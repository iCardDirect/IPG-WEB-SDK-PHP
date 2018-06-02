<?php

require_once '../ipg.php';

$orderId = time();
// All requests
$IPGPurchase = array(
	'Amount' => 2.00,
	'Currency' => '978',
	'CustomerIP' => '82.119.81.30',
	'OrderID' => $orderId,
	'BannerIndex' => '1',
	'CartItems' => 1,
	'Email' => 'test@testemail.com',
	'Article_1' => 'Печка',
	'Quantity_1' => '2',
	'Price_1' => '1',
	'Currency_1' => '978',
	'Amount_1' => 2.00
);

$IPGFirstRecurring = array(
	'Amount' => 2.00,
	'Currency' => '978',
	'CustomerIP' => '82.119.81.30',
	'OrderID' => $orderId,
	'BannerIndex' => '1',
	'URL_OK' => URL_OK,
	'URL_Cancel' => URL_Cancel,
	'URL_Notify' => URL_Notify,
	'Email' => 'test@testemail.com',
);


$IPGMoto = array(
	'Amount' => 2.00,
	'Currency' => '978',
	'CustomerIP' => '82.119.81.30',
	'OrderID' => $orderId,
	'BannerIndex' => '1',
	'URL_OK' => URL_OK,
	'URL_Cancel' => URL_Cancel,
	'URL_Notify' => URL_Notify,
);

$IPGGetTxnStatus = array(
	'OrderID' => '1525847382',
	'OutputFormat' => 'json'
);

$IPGReversal = array(
	'IPG_Trnref' => '20180509062952016726',
	'OutputFormat' => 'json'
);

$IPGRefund = array(
	'MID' => MID,
	'IPG_Trnref' => '20180509062952016726',
	'Amount' => 1,
	'Currency' => '978',
	'OutputFormat' => 'json'
);


$ipg = new IPG();
// if front request response is null because form is built and submitted
//$response = $ipg->IPGReversal($IPGReversal);
$response = $ipg->IPGStoreCard($IPGPurchase);

echo $response;

