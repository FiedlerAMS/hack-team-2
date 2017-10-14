<?php

namespace Hack\OpenWeather;

use App\Http\Client;
use Unirest\Response;

class OpenWeatherApi
{
    private $client;
    private $url;
    private $historyUrl;
    private $defaultParameters = [];
    private $cityId;
    private $urls;

    public function __construct(
        Client $client,
        string $url,
        string $historyUrl,
        string $apiKey,
        string $cityId
    ) {
        $this->client = $client;
        $this->url = rtrim($url, '/');
        $this->historyUrl = rtrim($historyUrl, '/');
        $this->cityId = $cityId;
        $this->urls = $this->getEndPoints();
        $this->defaultParameters = [
            'appid' => $apiKey,
            'id' => $cityId,
        ];
    }

    private function getEndPoints()
    {
        return [
            'weather/forecast' => function () {
                return "{$this->url}/forecast";
            },
            'weather/current' => function () {
                return "{$this->url}/weather";
            },
            'weather/history' => function() {
                return "{$this->historyUrl}/history/city?type=hour";
            }
        ];
    }

    public function getWeather(\DateTimeInterface $dateTime)
    {
        if ($dateTime > new \DateTime('NOW')) {
            return $this->getWeatherForecast($dateTime);
        } elseif ($dateTime <= new \DateTime('NOW') && $dateTime >= new \DateTime('NOW - 1HOUR')) {
            return $this->getCurrentWeather();
        } else {
            return $this->getHistoryWeather($dateTime);
        }
    }

    private function getWeatherForecast(\DateTimeInterface $dateTime)
    {
        $endpoint = $this->urls['weather/forecast']();
        $response = $this->httpGet($endpoint);
        if (!self::isOK($response) || empty($response->body->list)) {
            return false;
        }
        return $this->findClosestDate($response->body->list, $dateTime);
    }

    private function findClosestDate(array $openWeathers, \DateTimeInterface $dateTime)
    {
        $closest = $closestDiff = null;
        $pinPointUnix = $dateTime->format('U');
        foreach ($openWeathers as $weatherObj) {
            $weather = Weather::fromOpenWeather($weatherObj);
            $diff = abs($pinPointUnix - $weather->getDateTime()->format('U'));
            if ($diff > $closestDiff && $closest !== null) {
                continue;
            }
            $closestDiff = $diff;
            $closest = $weather;
        }
        return $closest;
    }

    private function getCurrentWeather()
    {
        $endpoint = $this->urls['weather/current']();
        $response = $this->httpGet($endpoint);
        if (!self::isOK($response)) {
            return false;
        }
        return Weather::fromOpenWeather($response->body);
    }

    /** @todo We don´t have key, so we cannot fetch history weather */
    private function getHistoryWeather(\DateTimeInterface $dateTime)
    {
        /*
        $endpoint = $this->urls['weather/history']();
        $params = [
            'start' => $dateTime->format('U'),
            'cnt' => 1,
        ];
        $response = $this->httpGet($endpoint, $params);
        if (!self::isOK($response)) {
            return false;
        }
        return Weather::fromOpenWeather($response->body);
         */
        return new Weather(0);
    }

    private function httpGet($endpoint, $parameters = [], $headers = []): Response
    {
        $withDefault = $parameters + $this->defaultParameters;
        return $this->client->get($endpoint, $withDefault, $headers);
    }

    private static function isOK(Response $response)
    {
        return (int) $response->code === 200;
    }
}
