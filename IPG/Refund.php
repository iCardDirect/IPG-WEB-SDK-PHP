<?php

namespace IPG;

class Refund extends Base
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
	 * @return Refund
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
	 * @return Refund
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
	 * @return Refund
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
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return Refund
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Refund
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

	/**
	 * Process IPG Refund
	 */
	public function process()
	{
		$this->validate();

		$this->_addPostParam( 'IPGmethod', 'IPGRefund' );
		$this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
		$this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
        $this->_addPostParam( 'KeyIndexResp', $this->getConfig()->getKeyIndexResp() );
		$this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
		$this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );
		$this->_addPostParam( 'MID', $this->getConfig()->getVirtualTerminalId() );

		$this->_addPostParam( 'OrderID', $this->getOrderId() );
		$this->_addPostParam( 'Email', $this->getCustomer()->getEmail() );
		$this->_addPostParam( 'IPG_Trnref', $this->getTrnRef() );
		$this->_addPostParam( 'Amount', $this->getAmount() );
		$this->_addPostParam( 'Currency', $this->getCurrency() );
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
		
		try {
			$this->getConfig()->validate();
		}
		catch ( \Exception $ex ) {
			throw new IPG_Exception( 'Invalid Config details: ' . $ex->getMessage() );
		}

		return true;
	}

}
