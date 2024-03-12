<?php

namespace IPG;

class Moto extends FirstRecurring
{
	public function __construct(Config $config)
	{
		$this->_setConfig( $config );
	}

	/**
	 * Process IPG Moto
	 */
	public function process()
	{
		$this->validate();

		$this->_addPostParam( 'IPGmethod', 'IPGMoto' );
		$this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
		$this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
        $this->_addPostParam( 'KeyIndexResp', $this->getConfig()->getKeyIndexResp() );
		$this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
		$this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );
		$this->_addPostParam( 'MID', $this->getConfig()->getVirtualTerminalId() );
		$this->_addPostParam( 'MIDName', $this->getConfig()->getMerchantName() );

		$this->_addPostParam( 'Amount', $this->getAmount() );
		$this->_addPostParam( 'Currency', $this->getCurrency() );
		$this->_addPostParam( 'CustomerIP', $this->getCustomer()->getIP() );
		$this->_addPostParam( 'OrderID', $this->getOrderId() );
		$this->_addPostParam( 'BannerIndex', $this->getBannerIndex() );
		if ( !empty( $this->getOrderLink() ) ) {
			$this->_addPostParam( 'OrderLink', $this->getOrderLink() );
		}
		if ( !empty( $this->getNote() ) ) {
			$this->_addPostParam( 'Note', $this->getNote() );
		}

		$this->_addPostParam( 'URL_OK', $this->getUrlOK() );
		$this->_addPostParam( 'URL_Cancel', $this->getUrlCancel() );
		$this->_addPostParam( 'URL_Notify', $this->getUrlNotify() );


		$this->_processHtmlPost();

		return true;
	}

	/**
	 * Validate all set purchase details
	 *
	 * @return boolean
	 * @throws IPG_Exception
	 */
	public function validate()
	{
		if ( $this->getUrlCancel() === null || !Helper::isValidURL( $this->getUrlCancel() ) ) {
			throw new IPG_Exception( 'Invalid Cancel URL' );
		}
		if ( $this->getUrlNotify() === null || !Helper::isValidURL( $this->getUrlNotify() ) ) {
			throw new IPG_Exception( 'Invalid Notify URL' );
		}
		if ( $this->getUrlOk() === null || !Helper::isValidURL( $this->getUrlOk() ) ) {
			throw new IPG_Exception( 'Invalid Success URL' );
		}
		if ( empty( $this->getAmount() ) || !Helper::isValidAmount( $this->getAmount() ) ) {
			throw new IPG_Exception( 'Invalid amount param' );
		}
		if ( empty( $this->getOrderId() ) ) {
			throw new IPG_Exception( 'Invalid OrderId param' );
		}
		if ( empty( $this->getCurrency() ) ) {
			throw new IPG_Exception( 'Invalid currency' );
		}
		if ( $this->getConfig()->getMerchantName() === null ) {
			throw new IPG_Exception( 'Invalid merchant name' );
		}

		if ( !empty( $this->getOrderLink() ) && !Helper::isValidURL( $this->getOrderLink() ) ) {
			throw new IPG_Exception( 'Invalid OrderLink param' );
		}

		try {
			$this->getConfig()->validate();
		}
		catch ( \Exception $ex ) {
			throw new IPG_Exception( 'Invalid Config details: ' . $ex->getMessage() );
		}

		return true;
	}

}
