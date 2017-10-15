<?php

namespace App\Presenters;

use Hack\Map\MapFactoryInterface;
use Hack\Map\MapRecord;
use Hack\OpenWeather\OpenWeatherApi;

class HomepagePresenter extends BasePresenter
{
    private $mapFactory;
    private $weatherApi;

    public function __construct(
        MapFactoryInterface $mapFactory,
        OpenWeatherApi $weatherApi
    ) {
        parent::__construct();
        $this->mapFactory = $mapFactory;
        $this->weatherApi = $weatherApi;
    }

    protected function createComponentMap()
    {
        return $this->mapFactory->create();
    }

    public function renderDefault()
    {
        $records = [
            new MapRecord(
                'foo',
                'bar',
                '#',
                'abc',
                'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                48.9834198,
                14.4698109,
                'qqq'
            ),
        ];
        $this['map']->setRecords($records);
    }

    public function actionLine()
    {
        $string = file_get_contents(__DIR__ . '/sample.json');
        $json = json_decode($string);
        $line = [];
        foreach($json->oLineMapData->aoStations as $station) {
            foreach ((array) $station->aoCoor as $coor) {
                $stat = new \stdClass();
                $stat->lat = $coor->x;
                $stat->lng = $coor->y;
                $stat->name = $station->Name;
                $line[] = $stat;
            }
            $stat = new \stdClass();
            $stat->lat = $station->Lat;
            $stat->lng = $station->Lng;
            $stat->name = $station->Name;
            $line[] = $stat;
        }
        $this->sendJson($line);
    }

    public function actionWeather()
    {
        dump($this->weatherApi->getWeather(new \DateTime('NOW')));
        dump($this->weatherApi->getWeather(new \DateTime('NOW - 1DAY')));
        dump($this->weatherApi->getWeather(new \DateTime('NOW + 2DAYS 4HOURS')));
        $this->terminate();
    }
}
