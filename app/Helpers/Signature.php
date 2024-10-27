<?php


namespace App\Helpers;


class Signature
{
    const MD5_SIGNATURE_TYPE = 'md5';
    public static function encryptMD5($data, $join= '&')
    {
        if (is_array($data)) {
            return md5(implode($join, $data));
        }
        return md5($data);
    }

    public static function encryptHash($data, $join= '&', $hashType = 'sha512')
    {
        if (is_array($data)) {
            return hash($hashType, implode($join, $data));
        }
        return hash($hashType, $data);
    }

}
