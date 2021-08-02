<?php

Class Auth
{
    public function __construct()
    {
        $tokenVal = md5(1234);

        if($tokenVal === $this->getBearerToken())
        {
            return TRUE;
        }
        else
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

    public function getBearerToken()
    {
        $header     = apache_request_headers();
        $authHeader = '';
        if (isset($header['Authorization']))
        {
            $authHeader = trim($header["Authorization"]);
        }

        // HEADER: Get the access token from the header
        if (!empty($authHeader))
        {
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches))
            {
                return $matches[1];
            }
        }
        return null;
    }
}