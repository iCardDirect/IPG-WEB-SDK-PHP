<?php

namespace IPG;

class PaymentToken extends Base
{
    /**
     * @var PaymentTypeInterface
     */
    private $paymentType;

    public function __construct(PaymentTypeInterface $paymentType)
    {
        $this->paymentType = $paymentType;
        parent::__construct($paymentType->getConfig());
    }

    /**
     * @return Response
     * @throws IPG_Exception
     */
    public function process(): Response
    {
        $this->validate();

        $this->_addPostParam( 'IPGmethod', $this->getMethod() );
        $this->_addPostParam( 'KeyIndex', $this->paymentType->getConfig()->getKeyIndex() );
        $this->_addPostParam( 'KeyIndexResp', $this->paymentType->getConfig()->getKeyIndexResp() );
        $this->_addPostParam( 'IPGVersion', $this->paymentType->getConfig()->getVersion() );
        $this->_addPostParam( 'Language', $this->paymentType->getConfig()->getLanguage() );
        $this->_addPostParam( 'Originator', $this->paymentType->getConfig()->getMerchantId() );
        $this->_addPostParam( 'OutputFormat', $this->getOutputFormat() );
        $this->_addPostParam( 'BannerIndex', $this->paymentType->getBannerIndex() );

        $this->_addPostParam( 'ModalType', $this->paymentType->getMethod() );
        $this->_addPostParam( 'URL_Notify', $this->paymentType->getUrlNotify() );

        if ($this->paymentType->getMethod() == 'IPG3DSPurchaseWithStoredCard') {
            $this->_addPostParam( 'CardToken', $this->paymentType->getCardToken() );
            $this->_addPostParam( 'VerifyCVC', (int)$this->paymentType->isVerifyCVC() );
        }

        $this->_addPostParam( 'MID', str_pad(intval($this->paymentType->getConfig()->getVirtualTerminalId()), 15, '0', STR_PAD_LEFT) );
        $this->_addPostParam( 'MIDName', $this->paymentType->getConfig()->getMerchantName() );
        $this->_addPostParam( 'Currency', $this->paymentType->getCurrency() );
        $this->_addPostParam( 'Amount', $this->paymentType->getAmount() );
        $this->_addPostParam( 'OrderID', $this->paymentType->getOrderId() );
        if (!empty($this->paymentType->getNote())) {
            $this->_addPostParam( 'Note', $this->paymentType->getNote() );
        }

        $this->_addPostParam( 'CustomerIP', $this->paymentType->getCustomer()->getIP() );
        $this->_addPostParam( 'Email', $this->paymentType->getCustomer()->getEmail() );
        if ( !empty( $this->paymentType->getCustomer()->getIdentifier() ) ) {
            $this->_addPostParam( 'CustomerIdentifier', $this->paymentType->getCustomer()->getIdentifier() );
        }
        if ( !empty( $this->paymentType->getCustomer()->getMobileNumber() ) ) {
            $this->_addPostParam( 'MobileNumber', $this->paymentType->getCustomer()->getMobileNumber() );
        }

        if ( !empty( $this->paymentType->getBillingAddress() ) ) {
            $this->_addPostParam( 'BillAddrCountry', $this->paymentType->getBillingAddress()->getBillAddrCountry() );
            $this->_addPostParam( 'BillAddrPostCode', $this->paymentType->getBillingAddress()->getBillAddrPostCode() );
            $this->_addPostParam( 'BillAddrCity', $this->paymentType->getBillingAddress()->getBillAddrCity() );
            $this->_addPostParam( 'BillAddrLine1', $this->paymentType->getBillingAddress()->getBillAddrLine1() );

            if ( !empty($this->paymentType->getBillingAddress()->getBillAddrState() ) ) {
                $this->_addPostParam( 'BillAddrState', $this->paymentType->getBillingAddress()->getBillAddrState() );
            }

            if ( !empty($this->paymentType->getBillingAddress()->getBillAddrLine2() ) ) {
                $this->_addPostParam( 'BillAddrLine2', $this->paymentType->getBillingAddress()->getBillAddrLine2() );
            }

            if ( !empty($this->paymentType->getBillingAddress()->getBillAddrLine3() ) ) {
                $this->_addPostParam( 'BillAddrLine3', $this->paymentType->getBillingAddress()->getBillAddrLine3() );
            }
        }

        if (!empty($this->paymentType->getCart())) {
            $this->_addPostParam( 'CartItems', $this->paymentType->getCart()->getTotalItemsCount() );
            $cartItemLoopIndex = 0;

            foreach ( $this->paymentType->getCart()->getCart() as $cartItem ) {
                $cartItemLoopIndex += 1;
                $this->_addPostParam( 'Article_' . $cartItemLoopIndex, $cartItem['name'] );
                $this->_addPostParam( 'Quantity_' . $cartItemLoopIndex, $cartItem['quantity'] );
                $this->_addPostParam( 'Price_' . $cartItemLoopIndex, $cartItem['price'] );
                $this->_addPostParam( 'Currency_' . $cartItemLoopIndex, $this->paymentType->getCurrency() );
                $totalPrice = round( ($cartItem['quantity'] * $cartItem['price'] ), 2 );
                $this->_addPostParam( 'Amount_' . $cartItemLoopIndex, $totalPrice );
            }
        }

        return $this->_processPost();
    }

    public function getMethod(): string
    {
        return 'IPGPaymentToken';
    }

    /**
     * @return void
     * @throws IPG_Exception
     */
    private function validate()
    {
        if ( $this->paymentType->getUrlNotify() === null || !Helper::isValidURL( $this->paymentType->getUrlNotify() ) ) {
            throw new IPG_Exception( 'Invalid Notify URL' );
        }

        if ( empty( $this->paymentType->getCurrency() ) ) {
            throw new IPG_Exception( 'Invalid currency' );
        }

        if ( empty( $this->paymentType->getAmount()  ) || !Helper::isValidAmount( $this->paymentType->getAmount()) ) {
            throw new IPG_Exception( 'Invalid amount' );
        }

        if ( empty( $this->paymentType->getOrderId() ) ) {
            throw new IPG_Exception( 'Invalid OrderId param' );
        }

        if ( $this->paymentType->getConfig()->getMerchantName() === null ) {
            throw new IPG_Exception( 'Invalid merchant name' );
        }

        if ( $this->paymentType->getCustomer()->getEmail() === null || !Helper::isValidEmail( $this->paymentType->getCustomer()->getEmail()) ) {
            throw new IPG_Exception( 'Missing or invalid email' );
        }

        try {
            $this->getConfig()->validate();
        }
        catch ( IPG_Exception $ex ) {
            throw new IPG_Exception( 'Invalid Config details: ' . $ex->getMessage() );
        }

        if ($this->paymentType->getConfig()->getVersion() < 4.5 && $this->paymentType->getMethod() == 'IPGPurchase') {
            try {
                $this->paymentType->getCart()->validate();
            }
            catch ( IPG_Exception $ex ) {
                throw new IPG_Exception( 'Invalid Cart details: ' . $ex->getMessage() );
            }
        }
    }
}