<?php
class CryptoHelper
{
    private $cipher = 'aes-256-cbc';
    private $key;
    private $iv;

    public function __construct($key)
    {
        $this->key = hash('sha256', $key, true);
        $this->iv = openssl_random_pseudo_bytes(16);
    }

    public function encrypt($data)
    {
        $encryptedData = openssl_encrypt($data, $this->cipher, $this->key, 0, $this->iv);
        return base64_encode($this->iv . $encryptedData);
    }

    public function decrypt($encryptedData)
    {
        $encryptedData = base64_decode($encryptedData);
        $iv = substr($encryptedData, 0, 16);
        $data = substr($encryptedData, 16);
        return openssl_decrypt($data, $this->cipher, $this->key, 0, $iv);
    }
}
?>