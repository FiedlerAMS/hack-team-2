<?php

namespace Hack\Api;

use Unirest\Request;

class FiedlerRequester
{
    private $fiedlerUrl;

    public function __construct(){
        $this->fiedlerUrl = 'https://hack.fiedler.company/api/v1';
    }

    private function fixPath($path)
    {
        return '/' . ltrim($path, '/');
    }
    
    public function get($path)
    {
        $path = $this->fixPath($path);
        return Request::get($this->fiedlerUrl . $path);
    }
}
