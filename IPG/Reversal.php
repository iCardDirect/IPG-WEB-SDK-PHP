<?php

namespace IPG;

class Reversal extends Base
{
	/**
	 * Transaction identifier. Returned by IPG after Purchase
	 * 
	 * @var string 
	 */
	private $trnRef;

	public function __construct(Config $config)
	{
		$this->_setConfig( $config );
	}

	/**
	 * Set transaction reference. Unique identifier, returned after purchase
	 * 
	 * @param string $trnRef
	 * 
	 * @return Reversal
	 */
	public function setTrnRef($trnRef)
	{
		$this->trnRef = $trnRef;
		
		return $this;
	}

	/**
	 * Transaction Reference
	 * @return string
	 */
	public function getTrnRef()
	{
		return $this->trnRef;
	}
	
	/**
	 * Process IPG Reversal
	 */
	public function process()
	{
		$this->validate();

		$this->_addPostParam( 'IPGmethod', 'IPGReversal' );
		$this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
		$this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
		$this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
		$this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );

		$this->_addPostParam( 'IPG_Trnref', $this->getTrnRef() );
		$this->_addPostParam( 'OutputFormat', $this->getOutputFormat() );
		
		return $this->_processPost();
	}

	/**
	 * Validate all statuses
	 *
	 * @return boolean
	 * @throws IPG_Exception
	 */
	public function validate()
	{
		if ( empty( $this->getTrnRef() ) ) {
			throw new IPG_Exception( 'Invalid TrnRef param' );
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
