<?php

namespace IPG;

class Response
{

	private $config;
	private $data;
	private $format;
	private $validateSignature = false;

	public function __construct(Config $config, $rawData, $format, $validateSignature = false)
	{
		$this->config = $config;
		$this->validateSignature = $validateSignature;

		$this->_parseData( $rawData, $format );
	}

	private function _parseData($rawData, $format)
	{
		if ( empty( $rawData ) ) {
			throw new IPG_Exception( 'Missing response data' );
		}

		switch ( $format ) {
			case Defines::OUTPUT_FORMAT_JSON:
				$this->data = json_decode( $rawData, true );
				break;
			case Defines::OUTPUT_FORMAT_XML:
				$this->data = ( array ) new \SimpleXMLElement( $rawData );
				if ( isset( $this->data['@attributes'] ) ) {
					unset( $this->data['@attributes'] );
				}
				break;
			case Defines::OUTPUT_FORMAT_POST:
				$this->data = $rawData;
				break;

			default:
				throw new IPG_Exception( 'Invalid Response Format' );
				break;
		}

		$this->format = $format;

		if ( empty( $this->data ) ) {
			throw new IPG_Exception( 'No IPG Response' );
		}
		$this->data = Helper::changeArrayKeysLowerCase( $this->data );

		if ( $this->validateSignature && !$this->verifySignature() ) {
			throw new IPG_Exception( 'Signature is not valid!' );
		}
		return $this;
	}

	/**
	 * Request param: Status
	 *
	 * @return int
	 */
	public function getStatus()
	{
		return ( int ) Helper::getArrayVal( $this->data, 'status' );
	}

	/**
	 * Request param: statusmsg
	 *
	 * @return string
	 */
	public function getStatusMsg()
	{
		return Defines::getMessageByStatus( $this->getStatus() );
	}

	/**
	 * Returns response data.
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Verify received signature
	 * 
	 * @return boolean
	 * @throws IPG_Exception
	 */
	private function verifySignature()
	{
		if ( empty( $this->data['signature'] ) ) {
			throw new IPG_Exception( 'Missing signature!' );
		}

		$signature = $this->data['signature'];
		unset( $this->data['signature'] );

		$pubKeyId = openssl_get_publickey( $this->config->getAPIPublicKey() );
		$signature = base64_decode( $signature );

        if ($this->config->getVersion() < Defines::NEW_SIGNATURE_VERSION) {
            $concData = urlencode( stripslashes( implode( '', $this->data ) ) );

            $res = openssl_verify( sha1( $concData ), $signature, $pubKeyId );
		} else {
            $concData = base64_encode( implode( '', $this->data ) );

            $res = openssl_verify( $concData, $signature, $pubKeyId, OPENSSL_ALGO_SHA256 );
        }

		openssl_free_key( $pubKeyId );

        return $res == 1;
	}

}
