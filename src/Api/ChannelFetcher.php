<?php

namespace Hack\Api;

use Hack\Api\Entity\Channel;
use Hack\Api\Entity\Station;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ChannelFetcher
{
    private $fiedlerRequester;
    private $serializer;

    public function __construct(FiedlerRequester $fiedlerRequester)
    {
        $this->fiedlerRequester = $fiedlerRequester;

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new ArrayDenormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function fetch(array $stations, string $type = null)
    {
        /** @var Station $station */
        foreach ($stations as $station) {
            $response = $this->fiedlerRequester->get("/stations/{$station->id}/channels");
            $channels = $this->hydrate($response->body, $station);
            $filtered = $this->filterChannels($channels, $type);
            $station->channels = $filtered;
        }

        return $stations;
    }

    private function filterChannels(array $stations, string $type = null)
    {
        if (null === $type) {
            return $stations;
        }
        return array_filter($stations, function (Channel $station) use ($type) {
            return $station->purpose === $type;
        });
    }

    /**
     * @param array   $arr
     * @param Station $station
     *
     * @return Channel[]
     */
    private function hydrate(array $arr, Station $station)
    {
        $channels = [];
        foreach ($arr as $value) {
            $element = $value->metadata;
            $channels[] = $ch = new Channel();
            $ch->station = $station;
            $ch->id = $element->id;
            $ch->purpose = $element->purpose;
            $ch->label = $element->label;
            $ch->units = $element->units;
        }

        return $channels;
    }
}
