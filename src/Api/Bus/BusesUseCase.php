<?php

namespace Hack\Api\Bus;


use Hack\Api\ChannelDataFetcher;
use Hack\Api\ChannelFetcher;
use Hack\Api\Entity\Station;
use Hack\Api\StationsFetcher;
use Hack\Api\StationType;

class BusesUseCase
{
    private $channelDataFetcher;
    private $channelFetcher;
    private $fetcher;

    public function __construct(
        StationsFetcher $fetcher,
        ChannelFetcher $channelFetcher,
        ChannelDataFetcher $channelDataFetcher
    ){
        $this->fetcher = $fetcher;
        $this->channelFetcher = $channelFetcher;
        $this->channelDataFetcher = $channelDataFetcher;
    }

    public function __invoke()
    {
        $buses = $this->fetcher->fetch(StationType::BUS);
        $buses = $this->channelFetcher->fetch($buses, BusChannel::PASSENGERS);
        $buses = $this->channelFetcher->fetch($buses, BusChannel::GPS_LAT);
        $buses = $this->channelFetcher->fetch($buses, BusChannel::GPS_LNG);
        /** @var Station $bus */
        foreach ($buses as $bus) {
            $channels = $this->channelDataFetcher->fetch($bus->channels, 60*24);
        }
        return $buses;
    }
}
