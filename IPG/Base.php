<?php

namespace IPG;

use GuzzleHttp\Client;
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
	private $params = [];
	
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

	/**
	 * Get Config
	 * 
	 * @return Config
	 */
	protected function getConfig()
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
		$this->params['Signature'] = $this->_generateSignature();
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
	 * Create signature of API Request params
	 *
	 * @return string base64 encoded signature
	 */
	private function _generateSignature()
	{
        $privKey = openssl_get_privatekey( $this->getConfig()->getPrivateKey() );

        if ($this->getConfig()->getVersion() < Defines::NEW_SIGNATURE_VERSION) {
            // Concatenate all values from post and url encode them
            $concData = urlencode( stripslashes( implode( '', $this->params ) ) );

            $dataHash = sha1( $concData ); // Create sha1 hash of concatenated data
            openssl_sign( $dataHash, $signature, $privKey ); # Signed data in binary
        } else {
            // Concatenate all values from post and create base64 encode them
            $signParams = array_map([Helper::class, 'unescape'], $this->params);
            $concData = base64_encode( implode( '', $signParams ) );

            openssl_sign( $concData, $signature, $privKey, OPENSSL_ALGO_SHA256 ); # Signed data in binary with SHA256 algo
        }

        $signature = base64_encode( $signature ); # Base64 encoding of the signature

        return $signature; # Now you need to add the signature to the post request
	}

	/**
	 * Send POST Request to API and returns Response object with validated response data
	 *
	 * @return Response
	 * @throws IPG_Exception
	 */
	protected function _processPost()
	{
		$this->params['Signature'] = $this->_generateSignature();

        $client = new Client();
        try {
            $response = $client->request(
                'POST',
                $this->getConfig()->getIPGUrl(),
                [
                    'form_params' => $this->params
                ]
            );

            return new Response($this->getConfig(), $response->getBody()->getContents(), $this->getOutputFormat());
        } catch (GuzzleException $ex) {
            throw new IPG_Exception( 'Error sending request to iCard IPG. Error message: ' . $ex->getMessage() );
        }
	}

}
