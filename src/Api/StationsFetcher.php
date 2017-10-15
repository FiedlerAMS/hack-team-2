<?php

namespace Hack\Api;

use Hack\Api\Entity\Station;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class StationsFetcher
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

    public function fetch(string $type = null)
    {
        $response = $this->fiedlerRequester->get('/stations');
        $stations = $this->hydrate($response->raw_body);
        return null === $type 
            ? $stations 
            : $this->filterType($stations, $type)
        ;
    }

    private function filterType(array $stations, string $type)
    {
        return array_filter($stations, function (Station $station) use ($type) {
            return $station->purpose === $type;
        });
    }

    /**
     * @param string $json
     *
     * @return Station[]
     */
    private function hydrate(string $json)
    {
        return $this->serializer->deserialize($json, Station::class . '[]', 'json');
    }
}
