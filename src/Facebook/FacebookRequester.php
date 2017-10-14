<?php

namespace Hack\Facebook;

use Unirest\Request;

class FacebookRequester
{
    private $facebookServiceUrl;

    public function __construct(){
        $this->facebookServiceUrl = 'http://localhost:3000';
    }

    private function fixPath($path)
    {
        return '/' . ltrim($path, '/');
    }
    
    public function get($path)
    {
        $path = $this->fixPath($path);
        return Request::get($this->facebookServiceUrl . $path);
    }
}
