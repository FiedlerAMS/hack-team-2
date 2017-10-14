<?php

namespace Hack\Map;

use App\Utils\GoogleApiKey;
use Nette\Application\UI\Control;
use Webmozart\Assert\Assert;

class Map extends Control
{
    private $googleApiKey;

    private $records = [];

    private $center;

    private $zoom;
    
    private $displayResizingButtons;

    public function __construct()
    {
        parent::__construct();
        $this->googleApiKey = "";
        $this->setCenter(48.714891, 14.117479);
        $this->setZoom(10);
        $this->setDisplayResizingButton(false);
    }

    public function setCenter(float $latitude, float $longitude)
    {
        $this->center = json_encode(['lat' => $latitude, 'lng' => $longitude]);
    }

    public function setZoom(int $zoom)
    {
        $this->zoom = $zoom;
    }

    public function setRecords(array $records)
    {
        Assert::allIsInstanceOf($records, MapRecord::class);
        $this->records = $records;
    }

    public function setDisplayResizingButton($displayResizingButton)
    {
        $this->displayResizingButtons = $displayResizingButton;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/default.latte');
        $this->template->render();
    }

    public function renderJavascript()
    {
        $this->template->center = $this->center;
        $this->template->zoom = $this->zoom;
        $this->template->googleApiKey = $this->googleApiKey;
        $this->template->setFile(__DIR__ . '/templates/javascript.latte');
        $this->template->render();
    }
}
