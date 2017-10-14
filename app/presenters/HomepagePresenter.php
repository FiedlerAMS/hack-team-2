<?php

namespace App\Presenters;

use Hack\Map\MapFactoryInterface;
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
        $this->template->anyVariable = 'any value';
    }

    public function actionWeather()
    {
        dump($this->weatherApi->getWeather(new \DateTime('NOW')));
        dump($this->weatherApi->getWeather(new \DateTime('NOW - 1DAY')));
        dump($this->weatherApi->getWeather(new \DateTime('NOW + 2DAYS 4HOURS')));
        $this->terminate();
    }
}
