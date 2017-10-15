<?php

namespace App\Presenters;

use Hack\Api\Bus\BusChannel;
use Hack\Api\ChannelDataFetcher;
use Hack\Api\ChannelFetcher;
use Hack\Api\Entity\Station;
use Hack\Api\StationsFetcher;
use Hack\Api\StationType;

class ApiPresenter extends BasePresenter
{
    private $fetcher;
    private $channelFetcher;
    private $channelDataFetcher;

    public function __construct(
        StationsFetcher $fetcher,
        ChannelFetcher $channelFetcher,
        ChannelDataFetcher $channelDataFetcher
    ){
        parent::__construct();
        $this->fetcher = $fetcher;
        $this->channelFetcher = $channelFetcher;
        $this->channelDataFetcher = $channelDataFetcher;
    }

    public function actionDefault()
    {
        $buses = $this->fetcher->fetch(StationType::BUS);
        $buses = $this->channelFetcher->fetch($buses, BusChannel::PASSENGERS);
        /** @var Station $bus */
        foreach ($buses as $bus) {
            $channels = $this->channelDataFetcher->fetch($bus->channels, 500);
        }

        dump($buses);
        die();
    }
}
