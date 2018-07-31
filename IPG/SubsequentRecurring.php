<?php

namespace IPG;

class SubsequentRecurring extends Base
{

	/**
	 * Transaction identifier. Returned by IPG after Purchase
	 * 
	 * @var string 
	 */
	private $trnRef;

	/**
	 * ISO 3166-1 numeric code
	 * 
	 * @var string 
	 */
	private $currency;

	/**
	 * The amount of the payment
	 * 
	 * @var $amount
	 */
	private $amount;

	/**
	 * Unique order id
	 * 
	 * @var string 
	 */
	private $orderId;

	/**
	 * @var Customer 
	 */
	private $customer;

	public function __construct(Config $config)
	{
		$this->_setConfig( $config );
	}

	/**
	 * Set transaction reference. Unique identifier, returned after purchase
	 * 
	 * @param string $trnRef
	 * 
	 * @return SubsequentRecurring
	 */
	public function setTrnRef($trnRef)
	{
		$this->trnRef = $trnRef;

		return $this;
	}

	/**
	 * Transaction Reference
	 * 
	 * @return string
	 */
	public function getTrnRef()
	{
		return $this->trnRef;
	}

	/**
	 * Set currency numeric code ISO 3166-1
	 * 
	 * @param string $currencyCode
	 * 
	 * @return SubsequentRecurring
	 */
	public function setCurrency($currencyCode)
	{
		$this->currency = $currencyCode;

		return $this;
	}

	/**
	 * Currency numeric code
	 * 
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * Set amount of purchase
	 * 
	 * @param double $amount
	 * 
	 * @return SubsequentRecurring
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}

	/**
	 * Amount
	 * 
	 * @return double
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Set custom unique order id
	 * 
	 * @param string $orderId
	 * 
	 * @return SubsequentRecurring
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
	 * Set customer object
	 * 
	 * @param \IPG\Customer $customer
	 * 
	 * @return SubsequentRecurring
	 */
	public function setCustomer(Customer $customer)
	{
		$this->customer = $customer;

		return $this;
	}

	/**
	 * Get Customer 
	 * 
	 * @return Customer
	 */
	public function getCustomer()
	{
		return $this->customer;
	}

	/**
	 * Process IPG Subsequent Recurring
	 */
	public function process()
	{
		$this->validate();

		$this->_addPostParam( 'IPGmethod', 'IPGSubsequentRecurring' );
		$this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
		$this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
		$this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
		$this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );

		$this->_addPostParam( 'MID', $this->getConfig()->getVirtualTerminalId() );
		$this->_addPostParam( 'IPG_Trnref', $this->getTrnRef() );
		$this->_addPostParam( 'OrderID', $this->getOrderId() );
		$this->_addPostParam( 'Amount', $this->getAmount() );
		$this->_addPostParam( 'Currency', $this->getCurrency() );
		$this->_addPostParam( 'Email', $this->customer->getEmail() );
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
		if ( empty( $this->getAmount() ) || !Helper::isValidAmount( $this->getAmount() ) ) {
			throw new IPG_Exception( 'Invalid amount param' );
		}
		if ( empty( $this->getCurrency() ) ) {
			throw new IPG_Exception( 'Invalid currency' );
		}
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
