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
-----BEGIN PRIVATE KEY-----
MIICXgIBAAKBgQC+NIHevraPmAvx5//z38qjcqlCeyiLwXI5CRNZoL+Ms+/itElM
ITVpaILCBF5+Uwp+A0pPYy/Gn9S+1gz/LL/mBDbWpTuMhHvEgJilX6CsVIah9/c/
.............................
oZBWvHeIpnnSTMkbvkNDAkEAvYoCwTJWAGYUDSSLSN+nP1nmrbyJVSSJMNNQ5974
bBzRvEz9OIgvFL2LslY3kBdwE5JIFacyvDXBVUVqv7MdlQ==
-----END PRIVATE KEY-----
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
$config->setLanguage( 'en' ); //Not required for methods Reversal, Refund and GetTxnStatus
$config->setMerchantId( 33 ); //Originator
$config->setMerchantName( 'IPG Example' );
$config->setVersion( '4.5' );
$config->setVirtualTerminalId( '112' ); //MID
$config->setAPIPublicKey( $pubKey );
$config->setPrivateKey( $privKey );
```

Keys can be set by path, using path methods.

### Build Cart Object /removed from version 4.5/

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
$purchase->setBannerIndex(8);
$purchase->setCart( $cart ); //required for a version <= 4.4
$purchase->setCurrency( 978 );
$purchase->setAmount( 10.50 ); //required for a version >= 4.5
$purchase->setCustomer( $customer );
$purchase->setBillingAddress( $billingAddress );
$purchase->setOrderId( time() );
$purchase->setUrlOK( 'http://yoursite.com/urlOK' );
$purchase->setUrlNotify( 'http://yoursite.com/urlNotify' );
$purchase->setUrlCancel( 'http://yoursite.com/urlCancel' );
try {
	$purchase->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process 3DSPurchaseWithStoredCard

```php
$purchase = new IPG\ThreeDSPurchaseWithStoredCard( $config );
$purchase->setBannerIndex(8);
$purchase->setCurrency( 978 );
$purchase->setAmount( 10.50 );
$purchase->setCustomer( $customer );
$purchase->setBillingAddress( $billingAddress );
$purchase->setOrderId( time() );
$purchase->setUrlOK( 'http://yoursite.com/urlOK' );
$purchase->setUrlNotify( 'http://yoursite.com/urlNotify' );
$purchase->setUrlCancel( 'http://yoursite.com/urlCancel' );
$purchase->setCardToken('40B1B011C4A21E ... 36E452619059');
$purchase->setIsVerifyCVC(true);
try {
	$purchase->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process PaymentToken

```php
$paymentToken = new IPG\PaymentToken( $purchase );
$paymentToken->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
try {
	$paymentToken->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process EmbeddedPayment

```php
$embeddedPayment = new IPG\EmbeddedPayment( $purchase );
$embeddedPayment->setTheme('ThemeName');
$embeddedPayment->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
try {
	$embeddedPayment->process();
} catch ( \IPG\IPG_Exception $exc ) {
	echo $exc->getMessage();
}
```

### Process Reversal

```php
$reversal = new IPG\Reversal( $config );
$reversal->setOrderId( time() )
$reversal->setTrnRef('20250925084241282100');
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
$refund->setTrnRef('20250925084241282100');
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