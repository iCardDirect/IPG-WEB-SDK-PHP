<?php

namespace IPG;

interface PaymentTypeInterface
{
    public function getConfig(): Config;
    public function getCart(): ?Cart;
    public function getCustomer(): Customer;
    public function getBillingAddress(): BillingAddress;
    public function getCurrency();
    public function getAmount();
    public function getOrderId();
    public function getUrlNotify();
    public function getBannerIndex();
    public function getMethod(): string;
    public function getCardToken(): string;
    public function isVerifyCVC(): bool;
    public function getNote(): string;
}