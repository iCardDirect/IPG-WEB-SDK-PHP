<?php

require_once '../ipg.php';

$postData = $_POST;

$path = realpath( './front_response_logs' );

$signedData = $postData['Signature'];
unset( $postData['Signature'] );
$concData = urlencode( stripslashes( implode( '', $postData ) ) );
$pubKeyId = openssl_get_publickey( PUBLIC_KEY );
$signedData = base64_decode( $signedData );
$res = openssl_verify( sha1( $concData ), $signedData, PUBLIC_KEY );
openssl_free_key( $pubKeyId );



if ( $res == 1 ) {
	// success
	$file = $path . '/' . time() . '.txt';

	$fh = fopen( $file, 'w' ) or die( "can't open file" );

	if ( $fh ) {
		$content = json_encode( $postData, true );
		fwrite( $fh, $content );
		fclose( $fh );
		echo 'OK';
	}
}
else {
	//not success
	echo 'NOT OK';
}
exit;
