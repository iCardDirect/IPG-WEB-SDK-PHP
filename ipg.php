<?php

require_once 'config.php';

class IPG
{

	protected $_backEndRequest = false;

	/**
	 * Send request to IPG
	 * @param array $postData must contain all required params for specific method call
	 * @return mixed Used only when request is back end
	 */
	public function request($postData)
	{
		$postData = $this->setDefaultParams( $postData );
		$postData['Signature'] = $this->generateSignature( $postData );

		if ( !$this->_backEndRequest ) {
			$this->createForm( $postData );
		}
		else {
			return $this->backEndRequest( $postData );
		}

		return null;
	}

	/**
	 * Front-End Request. This is the standard method for checkout at web shop.
	 * @param array $postData
	 */
	public function IPGPurchase($postData)
	{
		$postData['IPGmethod'] = 'IPGPurchase';
		
		return $this->request($postData);
	}

	/**
	 * Front-End Request. 
	 * This is a method for processing a first transaction of subscription agreement.
	 * @param array $postData
	 * @return type
	 */
	public function IPGFirstRecurring($postData)
	{
		$postData['IPGmethod'] = 'IPGFirstRecurring';
		
		return $this->request($postData);
	}
	
	/**
	 * Front-End Request. 
	 * This is a method for processing a MOTO transactions by merchant.
	 * @param array $postData
	 * @return type
	 */
	public function IPGMoto($postData)
	{
		$postData['IPGmethod'] = 'IPGMoto';
		
		return $this->request($postData);
	}
	
	/**
	 * Back-End request.
	 * Returns the status and the parameters of a previously executed payment. 
	 * Usually for back-office.
	 * @param array $postData
	 * @return type
	 */
	public function IPGGetTxnStatus($postData)
	{
		$this->_backEndRequest = true;
		$postData['IPGmethod'] = 'IPGGetTxnStatus';
		
		return $this->request($postData);
		
	}
	
	/**
	 * Back-End request.
	 * This command cancels a previously executed payment (void). 
	 * Usually for back-office. 
	 * @param array $postData
	 * @return type
	 */
	public function IPGReversal($postData)
	{
		$this->_backEndRequest = true;
		$postData['IPGmethod'] = 'IPGReversal';
		
		return $this->request($postData);
		
	}
		
	/**
	 * Back-End request.
	 * Credit to cardholder, e.g. return money. Usually for back-office.
	 * @param array $postData
	 * @return type
	 */
	public function IPGRefund($postData)
	{
		$this->_backEndRequest = true;
		$postData['IPGmethod'] = 'IPGRefund';
		
		return $this->request($postData);
		
	}
	
	/**
	 * Back-End request.
	 * This is a method for processing a subsequent recurring transaction 
	 * after subscription by merchant.
	 * @param array $postData
	 * @return type
	 */
	public function IPGSubsequentRecurring($postData)
	{
		$this->_backEndRequest = true;
		$postData['IPGmethod'] = 'IPGSubsequentRecurring';
		
		return $this->request($postData);
		
	}
	
	/**
	 * Front-End request.
	 * Method used to store(save) card and then use to make payments.
	 * @param array $postData
	 * @return type
	 */
	public function IPGStoreCard($postData)
	{
		$postData['IPGmethod'] = 'IPGStoreCard';
		
		return $this->request($postData);
	}
	
	/**
	 * Manually set request type in case of using request() method directly.
	 * @param boolean $isBackEndRequest
	 */
	public function setIsBackEndRequest($isBackEndRequest)
	{
		$this->_backEndRequest = $isBackEndRequest;
	}
	
	/**
	 * Sets default params in every call
	 * @param array $postData
	 * @return type
	 */
	protected function setDefaultParams($postData)
	{
		$defaultParams = array(
			'KeyIndex' => KEY_INDEX,
			'IPGVersion' => IPGVersion,
			'Language' => LANGUAGE,
			'Originator' => ORIGINATOR
		);

		foreach ( $defaultParams as $key => $value ) {
			if ( !isset( $postData[$key] ) ) {
				$postData[$key] = $value;
			}
		}

		// Only front end params
		$frontEndRequestParams = array(
			'MID' => MID,
			'MIDName' => MID_NAME,
			'URL_OK' => URL_OK,
			'URL_Cancel' => URL_Cancel,
			'URL_Notify' => URL_Notify,
		);
		if ( !$this->_backEndRequest ) {
			foreach ( $frontEndRequestParams as $key => $value ) {
				$postData[$key] = $value;
			}
		}

		return $postData;
	}

	/**
	 * Generate signature
	 * @param array $postData
	 * @return string
	 */
	public function generateSignature($postData)
	{
		// Concatenate all values from post and url encode them
		$concData = urlencode( stripslashes( implode( '', $postData ) ) );

		$dataHash = sha1( $concData ); // Create sha1 hash of concatenated data
		$privKey = openssl_get_privatekey( PRIVATE_KEY );
		openssl_sign( $dataHash, $signature, $privKey ); # Signed data in binary
		$signature = base64_encode( $signature ); # Base64 encoding of the signature
		return $signature; # Now you need to add the signature to the post request
	}

	/**
	 * Create and submit form. After successful submit, IPG Payment Page will be loaded
	 * @param array $postData
	 */
	public function createForm($postData)
	{
		$postUrl = DEV_MODE ? IPG_TEST_URL : IPG_URL;
		$formHtml = ''
				. '<html>
			<head>
				<title>IPG Auto Submit Form</title>
			</head>
			<body onload="document.forms[\'ipg\'].submit();">
				<form name="ipg" method="post" action="' . $postUrl . '" hidden>';
		foreach ( $postData as $key => $value ) {
			$formHtml .= '<input name="' . $key . '" type="text" value="' . $value . '"/>';
		}

		$formHtml .= '</form>
			</body>
		</html>';

		echo $formHtml;
		exit;
	}

	/**
	 * 
	 * @param type $postData
	 * @return mixed json|xml
	 */
	public function backEndRequest($postData)
	{
		$ch = curl_init();
		if ( DEV_MODE ) {
			curl_setopt( $ch, CURLOPT_URL, IPG_TEST_URL );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		}
		else {
			curl_setopt( $ch, CURLOPT_URL, IPG_URL );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
		}
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $ch );

		return $result;
	}

}
