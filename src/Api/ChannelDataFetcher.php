<?php

namespace Hack\Api;

use Hack\Api\Entity\Channel;
use Hack\Api\Entity\ChannelData;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ChannelDataFetcher
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

    public function fetch(array $channels, $limit = 10)
    {
        /** @var Channel $channel */
        foreach ($channels as $channel) {
            $path = "/stations/{$channel->station->id}/channels/{$channel->id}/data?limit={$limit}";
            $response = $this->fiedlerRequester->get($path);
            $data = $this->hydrate($response->raw_body);
            $channel->data = $data;
        }

        return $channels;
    }

    /**
     * @param string $json
     *
     * @return ChannelData[]
     */
    private function hydrate(string $json)
    {
        return $this->serializer->deserialize($json, ChannelData::class . '[]', 'json');
    }
}
