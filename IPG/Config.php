<?php

namespace IPG;

class Config
{

	/**
	 * Version of IPG that is used
	 * @var string 
	 */
	private $version = '3.2';

	/**
	 * by default EN. ISO 2-character code for the desired language on the payment page. 
	 * Currently supporting EN, FR, DE, BG, ES, RO. 
	 * @var string 
	 */
	private $language = 'en';

	/**
	 * Merchant ID
	 * @var int 
	 */
	private $merchantId;

	/**
	 * Virtual terminal ID
	 * @var string 
	 */
	private $virtualTerminalId;

	/**
	 * Name used to show up when front-end request is used
	 * @var string 
	 */
	private $merchantName;

	/**
	 * Identifier of the private key used for signature
	 * @var int 
	 */
	private $keyIndex = 1;

	/**
	 * Requests will be send to this url.
	 * @var string
	 */
	private $ipgURL = 'https://devs.icards.eu/ipgtest';

	/**
	 * API public key
	 * @var string 
	 */
	private $APIPublicKey;

	/**
	 * Private key
	 * @var string 
	 */
	private $privateKey;

	/**
	 * IPG Version
	 * 
	 * @param string $version
	 * @return Config
	 */
	public function setVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * Get IPG Version
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Set Language
	 * 
	 * @param string $language
	 * @return Config
	 */
	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * Language ISO-2
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * Set merchant ID
	 * 
	 * @param int $merchantId
	 * @return Config
	 */
	public function setMerchantId($merchantId)
	{
		$this->merchantId = $merchantId;

		return $this;
	}

	/**
	 * Merchant ID
	 * @return int
	 */
	public function getMerchantId()
	{
		return $this->merchantId;
	}

	/**
	 * Set virtual terminal ID
	 * 
	 * @param string $vtId
	 * @return Config
	 */
	public function setVirtualTerminalId($vtId)
	{
		$this->virtualTerminalId = $vtId;

		return $this;
	}

	/**
	 * Virtual Terminal ID
	 *
	 * @return string
	 */
	public function getVirtualTerminalId()
	{
		return $this->virtualTerminalId;
	}

	/**
	 * Set merchant name. Visible on IPG Page
	 * 
	 * @param string $merchantName
	 * @return Config
	 */
	public function setMerchantName($merchantName)
	{
		$this->merchantName = $merchantName;

		return $this;
	}

	/**
	 * Merchant Name
	 * @return string
	 */
	public function getMerchantName()
	{
		return $this->merchantName;
	}

	/**
	 * Set identifier of the private key used for signature
	 * @param int $keyIndex
	 * @return Config
	 */
	public function setKeyIndex($keyIndex)
	{
		$this->keyIndex = $keyIndex;

		return $this;
	}

	/**
	 * Identifier of the private key used for signature
	 * 
	 * @return int
	 */
	public function getKeyIndex()
	{
		return $this->keyIndex;
	}

	/**
	 * Set IPG Request URL
	 * 
	 * @param string $ipgUrl
	 * @return Config
	 */
	public function setIPGUrl($ipgUrl)
	{
		$this->ipgURL = $ipgUrl;

		return $this;
	}

	/**
	 * Set IPG Request URL
	 * 
	 * @return string
	 */
	public function getIPGUrl()
	{
		return $this->ipgURL;
	}

	/**
	 * Set API Public Key
	 * @param string $apiPublicKey
	 * 
	 * @return Config
	 */
	public function setAPIPublicKey($apiPublicKey)
	{
		$this->APIPublicKey = $apiPublicKey;

		return $this;
	}

	/**
	 * IPG API public RSA key as a filepath
	 *
	 * @param string $path Path to certificate
	 *
	 * @return Config
	 * @throws IPG_Exception
	 */
	public function setAPIPublicKeyPath($path)
	{
		if ( !is_file( $path ) || !is_readable( $path ) ) {
			throw new IPG_Exception( 'Public key not found in:' . $path );
		}
		$this->APIPublicKey = file_get_contents( $path );

		return $this;
	}

	/**
	 * Get API Public Key
	 * @return string
	 */
	public function getAPIPublicKey()
	{
		return $this->APIPublicKey;
	}

	/**
	 * Set private key
	 * @param string $privateKey
	 * 
	 * @return Config
	 */
	public function setPrivateKey($privateKey)
	{
		$this->privateKey = $privateKey;

		return $this;
	}

	/**
	 * Private RSA key as a filepath
	 *
	 * @param string $path File path
	 *
	 * @return Config
	 * @throws IPG_Exception
	 */
	public function setPrivateKeyPath($path)
	{
		if ( !is_file( $path ) || !is_readable( $path ) ) {
			throw new IPG_Exception( 'Private key not found in:' . $path );
		}
		$this->privateKey = file_get_contents( $path );
		return $this;
	}

	/**
	 * Get Private Key
	 * 
	 * @return string
	 */
	public function getPrivateKey()
	{
		return $this->privateKey;
	}

	/**
	 * Validate all set config details
	 *
	 * @return boolean
	 * @throws IPG_Exception
	 */
	public function validate()
	{
		if ( empty( $this->getKeyIndex() ) || !is_numeric( $this->getKeyIndex() ) ) {
			throw new IPG_Exception( 'Invalid Key Index' );
		}
		if ( empty( $this->getIPGUrl() ) || !Helper::isValidURL( $this->getIPGUrl() ) ) {
			throw new IPG_Exception( 'Invalid IPC URL' );
		}
		if ( empty( $this->getMerchantId() ) || !is_int( $this->getMerchantId() ) ) {
			throw new IPG_Exception( 'Invalid Merchant ID' );
		}
		if ( $this->getVersion() == null ) {
			throw new IPG_Exception( 'Invalid IPG Version' );
		}
		if ( empty( $this->getVirtualTerminalId() ) ) {
			throw new IPG_Exception( 'Invalid Virtual Terminal ID' );
		}
		if ( !openssl_get_privatekey( $this->getPrivateKey() ) ) {
			throw new IPG_Exception( 'Invalid Private key' );
		}
		return true;
	}

}
