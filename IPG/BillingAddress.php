<?php

namespace IPG;

class BillingAddress
{
    /**
     * ISO 3166-1 numeric three-digit country code
     *
     * @var int
     */
    private $billAddrCountry;

    /**
     * @var string
     */
    private $billAddrState;

    /**
     * @var string
     */
    private $billAddrPostCode;

    /**
     * @var string
     */
    private $billAddrCity;

    /**
     * @var string
     */
    private $billAddrLine1;

    /**
     * @var string
     */
    private $billAddrLine2;

    /**
     * @var string
     */
    private $billAddrLine3;

    /**
     * @return int
     */
    public function getBillAddrCountry()
    {
        return $this->billAddrCountry;
    }

    /**
     * @param int $billAddrCountry
     * @return BillingAddress
     */
    public function setBillAddrCountry($billAddrCountry)
    {
        $this->billAddrCountry = $billAddrCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillAddrState()
    {
        return $this->billAddrState;
    }

    /**
     * @param string $billAddrState
     * @return BillingAddress
     */
    public function setBillAddrState($billAddrState)
    {
        $this->billAddrState = $billAddrState;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillAddrPostCode()
    {
        return $this->billAddrPostCode;
    }

    /**
     * @param string $billAddrPostCode
     * @return BillingAddress
     */
    public function setBillAddrPostCode($billAddrPostCode)
    {
        $this->billAddrPostCode = $billAddrPostCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillAddrCity()
    {
        return $this->billAddrCity;
    }

    /**
     * @param string $billAddrCity
     * @return BillingAddress
     */
    public function setBillAddrCity($billAddrCity)
    {
        $this->billAddrCity = $billAddrCity;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillAddrLine1()
    {
        return $this->billAddrLine1;
    }

    /**
     * @param string $billAddrLine1
     * @return BillingAddress
     */
    public function setBillAddrLine1($billAddrLine1)
    {
        $this->billAddrLine1 = $billAddrLine1;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillAddrLine2()
    {
        return $this->billAddrLine2;
    }

    /**
     * @param string $billAddrLine2
     * @return BillingAddress
     */
    public function setBillAddrLine2($billAddrLine2)
    {
        $this->billAddrLine2 = $billAddrLine2;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillAddrLine3()
    {
        return $this->billAddrLine3;
    }

    /**
     * @param string $billAddrLine3
     * @return BillingAddress
     */
    public function setBillAddrLine3($billAddrLine3)
    {
        $this->billAddrLine3 = $billAddrLine3;
        return $this;
    }
}