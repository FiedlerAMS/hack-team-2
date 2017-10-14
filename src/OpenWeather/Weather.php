<?php

namespace Hack\OpenWeather;

class Weather
{

    private $weatherCode;
    private $name;
    private $dateTime;

    public function __construct(int $weatherCode, string $name = "", int $timestamp = 0)
    {
        $this->weatherCode = $weatherCode;
        $this->name = $name;
        $this->dateTime = \DateTimeImmutable::createFromFormat('U', $timestamp);
    }

    public static function fromOpenWeather(\stdClass $weather): Weather
    {
        $code = $weather->weather[0]->id ?? 0;
        $desc = $weather->weather[0]->description ?? "";
        $timestamp = $weather->dt ?? 0;
        return new Weather($code, \Nette\Utils\Strings::firstUpper($desc), $timestamp);
    }

    public function isValid()
    {
        return $this->weatherCode > 200;
    }

    public function isRaining(): bool
    {
        $rain = $this->weatherCode >= 500 && $this->weatherCode < 600;
        $drizzle = $this->weatherCode >= 311 && $this->weatherCode <= 321;
        return $rain || $drizzle;
    }

    public function isSnowing(): bool
    {
        return $this->weatherCode >= 600 && $this->weatherCode < 700;
    }

    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }
}
