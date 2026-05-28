<?php

namespace IPG;

class EmbeddedPayment extends PaymentToken
{
    /**
     * Merchant theme to customize the payment page
     *
     * @var string
     */
    private $theme;

    public function getMethod(): string
    {
        return 'IPGEmbeddedPayment';
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): EmbeddedPayment
    {
        $this->theme = $theme;
        return $this;
    }

    protected function _buildPostData(): void
    {
        parent::_buildPostData();

        unset($this->params['ModalType']);
        unset($this->params['Signature']);

        $this->_addPostParam( 'Theme', $this->getTheme() );
        $this->_addPostParam( 'PaymentType', $this->paymentType->getMethod() );

        $this->_addPostParam('Signature', Crypto::generateSignature( $this->getConfig(), $this->params ));
    }
}