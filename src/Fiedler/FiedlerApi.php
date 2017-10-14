<?php

namespace Hack\Fiedler;

use App\Http\Client;

class FiedlerApi
{
    private $client;
    private $url;
    private $urls;

    public function __construct($url, Client $client)
    {
        $this->client = $client;
        $this->url = rtrim($url, '/');
        $this->urls = $this->getEndPoints();
    }

    private function getEndPoints()
    {
        return [
            'sample' => function ($id) {
                return "{$this->url}/devices/{$id}";
            },
            'sample/foo' => function ($id, $year = null) {
                $year = $year ?? date('Y');
                return "{$this->url}/device/{$id}/consumptions/{$year}";
            },
        ];
    }

    /** @return array[] */
    public function getMessages($id, $limit)
    {
        $url = $this->urls['sample']($id);
        list($data, $next) = $this->getMessagesFromUrl($url);
        while ($next && count($data) < $limit) {
            list($subQueryData, $next) = $this->getMessagesFromUrl($next);
            $data = array_merge($data, $subQueryData);
        }
        return array_slice($data, 0, $limit);
    }

    private function getMessagesFromUrl($backendUrl)
    {
        $response = $this->client->get($backendUrl);
        return [$response->body->data ?? [], $response->body->paging->next ?? null];
    }

    public function createCallback($deviceTypeId, $callbackUrl)
    {
        $callback = new \StdClass();
        $callback->callbackUrl = $callbackUrl;
        $content = json_encode([$callback]);
        $url = $this->urls['sample/foo']($deviceTypeId);
        $response = $this->client->post($url, $content, ['Content-Type' => 'application/json']);
        return self::isOK($response);
    }

    private static function isOK(\Unirest\Response $response)
    {
        return (int) $response->code === 200;
    }
}
