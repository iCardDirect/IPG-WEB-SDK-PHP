<?php

namespace IPG;

class Defines
{

	const OUTPUT_FORMAT_XML = 'xml';
	const OUTPUT_FORMAT_JSON = 'json';
	const OUTPUT_FORMAT_POST = 'post';
	
	const STATUS_SUCCESS = 0;
	const STATUS_MISSING_REQ_PARAMS = 1;
	const STATUS_SIGNATURE_FAILED = 2;
	const STATUS_IPG_ERROR = 3;
	const STATUS_INVALID_VIRTUAL_TERMINAL_ID = 4;
	const STATUS_INVALID_PARAMS = 5;
	const STATUS_REFERER = 6;
	const STATUS_PAYMENT_TRIES = 7;
	const STATUS_TRANSACTION_AUTH_FAIL = 8;
	const STATUS_TRN_NOT_FOUND = 9;
	const STATUS_UNDEFINED_ERROR = 99;

	public static function getMessageByStatus($status)
	{
		$statusMessages = array(
			self::STATUS_SUCCESS => 'Success',
			self::STATUS_MISSING_REQ_PARAMS => 'Missing required fields',
			self::STATUS_SIGNATURE_FAILED => 'Signature failed',
			self::STATUS_IPG_ERROR => 'IPG Error occurred',
			self::STATUS_INVALID_VIRTUAL_TERMINAL_ID => 'Given virtual terminal ID is invalid',
			self::STATUS_INVALID_PARAMS => 'Invalid params',
			self::STATUS_REFERER => 'Invalid referer',
			self::STATUS_PAYMENT_TRIES => 'Exceeded payment tries',
			self::STATUS_TRANSACTION_AUTH_FAIL => 'Transaction authentication failed',
			self::STATUS_TRN_NOT_FOUND => 'Transaction not found!',
			self::STATUS_UNDEFINED_ERROR => 'UNDEFINED ERROR!!!'
		);

		$message = (isset( $statusMessages[$status] )) ? $statusMessages[$status] : 'Status not found';
		return $message;
	}

}
