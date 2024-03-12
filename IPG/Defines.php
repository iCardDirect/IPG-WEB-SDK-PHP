<?php

namespace IPG;

class Defines
{
    const NEW_SIGNATURE_VERSION = 4.1;
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
	const STATUS_PENDING_TRANSACTION = 10;
	const STATUS_INVALID_AMOUNT = 11;
	const STATUS_DECLINED_TRANSACTION = 12;
	const STATUS_EXPIRED_TRANSACTION = 13;
	const STATUS_INVALID_CARD = 14;
	const STATUS_FORBIDDEN_CARD_SCHEME = 15;
	const STATUS_CARD_NOT_FOUND = 16;
	const STATUS_CARD_SCHEME_TIMEOUT = 17;
	const STATUS_TOKEN_PROVIDER_GET_SESSION_ERROR = 18;
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
			self::STATUS_PENDING_TRANSACTION => 'Transaction is still pending!',
			self::STATUS_INVALID_AMOUNT => 'Invalid amount value!',
			self::STATUS_DECLINED_TRANSACTION => 'Transaction is declined!',
			self::STATUS_EXPIRED_TRANSACTION => 'Payment process is not completed in 15 min!',
			self::STATUS_INVALID_CARD => 'Card is not valid!',
			self::STATUS_FORBIDDEN_CARD_SCHEME => 'Card scheme is not allowed for merchant!',
			self::STATUS_CARD_NOT_FOUND => 'Card is not found in CardStorage!',
			self::STATUS_CARD_SCHEME_TIMEOUT => 'Request to card scheme is timeouted!',
			self::STATUS_TOKEN_PROVIDER_GET_SESSION_ERROR => 'Error in get session request for purchase with tokenized card!',
			self::STATUS_UNDEFINED_ERROR => 'UNDEFINED ERROR!!!'
		);

        return (isset( $statusMessages[$status] )) ? $statusMessages[$status] : 'Status not found';
	}

}
