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
	private function _generateSignature(): string
	{
        $privKey = openssl_get_privatekey( $this->getConfig()->getPrivateKey() );

        if ($this->getConfig()->getVersion() < Defines::NEW_SIGNATURE_VERSION) {
            // Concatenate all values from post and url encode them
            $concData = urlencode( stripslashes( implode( '', $this->params ) ) );

            $dataHash = sha1( $concData ); // Create sha1 hash of concatenated data
            openssl_sign( $dataHash, $signature, $privKey ); # Signed data in binary

            return base64_encode( $signature );
        }

        if ($this->getConfig()->getVersion() < 4.5) {
            // Concatenate all values from post and create base64 encode them
            $signParams = array_map([Helper::class, 'unescape'], $this->params);
            $concData = base64_encode( implode( '', $signParams ) );

            openssl_sign( $concData, $signature, $privKey, OPENSSL_ALGO_SHA256 ); # Signed data in binary with SHA256 algo

            return base64_encode( $signature );
        }

        $data = Helper::flattenNested('', $this->params, ':');

        //remove empty arrays
        $data = array_filter($data, function ($value) {
            if (is_array($value) && empty($value)) {
                return false;
            }
            return true;
        });

        $data = array_change_key_case($data);

        $data = array_map(function ($key, $value) {
            if (is_bool($value)) {
                $value = intval($value);
            }

            if (is_string($value)) {
                $value = mb_convert_encoding($value, 'UTF8');
            }

            if ($value === null || $value === '') {
                return $key . ':';
            }

            return $key . ':' . $value;
        }, array_keys($data), array_values($data));

        sort($data, SORT_NATURAL);

        $data = implode(';', $data);

        openssl_sign( $data, $signature, $privKey, OPENSSL_ALGO_SHA256 ); # Signed data in binary with SHA256 algo

        return base64_encode( $signature );
	}

	/**
	 * Send POST Request to API and returns Response object with validated response data
	 *
	 * @return Response
	 * @throws IPG_Exception
	 */
	protected function _processPost(): Response
	{
		$this->params['Signature'] = $this->_generateSignature();

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

            return new Response($this->getConfig(), $response->getBody()->getContents(), $this->getOutputFormat());
        } catch (ClientException $ex) {
            return new Response($this->getConfig(), $ex->getResponse()->getBody()->getContents(), $this->getOutputFormat());
        } catch (GuzzleException $ex) {
            throw new IPG_Exception( 'Error sending request to iCard IPG. Error message: ' . $ex->getMessage() );
        }
	}

}
