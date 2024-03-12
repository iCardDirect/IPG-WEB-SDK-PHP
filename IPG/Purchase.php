<?php

namespace IPG;

class Purchase extends Base
{

	/**
	 * @var Cart 
	 */
	private $cart;

	/**
	 * ISO 3166-1 numeric code
	 * 
	 * @var string 
	 */
	private $currency;

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

    /**
     * @var BillingAddress
     */
    private $billingAddress;

    /**
	 * Back-End notify url. POST Request will be send.
	 * 
	 * @var string 
	 */
	private $urlNotify;

	/**
	 * Client is redirected to this url after successful payment
	 * 
	 * @var string 
	 */
	private $urlOK;

	/**
	 * Client is redirected to this url after cancellation
	 * 
	 * @var string 
	 */
	private $urlCancel;

	/**
	 * The link of the page with the order from the merchant web shop.
	 * 
	 * @var string 
	 */
	private $orderLink;

	/**
	 * Index specified in IPG for every banner provided by the
	 * Merchant. The Merchant may choose to select a proper
	 * banner for every payment. The banner is displayed on the payment page.
	 * 
	 * @var int 
	 */
	private $bannerIndex = 1;

	/**
	 * Text associated with the purchase.
	 * 
	 * @var string 
	 */
	private $note;

	public function __construct(Config $config)
	{
		$this->_setConfig( $config );
	}

	/**
	 * Set currency numeric code ISO 3166-1
	 * 
	 * @param string $currencyCode
	 * 
	 * @return Purchase
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
	 * Set Cart
	 * 
	 * @param \IPG\Cart $cart
	 * 
	 * @return Purchase
	 */
	public function setCart(Cart $cart)
	{
		$this->cart = $cart;

		return $this;
	}

	/**
	 * Get cart
	 * 
	 * @return Cart
	 */
	public function getCart()
	{
		return $this->cart;
	}

	/**
	 * Set custom unique order id
	 * 
	 * @param string $orderId
	 * 
	 * @return Purchase
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
	 * Set Back-End Notify URL address
	 * 
	 * @param type $urlNotify
	 * 
	 * @return Purchase
	 */
	public function setUrlNotify($urlNotify)
	{
		$this->urlNotify = $urlNotify;

		return $this;
	}

	public function getUrlNotify()
	{
		return $this->urlNotify;
	}

	/**
	 * Set URL OK. Client is redirected to this url after successful payment
	 * 
	 * @param string $urlOK
	 * 
	 * @return Purchase
	 */
	public function setUrlOK($urlOK)
	{
		$this->urlOK = $urlOK;

		return $this;
	}

	/**
	 * URL OK
	 * 
	 * @return string
	 */
	public function getUrlOK()
	{
		return $this->urlOK;
	}

	/**
	 * Cancel URL. Client is redirected to this url after cancellation.
	 * 
	 * @param string $urlCancel
	 * 
	 * @return Purchase
	 */
	public function setUrlCancel($urlCancel)
	{
		$this->urlCancel = $urlCancel;

		return $this;
	}

	/**
	 * URL Cancel
	 * 
	 * @return string
	 */
	public function getUrlCancel()
	{
		return $this->urlCancel;
	}

	/**
	 * Set customer object
	 * 
	 * @param \IPG\Customer $customer
	 * 
	 * @return Purchase
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
     * @return BillingAddress
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param BillingAddress $billingAddress
     * @return Purchase
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
	 * The link of the page with the order from the merchant web shop.
	 * 
	 * @param string $orderLink
	 * 
	 * @return Purchase
	 */
	public function setOrderLink($orderLink)
	{
		$this->orderLink = $orderLink;

		return $this;
	}

	/**
	 * Order Link
	 * 
	 * @return string
	 */
	public function getOrderLink()
	{
		return $this->orderLink;
	}

	/**
	 * Set Banner Index
	 * 
	 * @param int $bannerIndex
	 * 
	 * @return Purchase
	 */
	public function setBannerIndex($bannerIndex)
	{
		$this->bannerIndex = $bannerIndex;

		return $this;
	}

	/**
	 * Banner Index
	 * 
	 * @return int
	 */
	public function getBannerIndex()
	{
		return $this->bannerIndex;
	}

	/**
	 * Set Note
	 * 
	 * @param string $note
	 * 
	 * @return Purchase
	 */
	public function setNote($note)
	{
		$this->note = $note;

		return $this;
	}

	/**
	 * Note
	 * 
	 * @return string
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * Process IPG purchase
	 */
	public function process()
	{
		$this->validate();

		$this->_addPostParam( 'IPGmethod', 'IPGPurchase' );
		$this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
		$this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
        $this->_addPostParam( 'KeyIndexResp', $this->getConfig()->getKeyIndexResp() );
		$this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
		$this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );
		$this->_addPostParam( 'MID', $this->getConfig()->getVirtualTerminalId() );
		$this->_addPostParam( 'MIDName', $this->getConfig()->getMerchantName() );

		$this->_addPostParam( 'OrderID', $this->getOrderId() );
		$this->_addPostParam( 'BannerIndex', $this->getBannerIndex() );
		$this->_addPostParam( 'Currency', $this->getCurrency() );
		$this->_addPostParam( 'CustomerIP', $this->customer->getIP() );
		$this->_addPostParam( 'Email', $this->customer->getEmail() );
        if ( !empty( $this->customer->getIdentifier() ) ) {
            $this->_addPostParam( 'CustomerIdentifier', $this->customer->getIdentifier() );
        }
        if ( !empty( $this->customer->getMobileNumber() ) ) {
            $this->_addPostParam( 'MobileNumber', $this->customer->getMobileNumber() );
        }

        if ( !empty( $this->getOrderLink() ) ) {
			$this->_addPostParam( 'OrderLink', $this->getOrderLink() );
		}
		if ( !empty( $this->getNote() ) ) {
			$this->_addPostParam( 'Note', $this->getNote() );
		}

		$this->_addPostParam( 'URL_OK', $this->getUrlOK() );
		$this->_addPostParam( 'URL_Cancel', $this->getUrlCancel() );
		$this->_addPostParam( 'URL_Notify', $this->getUrlNotify() );

        if ( !empty( $this->billingAddress ) ) {
            $this->_addPostParam( 'BillAddrCountry', $this->billingAddress->getBillAddrCountry() );
            $this->_addPostParam( 'BillAddrPostCode', $this->billingAddress->getBillAddrPostCode() );
            $this->_addPostParam( 'BillAddrCity', $this->billingAddress->getBillAddrCity() );
            $this->_addPostParam( 'BillAddrLine1', $this->billingAddress->getBillAddrLine1() );

            if ( !empty($this->billingAddress->getBillAddrState() ) ) {
                $this->_addPostParam( 'BillAddrState', $this->billingAddress->getBillAddrState() );
            }

            if ( !empty($this->billingAddress->getBillAddrLine2() ) ) {
                $this->_addPostParam( 'BillAddrLine2', $this->billingAddress->getBillAddrLine2() );
            }

            if ( !empty($this->billingAddress->getBillAddrLine3() ) ) {
                $this->_addPostParam( 'BillAddrLine3', $this->billingAddress->getBillAddrLine3() );
            }
        }

        $this->_addPostParam( 'CartItems', $this->getCart()->getTotalItemsCount() );
		$cartItemLoopIndex = 0;
		$totalCartAmount = 0;

		foreach ( $this->getCart()->getCart() as $cartItem ) {
			$cartItemLoopIndex += 1;
			$this->_addPostParam( 'Article_' . $cartItemLoopIndex, $cartItem['name'] );
			$this->_addPostParam( 'Quantity_' . $cartItemLoopIndex, $cartItem['quantity'] );
			$this->_addPostParam( 'Price_' . $cartItemLoopIndex, $cartItem['price'] );
			$this->_addPostParam( 'Currency_' . $cartItemLoopIndex, $this->getCurrency() );
			$totalPrice = round( ($cartItem['quantity'] * $cartItem['price'] ), 2 );
			$totalCartAmount += $totalPrice;
			$this->_addPostParam( 'Amount_' . $cartItemLoopIndex, $totalPrice );
		}
		$this->_addPostParam( 'Amount', $totalCartAmount );

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

		if ( empty( $this->getCurrency() ) ) {
			throw new IPG_Exception( 'Invalid currency' );
		}
		if ( $this->getConfig()->getMerchantName() === null ) {
			throw new IPG_Exception( 'Invalid merchant name' );
		}
		if ( empty( $this->getOrderId() ) ) {
			throw new IPG_Exception( 'Invalid OrderId param' );
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

		try {
			$this->getCart()->validate();
		}
		catch ( \Exception $ex ) {
			throw new IPG_Exception( 'Invalid Cart details: ' . $ex->getMessage() );
		}

		return true;
	}

}
