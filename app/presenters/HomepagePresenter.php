<?php

namespace App\Presenters;

use Hack\Api\Bus\BusesUseCase;
use Hack\Api\Entity\ChannelData;
use Hack\Map\MapFactoryInterface;
use Hack\Map\MapRecord;
use Hack\OpenWeather\OpenWeatherApi;

class HomepagePresenter extends BasePresenter
{
    private $mapFactory;
    private $weatherApi;
    private $busesUseCase;
    private $baseUrl;

    public function __construct(
        MapFactoryInterface $mapFactory,
        OpenWeatherApi $weatherApi,
        BusesUseCase $useCase
    ) {
        parent::__construct();
        $this->mapFactory = $mapFactory;
        $this->weatherApi = $weatherApi;
        $this->busesUseCase = $useCase;
    }

    public function startup()
    {
        parent::startup();
        $this->baseUrl = $this->getHttpRequest()->getUrl()->baseUrl;
    }

    protected function createComponentMap()
    {
        return $this->mapFactory->create();
    }

    private function buses()
    {
        $uc = $this->busesUseCase;
        $buses = $uc();

        $lats = [];
        $records = [];
        /** @var ChannelData $channelData */
        foreach ($buses[0]->channels[3]->data as $channelData) {
            $lats[$channelData->timestamp] = $channelData->value;
        }
        foreach ($buses[0]->channels[4]->data as $channelData) {
            if (!isset($lats[$channelData->timestamp])) {
                continue;
            }
            $records[] = new MapRecord(
                $channelData->timestamp,
                'bar',
                '#',
                'abc',
                $this->baseUrl . '/images/spinner.gif',
                $lats[$channelData->timestamp],
                $channelData->value,
                $channelData->timestamp
            );
        }
        $this['map']->setRecords($records);
        $this['map']->setZoom(15);
    }

    private function json()
    {
        $prefix = 'snapped'; 
        $files = [
            [
                'file' => __DIR__ . "/routes/{$prefix}3.json",
                'color' => '#00ff00',
            ],
            [
                'file' => __DIR__ . "/routes/{$prefix}11.json",
                'color' => '#ff0000',
            ],
            [
                'file' => __DIR__ . "/routes/{$prefix}19.json",
                'color' => '#0000ff',
            ],
        ];
        foreach ($files as $data) {
            $records = [];
            $json = json_decode(file_get_contents($data['file']), true);
            foreach ($json as $point) {
                $records['points'][] = new MapRecord(
                    '',
                    '',
                    '#',
                    '',
                    $this->baseUrl . '/images/spinner.gif',
                    $point['lat'],
                    $point['lng'],
                    ''
                );
                $records['color'] = $data['color'];
            }
            $this['map']->appendRecords($records);
        }
        
        $this['map']->setZoom(13);
    }

    public function renderDefault()
    {
//        $this->buses();
        $this->json();
    }

    public function actionWeather()
    {
        dump($this->weatherApi->getWeather(new \DateTime('NOW')));
        dump($this->weatherApi->getWeather(new \DateTime('NOW - 1DAY')));
        dump($this->weatherApi->getWeather(new \DateTime('NOW + 2DAYS 4HOURS')));
        $this->terminate();
    }
}
