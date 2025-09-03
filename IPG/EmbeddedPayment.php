<?php

namespace IPG;

class EmbeddedPayment extends PaymentToken
{
    public function getMethod(): string
    {
        return 'IPGEmbeddedPayment';
    }

    protected function setPostParams(): void
    {
        parent::setPostParams();
        unset($this->params['ModalType']);
        $this->_addPostParam( 'PaymentType', $this->paymentType->getMethod() );
    }
}