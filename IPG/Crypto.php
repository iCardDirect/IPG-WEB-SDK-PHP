<?php

namespace IPG;

class Crypto
{
    /**
     * @param Config $config
     * @param string $dataToEncrypt
     * @return string
     * @throws IPG_Exception
     */
    public static function encrypt(Config $config, string $dataToEncrypt): string
    {
        $pubKeyId = openssl_get_publickey($config->getAPIPublicKey());
        if (!$pubKeyId) {
            throw new IPG_Exception('Public key not found');
        }

        openssl_public_encrypt( $dataToEncrypt, $encrypted, $pubKeyId );
        return base64_encode($encrypted);
    }

    /**
     * @param Config $config
     * @param string $dataToDecrypt
     * @return string
     * @throws IPG_Exception
     */
    public static function decrypt(Config $config, string $dataToDecrypt): string
    {
        $privateKeyId = openssl_get_privatekey($config->getPrivateKey());
        if (!$privateKeyId) {
            throw new IPG_Exception('Private key not found');
        }

        openssl_private_decrypt( base64_decode( $dataToDecrypt ), $decrypted, $privateKeyId );

        return $decrypted;
    }

    /**
     * @param Config $config
     * @param array $dataToSign
     * @return string
     * @throws IPG_Exception
     */
    public static function generateSignature(Config $config, array $dataToSign): string
    {
        $privateKeyId = openssl_get_privatekey( $config->getPrivateKey() );
        if (!$privateKeyId) {
            throw new IPG_Exception('Private key not found');
        }

        if ($config->getVersion() < Defines::NEW_SIGNATURE_VERSION) {
            openssl_sign(sha1(self::buildSignatureData($config, $dataToSign)), $signature, $privateKeyId);
        } else {
            openssl_sign(self::buildSignatureData($config, $dataToSign), $signature, $privateKeyId, OPENSSL_ALGO_SHA256);
        }

        return base64_encode($signature);
    }

    /**
     * @param Config $config
     * @param array $dataToValidate
     * @param string $signature
     * @return bool
     * @throws IPG_Exception
     */
    public static function validateSignature(Config $config, array $dataToValidate, string $signature): bool
    {
        $publicKeyId = openssl_get_publickey($config->getAPIPublicKey());
        if (!$publicKeyId) {
            throw new IPG_Exception('Public key not found');
        }

        $dataToValidate = self::buildSignatureData($config, $dataToValidate);

        $signature = base64_decode($signature);

        $res = openssl_verify($dataToValidate, $signature, $publicKeyId);

        if($res != 1){
            $res = openssl_verify(sha1($dataToValidate), $signature, $publicKeyId);
        }

        if($res != 1){
            $res = openssl_verify($dataToValidate, $signature, $publicKeyId, OPENSSL_ALGO_SHA256);
        }

        return $res == 1;
    }

    /**
     * @param Config $config
     * @param array $dataToSign
     * @return string
     */
    public static function buildSignatureData(Config $config, array $dataToSign): string
    {
        if ($config->getVersion() < Defines::NEW_SIGNATURE_VERSION) {
            // Concatenate all values from post and url encode them
            return urlencode( stripslashes( implode( '', $dataToSign ) ) );
        }

        if ($config->getVersion() < 4.5) {
            // Concatenate all values from post and create base64 encode them
            $signParams = array_map([Helper::class, 'unescape'], $dataToSign);
            return base64_encode( implode( '', $signParams ) );
        }

        $data = Helper::flattenNested('', $dataToSign, ':');

        //remove empty arrays
        $data = array_filter($data, function ($value) {
            if (is_array($value) && empty($value)) {
                return false;
            }
            return true;
        });

        $data = array_change_key_case($data);

        $data = array_map(function ($key, $value) {
            if (is_bool($value)) {
                $value = intval($value);
            }

            if (is_string($value)) {
                $value = mb_convert_encoding($value, 'UTF8');
            }

            if ($value === null || $value === '') {
                return $key . ':';
            }

            return $key . ':' . $value;
        }, array_keys($data), array_values($data));

        sort($data, SORT_NATURAL);

        return implode(';', $data);
    }
}