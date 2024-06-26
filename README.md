# IPG SDK PHP

## Installing

```
composer require icarddirect/ipg-sdk-php
```

## Example of purchase

### Require library:

```php
require __DIR__ . '/vendor/autoload.php';
```

### Set up configuration data:

```php
$privKey = <<<EOT
-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQC+NIHevraPmAvx5//z38qjcqlCeyiLwXI5CRNZoL+Ms+/itElM
ITVpaILCBF5+Uwp+A0pPYy/Gn9S+1gz/LL/mBDbWpTuMhHvEgJilX6CsVIah9/c/
Bn8U3gT724aBhyIJeKVLO54pILKlkrKId4w76KDaouaFxyCECBMLaXQZoQIDAQAB
AoGBAI0zVaYSVlzLNzLiU/Srkjc8i8K6wyLc/Pqybhb/arP9cHwP8sn9bTVPTKLT
s4J8CzH5J1VAANunE7yIEyXsBphnr4lfC0ZPVHavPPBfFR/v9QVI1HByhnjihmG9
uPZBuUAm/+s20rPOERepEMBmjpHnA7vTefMbtBXhRKbwszYxAkEA3Nl6ZmAIe50y
yyK3IyCDYitqqQIpMDDTBs8Pn3L+Cen7+a5UXt2+mP87uJSid7m6qK6tQrdKBXgI
TCMf9DZmBwJBANx6a9liZtQBM+GD0vAMZ3kTcBBKQe/c63pPpDBRSbiIgdhKJzcD
lfJoGL6wl2QI2NHhXc9eaH6gVGOsBQYD2RcCQQCVYp4Cpa7XPqve7+qE3jdArjGF
hKqrqDr1/hWJO1VPC3CfoSX8zW1hPDP/VLrY1U7HTvBvkl+Fd33VUmUI4cr9AkAR
PBSgKpwFKI7oqwhbMW0JPua8r0FWQbu6lO0txbzwiuMziCBmoYYgK9j7VwyOik6A
oZBWvHeIpnnSTMkbvkNDAkEAvYoCwTJWAGYUDSSLSN+nP1nmrbyJVSSJMNNQ5974
bBzRvEz9OIgvFL2LslY3kBdwE5JIFacyvDXBVUVqv7MdlQ==
-----END RSA PRIVATE KEY-----
EOT;

$pubKey = <<<EOT
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC4ur+fZBqNjnm1XJSJrzf8vyIv
xfXew44RKJv9kpPiSEtGaRiAmqZhMWsW/fD2Drnh1A6gCgfWIv/3Zgr18GZ/Heqm
h5n9HmQndHAB2nZnFLOioL9v6awAbqVeqYBMzp97UkruxXDtqejL7w8WkxearqpU
BBbcPHA2gMp0hRN/MwIDAQAB
-----END PUBLIC KEY-----
EOT;

$config = new \IPG\Config();
$config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
$config->setKeyIndex( 1 );
$config->setKeyIndexResp( 1 );
$config->setLanguage( 'en' );
$config->setMerchantId( 33 );
$config->setMerchantName( 'IPG Example' );
$config->setVersion( '4.2' );
$config->setVirtualTerminalId( '112' );
$config->setAPIPublicKey( $pubKey );
$config->setPrivateKey( $privKey );
```

Keys can be set by path, using path methods.

### Build Cart Object

```php
$cart = new \IPG\Cart();
$cart->add( 'Item', 1, 1.20 );
$cart->add( 'Another Item', 2, 2.00 );
$cart->add( 'Third item', 3, 3.00 );
```

### Build Customer Object

```php
$customer = new \IPG\Customer();
$customer->setEmail( 'customer@email.com' );
$customer->setIdentifier('Customer_Identifier');
$customer->setMobileNumber('+359888123456');
$customer->setIP( '127.0.0.1' );
```

### Build Billing address Object

```php
$billingAddress = new \IPG\BillingAddress();
$billingAddress->setBillAddrCountry( 100 );
$billingAddress->setBillAddrState('State if applicable');
$billingAddress->setBillAddrPostCode('PostCode');
$billingAddress->setBillAddrCity( 'City' );
$billingAddress->setBillAddrLine1( 'Line1' );
$billingAddress->setBillAddrLine2( 'Line2' );
$billingAddress->setBillAddrLine3( 'Line3' );
```

### Process Purchase

```php
$purchase = new IPG\Purchase( $config );
$purchase->setCart( $cart );
$purchase->setCurrency( '978' );
$purchase->setCustomer( $customer );
$purchase->setBillingAddress( $billingAddress );
$purchase->setOrderId( time() );
$purchase->setUrlOK( 'http://yoursite.com//urlOK' );
$purchase->setUrlNotify( 'http://yoursite.com//urlNotify' );
$purchase->setUrlCancel( 'http://yoursite.com//urlCancel' );
try {
	$purchase->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process Reversal

```php
$reversal = new IPG\Reversal( $config );
$reversal->setTrnRef('20240312133000327200');
$reversal->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
try {
	$response = $reversal->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process Refund

```php
$refund = new IPG\Refund( $config );
$refund->setCustomer( $customer );
$refund->setOrderId( time() );
$refund->setAmount(1.00);
$refund->setCurrency(978);
$refund->setTrnRef('20240312133000327200');
$refund->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
try {
	$response = $refund->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process GetTxnStatus

```php
$txnStatus = new IPG\TxnStatus( $config );
$txnStatus->setOrderId('OrderID of previous transaction');
$txnStatus->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
try {
	$response = $txnStatus->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```