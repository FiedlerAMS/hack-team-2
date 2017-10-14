<?php

namespace App\Http;

use Unirest\Request;

class Client
{
    public function __construct($verifyPeer = true)
    {
        Request::verifyPeer($verifyPeer);
    }

    public function authenticate($username, $password)
    {
        Request::auth($username, $password);
    }

    public function get($endpoint, $parameters = null, $headers = [])
    {
        return Request::get(
            $endpoint,
            $headers,
            $parameters
        );
    }

    public function post($endpoint, $body = null, $headers = [])
    {
        return Request::post(
            $endpoint,
            $headers,
            $body
        );
    }
}
