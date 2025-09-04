<?php

namespace IPG;

class ThreeDSPurchaseWithStoredCard extends Base implements PaymentTypeInterface
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var BillingAddress
     */
    private $billingAddress;

    /**
     * @var int
     */
    private $currency;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $orderId;

    /**
     * Back-End notify url. POST Request will be sent.
     *
     * @var string
     */
    private $urlNotify;

    /**
     * Client is redirected to this url after successful payment /valid only for redirect integration/
     *
     * @var string
     */
    private $urlOK;

    /**
     * Client is redirected to this url after cancellation Generate Code…
     *
     * @var string
     */
    private $urlCancel;

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

    /**
     * Token of the previously stored card
     *
     * @var string
     */
    private $cardToken;

    /**
     * @var bool
     */
    private $isVerifyCVC = true;

    /**
     * @var string
     */
    private $postResultAction;

    public function getCart(): ?Cart
    {
        return null;
    }

    public function setCustomer(Customer $customer): ThreeDSPurchaseWithStoredCard
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setBillingAddress(BillingAddress $billingAddress): ThreeDSPurchaseWithStoredCard
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function getBillingAddress(): BillingAddress
    {
        return $this->billingAddress;
    }

    public function setCurrency(int $currency): ThreeDSPurchaseWithStoredCard
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setAmount(float $amount): ThreeDSPurchaseWithStoredCard
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setOrderId(string $orderId): ThreeDSPurchaseWithStoredCard
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setUrlNotify(string $urlNotify): ThreeDSPurchaseWithStoredCard
    {
        $this->urlNotify = $urlNotify;
        return $this;
    }

    public function getUrlNotify()
    {
        return $this->urlNotify;
    }

    public function setUrlOK(string $urlOK): ThreeDSPurchaseWithStoredCard
    {
        $this->urlOK = $urlOK;
        return $this;
    }

    public function getUrlOk(): string
    {
        return $this->urlOK;
    }

    public function setUrlCancel(string $urlCancel): ThreeDSPurchaseWithStoredCard
    {
        $this->urlCancel = $urlCancel;
        return $this;
    }

    public function getUrlCancel(): string
    {
        return $this->urlCancel;
    }

    public function setBannerIndex(int $bannerIndex): ThreeDSPurchaseWithStoredCard
    {
        $this->bannerIndex = $bannerIndex;
        return $this;
    }

    public function getBannerIndex()
    {
        return $this->bannerIndex;
    }

    public function getMethod(): string
    {
        return 'IPG3DSPurchaseWithStoredCard';
    }

    public function setCardToken(string $cardToken): ThreeDSPurchaseWithStoredCard
    {
        $this->cardToken = $cardToken;
        return $this;
    }

    public function getCardToken(): string
    {
        if ($this->getConfig()->getVersion() >= 4.5) {
            return $this->cardToken;
        }

        return Crypto::encrypt($this->getConfig(), $this->cardToken);
    }

    public function setIsVerifyCVC(bool $isVerifyCVC): ThreeDSPurchaseWithStoredCard
    {
        $this->isVerifyCVC = $isVerifyCVC;
        return $this;
    }

    public function isVerifyCVC(): bool
    {
        return $this->isVerifyCVC;
    }

    public function setNote(string $note): ThreeDSPurchaseWithStoredCard
    {
        $this->note = $note;
        return $this;
    }

    public function getNote(): string
    {
        return $this->note;
    }


    public function getPaymentContext(): ?string
    {
        return '';
    }

    public function getPostResultAction(): ?string
    {
        return $this->postResultAction;
    }

    public function setPostResultAction(string $postResultAction): ThreeDSPurchaseWithStoredCard
    {
        $this->postResultAction = $postResultAction;
        return $this;
    }


    /**
     * Validate all set purchase details
     *
     * @return void
     * @throws IPG_Exception
     */
    public function validate(): void
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

        if ( empty( $this->getCurrency() ) && !is_int( $this->getCurrency()) ) {
            throw new IPG_Exception( 'Invalid currency' );
        }

        if ( empty( $this->getAmount() ) && !is_float( $this->getAmount()) ) {
            throw new IPG_Exception( 'Invalid amount' );
        }

        if ( $this->getConfig()->getMerchantName() === null ) {
            throw new IPG_Exception( 'Missing merchant name' );
        }

        if ( empty( $this->getOrderId() ) ) {
            throw new IPG_Exception( 'Missing OrderId param' );
        }

        if ( empty( $this->getCardToken() ) ) {
            throw new IPG_Exception( 'Missing CardToken param' );
        }

        if (!empty($this->getPaymentContext()) && !Helper::isValidPaymentContext($this->getPaymentContext())) {
            throw new IPG_Exception( 'Invalid PaymentContext param' );
        }

        if (!empty($this->getPostResultAction()) && !Helper::isValidPostResultAction($this->getPostResultAction())) {
            throw new IPG_Exception( 'Invalid PostResultAction param' );;
        }

        try {
            $this->getConfig()->validate();
        }
        catch ( IPG_Exception $ex ) {
            throw new IPG_Exception( 'Invalid Config details: ' . $ex->getMessage() );
        }
    }

    /**
     * Process IPG3DSPurchaseWithStoredCard
     *
     * @return void
     * @throws IPG_Exception
     */
    public function process()
    {
        $this->validate();

        $this->_addPostParam( 'IPGmethod', $this->getMethod() );
        $this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
        $this->_addPostParam( 'KeyIndexResp', $this->getConfig()->getKeyIndexResp() );
        $this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );
        $this->_addPostParam( 'Language', $this->getConfig()->getLanguage() );
        $this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
        $this->_addPostParam( 'BannerIndex', $this->getBannerIndex() );
        if (!empty($this->getPostResultAction())) {
            $this->_addPostParam( 'PostResultAction', $this->getPostResultAction() );
        }

        $this->_addPostParam( 'URL_OK', $this->getUrlOK() );
        $this->_addPostParam( 'URL_Cancel', $this->getUrlCancel() );
        $this->_addPostParam( 'URL_Notify', $this->getUrlNotify() );

        if ($this->getConfig()->getVersion() >= 4.5) {
            $this->_addPostParam( 'CardToken', $this->getCardToken() );
        } else {
            $this->_addPostParam( 'Token', $this->getCardToken() );
        }
        $this->_addPostParam( 'VerifyCVC', $this->isVerifyCVC() ? '1' : '0' );

        $this->_addPostParam( 'MID', $this->getConfig()->getVirtualTerminalId() );
        $this->_addPostParam( 'Currency', $this->getCurrency() );
        $this->_addPostParam( 'Amount', $this->getAmount() );
        $this->_addPostParam( 'MIDName', $this->getConfig()->getMerchantName() );
        $this->_addPostParam( 'OrderID', $this->getOrderId() );

        $this->_addPostParam( 'CustomerIP', $this->customer->getIP() );
        $this->_addPostParam( 'Email', $this->customer->getEmail() );
        if ( !empty( $this->customer->getIdentifier() ) ) {
            $this->_addPostParam( 'CustomerIdentifier', $this->customer->getIdentifier() );
        }
        if ( !empty( $this->customer->getMobileNumber() ) ) {
            $this->_addPostParam( 'MobileNumber', $this->customer->getMobileNumber() );
        }

        if ( !empty( $this->getNote() ) ) {
            $this->_addPostParam( 'Note', $this->getNote() );
        }

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

        $this->_processHtmlPost();
    }
}