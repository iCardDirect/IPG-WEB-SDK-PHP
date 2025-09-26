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

    protected function setPostParams(): void
    {
        parent::setPostParams();
        unset($this->params['ModalType']);
        $this->_addPostParam( 'Theme', $this->getTheme() );
        $this->_addPostParam( 'PaymentType', $this->paymentType->getMethod() );
    }
}