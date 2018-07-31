<?php

namespace IPG;

class TxnStatus extends Base
{
	/**
	 * Transaction order id which status is required
	 * 
	 * @var string 
	 */
	private $orderId;

	public function __construct(Config $config)
	{
		$this->_setConfig( $config );
	}

	/**
	 * Set Transaction order id which status is required
	 * 
	 * @param string $orderId
	 * 
	 * @return TxnStatus
	 */
	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;

		return $this;
	}

	/**
	 * Order ID
	 * 
	 * @return string
	 */
	public function getOrderId()
	{
		return $this->orderId;
	}

	/**
	 * Process IPG GetTxnStatus
	 */
	public function process()
	{
		$this->validate();

		$this->_addPostParam( 'IPGmethod', 'IPGGetTxnStatus' );
		$this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
		$this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
		$this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
		$this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );

		$this->_addPostParam( 'MID', $this->getConfig()->getVirtualTerminalId() );
		$this->_addPostParam( 'OrderID', $this->getOrderId() );
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
		if ( empty( $this->getOrderId() ) ) {
			throw new IPG_Exception( 'Invalid OrderId param' );
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
