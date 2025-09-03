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
        $response = $pr->process();
        fwrite(STDOUT, print_r((array)$response) . "\n");
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
        $response = $fr->process();
        fwrite(STDOUT, print_r((array)$response) . "\n");
        $this->assertTrue(true);
    }

    public function testTxnStatus()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.2' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $txnStatus = new \IPG\TxnStatus($config);
        $txnStatus
            ->setOrderId('62826592-0F57-4E77-9C46-14140B3B0BD9')
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
        $config->setLanguage( 'en' );
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
            ->setTrnRef('20240312133000327200')
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
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.2' );
        $config->setVirtualTerminalId( '112' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $customer = new \IPG\Customer();
        $customer->setEmail( 'customer@email.com' );

        $refund = new \IPG\Refund($config);
        $refund
            ->setCustomer($customer)
            ->setOrderId('IPG_SDK_' . time())
            ->setAmount(11.01)
            ->setCurrency(978)
            ->setTrnRef('20240312133000327200')
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
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setVersion( '4.2' );
        $config->setAPIPublicKey( file_get_contents(__DIR__ . '/data/pub_key') );
        $config->setPrivateKey( file_get_contents(__DIR__ . '/data/priv_key') );

        $reversal = new \IPG\Reversal($config);
        $reversal
            ->setTrnRef('20240312133000327200')
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
        $config->setVirtualTerminalId( '113' );
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

        $cart = new \IPG\Cart();
        $cart->add( 'Item', 1, 1.20 );
        $cart->add( 'Another Item', 2, 2.00 );
        $cart->add( 'Third item', 3, 3.00 );

        $purchase = new \IPG\Purchase($config);
        $purchase
            ->setCart($cart)
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(975)
//            ->setAmount(3)
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

    public function testEmbeddedPayment()
    {
        $config = new \IPG\Config();
        $config->setIPGUrl( 'https://dev-ipg.icards.eu/sandbox/' );
        $config->setKeyIndex( 1 );
        $config->setKeyIndexResp( 1 );
        $config->setLanguage( 'en' );
        $config->setMerchantId( 33 );
        $config->setMerchantName( 'PHP SDK Test modal with purchase' );;
        $config->setVersion( '4.3' );
        $config->setVirtualTerminalId( '113' );
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

        $cart = new \IPG\Cart();
        $cart->add( 'Item', 1, 1.20 );
        $cart->add( 'Another Item', 2, 2.00 );
        $cart->add( 'Third item', 3, 3.00 );

        $purchase = new \IPG\Purchase($config);
        $purchase
            ->setCart($cart)
            ->setBannerIndex(1)
            ->setOrderId('IPG_SDK_' . time())
            ->setCurrency(975)
//            ->setAmount(3)
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
}