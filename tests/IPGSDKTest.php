<?php

class IPGSDKTest extends \PHPUnit\Framework\TestCase
{
    public function testPurchaseResponse()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'IPG Example' );
        $config->setVersion( '4.2' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKeyPath( __DIR__ . '/data/pub_key' );
        $config->setPrivateKeyPath( __DIR__ . '/data/priv_key' );

        $cart = new \IPG\Cart();
        $cart->add( 'Item', 1, 1.20 );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        fwrite(STDOUT, __METHOD__ . "\n");
        $pr = new \IPG\Purchase($config);
        $pr
            ->setBannerIndex(1)
            ->setCart($cart)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlOK('https://dev-ipg.icards.eu/sandbox/client/ipgOk')
            ->setUrlCancel('https://dev-ipg.icards.eu/sandbox/client/ipgCancel')
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify');

        $redirectForm = $pr->process();
        fwrite(STDOUT, print_r((array)$redirectForm) . "\n");

        /*$postData = $pr->getPOSTParams();
        fwrite(STDOUT, print_r($postData) . "\n");
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            $config->getIPGUrl(),
            [
                'form_params' => $postData,
                'verify' => false,
            ]
        );
        fwrite(STDOUT, print_r((array)$response->getBody()->getContents()) . "\n");*/
        $this->assertTrue(true);
    }

    public function testPurchaseResponseWithoutCartItems()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'IPG Example' );
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKeyPath( __DIR__ . '/data/pub_key' );
        $config->setPrivateKeyPath( __DIR__ . '/data/priv_key' );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        fwrite(STDOUT, __METHOD__ . "\n");
        $pr = new \IPG\Purchase($config);
        $pr
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setAmount(1.4)
            ->setCurrency(978)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlOK('https://dev-ipg.icards.eu/sandbox/client/ipgOk')
            ->setUrlCancel('https://dev-ipg.icards.eu/sandbox/client/ipgCancel')
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify');

        $redirectForm = $pr->process();
        fwrite(STDOUT, print_r((array)$redirectForm) . "\n");

        /*$postData = $pr->getPOSTParams();
        fwrite(STDOUT, print_r($postData) . "\n");
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            $config->getIPGUrl(),
            [
                'form_params' => $postData,
                'verify' => false,
            ]
        );
        fwrite(STDOUT, print_r((array)$response->getBody()->getContents()) . "\n");*/

        $this->assertTrue(true);
    }

    public function test3DSPurchaseResponse()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'IPG Example' );
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( 112 );
        $config->setAPIPublicKeyPath( __DIR__ . '/data/pub_key' );
        $config->setPrivateKeyPath( __DIR__ . '/data/priv_key' );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        fwrite(STDOUT, __METHOD__ . "\n");

        $purchase = new \IPG\ThreeDSPurchaseWithStoredCard($config);
        $purchase
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setAmount(3)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlOK('https://dev-ipg.icards.eu/sandbox/client/ipgOk')
            ->setUrlCancel('https://dev-ipg.icards.eu/sandbox/client/ipgCancel')
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify')
            ->setNote('Test PHP SDK')
            ->setCardToken('40B1B011C4A21EA65A8AA06E9D767ECE348ADB2E2D4E4E6C3A0536E452619059')
            ->setIsVerifyCVC(true);

        $redirectForm = $purchase->process();
        fwrite(STDOUT, print_r((array)$redirectForm) . "\n");

        /*$postData = $purchase->getPOSTParams();
        fwrite(STDOUT, print_r($postData) . "\n");
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            $config->getIPGUrl(),
            [
                'form_params' => $postData,
                'verify' => false,
            ]
        );
        fwrite(STDOUT, print_r((array)$response->getBody()->getContents()) . "\n");*/

        $this->assertTrue(true);
    }

    public function testFirstRecurringResponse()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'IPG Example' );
        $config->setVersion( '4.2' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        fwrite(STDOUT, __METHOD__ . "\n");

        $fr = new \IPG\FirstRecurring($config);
        $fr
            ->setOrderId('IPG_SDK_' . time())
            ->setAmount(1)
            ->setCurrency(978)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlOK('https://dev-ipg.icards.eu/sandbox/client/ipgOk')
            ->setUrlCancel('https://dev-ipg.icards.eu/sandbox/client/ipgCancel')
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify');

        $redirectForm = $fr->process();
        fwrite(STDOUT, print_r((array)$redirectForm) . "\n");

        /*$postData = $fr->getPOSTParams();
        fwrite(STDOUT, print_r($postData) . "\n");
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            $config->getIPGUrl(),
            [
                'form_params' => $postData,
                'verify' => false,
            ]
        );
        fwrite(STDOUT, print_r((array)$response->getBody()->getContents()) . "\n");*/

        $this->assertTrue(true);
    }

    public function testTxnStatus()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $txnStatus = new \IPG\TxnStatus($config);
        $txnStatus
            ->setOrderId('D04CC011-7BF0-4237-9FA7-F0AC91BE4EF7')
            ->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $txnStatus->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testSubsequentRecurring()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.2' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );
        $customer->setIdentifier('Customer_Identifier');

        $sr = new \IPG\SubsequentRecurring($config);
        $sr
            ->setCustomer($customer)
            ->setAmount(10.50)
            ->setCurrency(978)
            ->setOrderId('IPG_SDK_' . time())
            ->setTrnRef('20260528075556135992')
            ->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $sr->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testRefund()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );

        $refund = new \IPG\Refund($config);
        $refund
            ->setCustomer($customer)
            ->setOrderId('IPG_SDK_' . time())
            ->setAmount(11.00)
            ->setCurrency(978)
            ->setTrnRef('20260528075556135992')
            ->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $refund->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testReversal()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $reversal = new \IPG\Reversal($config);
        $reversal
            ->setOrderId('IPG_SDK_' . time())
            ->setTrnRef('20260528075556135992')
            ->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $reversal->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testModalPurchase()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'PHP SDK Test modal with purchase' );;
        $config->setVersion( '4.3' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'php.sdk.test@test.com' );
        $customer->setIdentifier('Customer_Identifier');
//        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        $cart = new \IPG\Cart();
        $cart->add( 'First Item', 1, 1.20 );
        $cart->add( 'Second Item', 2, 2.00 );
        $cart->add( 'Third item', 3, 3.00 );

        $purchase = new \IPG\Purchase($config);
        $purchase
            ->setCart($cart)
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify')
            ->setNote('Test PHP SDK');

        $pt = new \IPG\PaymentToken($purchase);
        $pt->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $pt->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testModalPurchaseWithoutCart()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'PHP SDK Test modal with purchase' );;
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'php.sdk.test@test.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        $purchase = new \IPG\Purchase($config);
        $purchase
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setAmount(3)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify')
            ->setNote('Test PHP SDK');

        $pt = new \IPG\PaymentToken($purchase);
        $pt->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $pt->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testModal3DSPurchase()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'PHP SDK Test modal with 3DS purchase' );;
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'php.sdk.test@test.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        $purchase = new \IPG\ThreeDSPurchaseWithStoredCard($config);
        $purchase
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setAmount(3)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify')
            ->setNote('Test PHP SDK')
            ->setCardToken('40B1B011C4A21EA65A8AA06E9D767ECE348ADB2E2D4E4E6C3A0536E452619059')
            ->setIsVerifyCVC(true);

        $pt = new \IPG\PaymentToken($purchase);
        $pt->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $pt->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testEmbeddedPurchase()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'PHP SDK Test modal with purchase' );;
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'php.sdk.test@test.com' );
        $customer->setIdentifier('Customer_Identifier');
//        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        $purchase = new \IPG\Purchase($config);
        $purchase
            ->setAmount(11.00)
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify')
            ->setNote('Test PHP SDK');

        $pt = new \IPG\EmbeddedPayment($purchase);
        $pt->setTheme('theme2');
        $pt->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $pt->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }

    public function testEmbedded3DSPurchase()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'PHP SDK Test modal with purchase' );;
        $config->setVersion( '4.5' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'php.sdk.test@test.com' );
        $customer->setIdentifier('Customer_Identifier');
        $customer->setMobileNumber('+359888123456');
        $customer->setIP( '127.0.0.1' );

        $billingAddress = new \IPG\BillingAddress();
        $billingAddress->setBillAddrCountry( 100 );
        $billingAddress->setBillAddrPostCode('9000');
        $billingAddress->setBillAddrCity( 'Varna' );
        $billingAddress->setBillAddrLine1( 'BPV B1' );

        $purchase = new \IPG\ThreeDSPurchaseWithStoredCard($config);
        $purchase
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(978)
            ->setAmount(3)
            ->setCustomer($customer)
            ->setBillingAddress($billingAddress)
            ->setUrlNotify('https://dev-ipg.icards.eu/sandbox/client/ipgNotify')
            ->setNote('Test PHP SDK')
            ->setCardToken('40B1B011C4A21EA65A8AA06E9D767ECE348ADB2E2D4E4E6C3A0536E452619059')
            ->setIsVerifyCVC(true);

        $pt = new \IPG\EmbeddedPayment($purchase);
        $pt->setTheme('theme2');
        $pt->setOutputFormat(\IPG\Defines::OUTPUT_FORMAT_JSON);
        $response = $pt->process();
        fwrite(STDOUT, print_r($response->getData()) . "\n");
        $this->assertTrue(true);
    }
}