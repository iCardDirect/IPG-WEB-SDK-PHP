<?php

namespace IPG;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class Base
{

	/**
	 * @var Config 
	 */
	protected $_config;

	/**
	 * Params needed for request
	 * 
	 * @var array 
	 */
	protected $params = [];
	
	/**
	 * @var string 
	 */
	private $outputFormat = Defines::OUTPUT_FORMAT_XML;

	public function __construct(Config $config)
	{
		$this->_setConfig( $config );
	}

	/**
	 * Set configuration
	 * 
	 * @param \IPG\Config $config
	 * 
	 * @return Base
	 */
	protected function _setConfig(Config $config)
	{
		$this->_config = $config;

		return $this;
	}

	public function getConfig(): Config
	{
		return $this->_config;
	}

	/**
	 * Response will be returned in this format
	 * 
	 * @param string $outputFormat
	 */
	public function setOutputFormat($outputFormat)
	{
		$this->outputFormat = $outputFormat;
	}

	/**
	 * Output Format
	 * 
	 * @return string
	 */
	public function getOutputFormat()
	{
		return $this->outputFormat;
	}
	
	/**
	 * Add params to post request
	 * 
	 * @param string $key
	 * 
	 * @param mixed $value
	 */
	protected function _addPostParam($key, $value)
	{
		$this->params[$key] = Helper::escape(Helper::unescape($value));
	}

	/**
	 * Generate HTML form with POST params and auto-submit it
	 */
	protected function _processHtmlPost()
	{
		// Add request signature
		$this->params['Signature'] = Crypto::generateSignature( $this->getConfig(), $this->params );
		$c = '<body onload="document.ipgForm.submit();">';
		$c .= '<form id="ipgForm" name="ipgForm" action="' . $this->getConfig()->getIPGUrl() . '" method="post">';
		foreach ( $this->params as $k => $v ) {
			$c .= "<input type=\"hidden\" name=\"" . $k . "\" value=\"" . $v . "\"  />\n";
		}
		$c .= '</form></body>';
		echo $c;
		exit;
	}

	/**
	 * Send POST Request to API and returns Response object with validated response data
	 *
	 * @return Response
	 * @throws IPG_Exception
	 */
	protected function _processPost(): Response
	{
		$this->params['Signature'] = Crypto::generateSignature( $this->getConfig(), $this->params );

        $client = new Client();
        try {
            $response = $client->request(
                'POST',
                $this->getConfig()->getIPGUrl(),
                [
                    'form_params' => $this->params,
                    'verify' => false,
                ]
            );

            return new Response($this->getConfig(), $response->getBody()->getContents(), $this->getOutputFormat(), true);
        } catch (ClientException $ex) {
            return new Response($this->getConfig(), $ex->getResponse()->getBody()->getContents(), $this->getOutputFormat(), true);
        } catch (GuzzleException $ex) {
            throw new IPG_Exception( 'Error sending request to iCard IPG. Error message: ' . $ex->getMessage() );
        }
	}

}
