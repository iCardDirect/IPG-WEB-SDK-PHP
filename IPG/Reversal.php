<?php

namespace IPG;

class Reversal extends Base
{
    /**
     * Unique order id
     *
     * @var string
     */
    private $orderId;

    /**
	 * Transaction identifier. Returned by IPG after Purchase
	 * 
	 * @var string 
	 */
	private $trnRef;

	/**
	 * Set transaction reference. Unique identifier, returned after purchase
	 * 
	 * @param string $trnRef
	 * 
	 * @return Reversal
	 */
	public function setTrnRef(string $trnRef): Reversal
    {
		$this->trnRef = $trnRef;
		
		return $this;
	}

	/**
	 * Transaction Reference
	 * @return string
	 */
	public function getTrnRef(): string
    {
		return $this->trnRef;
	}
	
	/**
	 * Process IPG Reversal
	 */
	public function process()
	{
		$this->_buildPostData();

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

		try {
			$this->getConfig()->validate($this->getConfig()->getVersion() >= 4.5);
		}
		catch ( \Exception $ex ) {
			throw new IPG_Exception( 'Invalid Config details: ' . $ex->getMessage() );
		}

		return true;
	}

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): Reversal
    {
        $this->orderId = $orderId;
        return $this;
    }

    protected function _buildPostData(): void
    {
        $this->params = [];

        $this->validate();

        $this->_addPostParam( 'IPGmethod', 'IPGReversal' );
        $this->_addPostParam( 'Originator', $this->getConfig()->getMerchantId() );
        $this->_addPostParam( 'KeyIndex', $this->getConfig()->getKeyIndex() );
        $this->_addPostParam( 'KeyIndexResp', $this->getConfig()->getKeyIndexResp() );
        $this->_addPostParam( 'IPGVersion', $this->getConfig()->getVersion() );

        if ( !empty( $this->getConfig()->getVirtualTerminalId() ) ) {
            $this->_addPostParam( 'MID', Helper::formatMID( $this->getConfig()->getVirtualTerminalId() ) );
        }

        $this->_addPostParam( 'OrderID', $this->getOrderId() );
        $this->_addPostParam( 'IPG_Trnref', $this->getTrnRef() );
        $this->_addPostParam( 'OutputFormat', $this->getOutputFormat() );

        $this->_addPostParam('Signature', Crypto::generateSignature( $this->getConfig(), $this->params ));
    }
}
