<?php

namespace Hack\Map;

class MapRecord
{
    private $title;
    private $description;
    private $link;
    private $image;
    private $marker;
    private $latitude;
    private $longitude;
    private $htmlTargetId;

    public function __construct(
        string $title,
        string $description,
        string $link,
        string $image,
        string $marker,
        float $latitude,
        float $longitude,
        string $htmlTargetId
    ){

        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->image = $image;
        $this->marker = $marker;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->htmlTargetId = $htmlTargetId;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
            'image' => $this->image,
            'marker' => $this->marker,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'htmlTargetId' => $this->htmlTargetId,
        ];
    }
}
