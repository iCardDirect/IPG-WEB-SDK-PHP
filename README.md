# iCard.Direct SDK PHP


1.Configure config.php. Most of parameters should be set for testing environment.
1.1.DEV_MODE
For testing (integration) must be set to 1. For production 0.

1.2.IPG_TEST_URL - [https://devs.icards.eu/ipgtest/](https://devs.icards.eu/ipgtest/)
Link where request go to for development mode.

1.3.IPG_URL - [https://ipg.icard.com/](https://ipg.icard.com/)
Link for production

1.4.IPGVersion - by default it is 3.2 or latest

1.5.PUBLIC_KEY & PRIVATE_KEY
For testing environment you can take them from [https://devs.icards.eu/ipgtest/test_keys/](https://devs.icards.eu/ipgtest/test_keys/)
For production env must be given from iCard AD

1.6.ORIGINATOR
For testing use value "33" or check our lastest documentation
For production - unique identifier for merchant company that has signed a contract with iCard AD.
To use IPG PHP SDK, you need:
1.6.1.Have accessible NOTIFY, OK and Cancel URLs - There are 3 files in demo/ folder. Modify them with you business logic or just make another files with custom logic.
1.6.2.Configure config.php
1.6.3.To see how to use it, check ipg/demo/ipgtest.php

1.7.MID
For testing use value "112" or check our lastest documentation.
For production - Identifier of the virtual terminal used for the purchase.

1.8.MID_NAME
Merchant name, shown on payment page

1.9.KEY_INDEX - by default 1. Identifier of the private key used for signature (if more then 1)

1.10.LANGUAGE - by default EN. ISO 2-character code for the desired language on the payment page. 
Currently supporting EN, FR, DE, BG, ES, RO. 

1.11.URL_OK - The page where the cardholder should be redirected on successful payment.
There is a file in demo folder called ipgtestOK.php.

1.12.URL_Notify - Address supplied by the partner, where the IPGPurchaseNotify API call will 
send the parameters for the successful payment. 
IMPORTANT: After handling response from IPG, You have to return string "OK", otherwise payment will be unsuccessful 

1.13.URL_Cancel - The page where the cardholder should be redirected when "Cancel" is pressed on the payment page.

2.IPG Request Methods.

There are two kind of methods. Default params are set automatically. 

Default params are set automatically:
 - Originator
 - IPGMethod 
 - KeyIndex
 - Language
 - IPGVersion
 - Signature

2.1.Back-End Requests - IPGGetTxnStatus, IPGReversal, IPGRefund, IPGSubsequentRecurring.

2.2.Front-End Request - IPGFirstRecurring, IPGMoto, IPGPurchase.
For those methods MID, MIDName, URL_OK, URL_Cancel, URL_Notify are also set by default.

3.Demo

3.1.URL_OK, URL_Cancel and URL_Notify
After configuring config.php (be sure to have accessible URL_OK, URL_Cancel and URL_Notify links by http), you can use ipgtestOK.php, ipgtestCancel.php, ipgtestNotify.php files to see when and where you get request to them. 

IMPORTANT: Those files are only examples. Be sure to use well implemented logic of your project. Don't forget
important note from 1.12. 

3.2.ipgtest.php
Here you can find example of different requests to IPG. Make sure to set all required parameters.

Example:

```php
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
	'Email' => 'setEmail@mail.com',
	'Article_1' => 'Test product',
	'Quantity_1' => '2',
	'Price_1' => '1',
	'Currency_1' => '978',
	'Amount_1' => 2.00
);
$ipg = new IPG();
// if front request response is null because form is built and submitted
$response = $ipg->IPGPurchase($IPGPurchase);
?>
```