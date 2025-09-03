<?php

namespace IPG;

class EmbeddedPayment extends PaymentToken
{
    public function getMethod(): string
    {
        return 'IPGEmbeddedPayment';
    }
}