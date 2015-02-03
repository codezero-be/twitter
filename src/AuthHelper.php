<?php namespace CodeZero\Twitter; 

class AuthHelper {

    /**
     * Generate app credentials
     *
     * @param string $apiKey
     * @param string $apiSecret
     *
     * @return string
     */
    public function generateAppCredentials($apiKey, $apiSecret)
    {
        $apiKey = urlencode($apiKey);
        $apiSecret = urlencode($apiSecret);
        $credentials = base64_encode("{$apiKey}:{$apiSecret}");

        return $credentials;
    }

}