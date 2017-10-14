<?php

namespace App\Presenters;

use Hack\Map\MapFactoryInterface;

class HomepagePresenter extends BasePresenter
{
    private $mapFactory;

    public function __construct(
        MapFactoryInterface $mapFactory
    ){
        parent::__construct();
        $this->mapFactory = $mapFactory;
    }

    protected function createComponentMap()
    {
        return $this->mapFactory->create();
    }

    public function renderDefault()
    {
        $this->template->anyVariable = 'any value';
    }
}
