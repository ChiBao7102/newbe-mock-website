<?php


namespace App\Services;


use phpseclib\Crypt\RSA;

class RSAService
{

    /**
     * The base uri to consume authors service
     * @var string
     */
    protected $rsa;

    /**
     * Authorization secret to pass to book api
     * @var string
     */
    public $secret;

    public function __construct()
    {
        $this->rsa = new RSA();
    }

    public function generateRSAKeys()
    {
        return $this->rsa->createKey(4096);
    }

    public function generateSignature($data, $privateKey, $join = '|')
    {
        if (is_array($data)) {
            $joinData = implode($join, $data);
        } else {
            $joinData = $data;
        }
        $privateKeyId = openssl_get_privatekey($privateKey);
        openssl_sign($joinData, $signature, $privateKeyId, 'sha1WithRSAEncryption');
        return $this->strToHex($signature);
    }

    public function verifySignature($data, $signature, $publicKey, $join = '|')
    {
        $signature = self::hexToStr($signature);
        $publicKeyId = openssl_get_publickey($publicKey);
        if (is_array($data)) {
            $joinData = implode($join, $data);
        } else {
            $joinData = $data;
        }
        $result = openssl_verify($joinData, $signature, $publicKeyId, 'sha1WithRSAEncryption');
        return $result;
    }

    public function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return strToUpper($hex);
    }

    public static function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

}
