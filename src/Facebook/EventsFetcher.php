<?php

namespace Hack\Facebook;

class EventsFetcher
{
    private $distance;
    private $latitude;
    private $longitude;
    private $accessToken;
    private $facebookRequester;

    public function __construct(FacebookRequester $facebookRequester)
    {
        $this->distance = 3000; // in meters
        $this->latitude = 48.9834198;
        $this->longitude = 14.4698109;
        $this->facebookRequester = $facebookRequester;
        $this->accessToken = '731912170329931|cc70cb3690dc3eb3881727a6c8dc645c';
    }

    public function fetchEvents($timestamp)
    {
        $since = $timestamp;
        $until = $since + 1 * 60 * 60;

        $query = http_build_query([
            'since' => $since,
            'until' => $until,
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'distance' => $this->distance,
            'accessToken' => $this->accessToken,
        ]);
        $queryPath = "/events?{$query}";
        $response = $this->facebookRequester->get($queryPath);
        return $response->body->events;
    }
}
