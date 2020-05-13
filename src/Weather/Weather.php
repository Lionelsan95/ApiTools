<?php


namespace App\Weather;


use GuzzleHttp\Client;
use JMS\Serializer\Serializer;

class Weather
{
    private $weatherClient;

    private $apiKey;

    private $serializer;

    public function __construct(Client $weatherClient, Serializer $serializer)
    {
        $this->weatherClient = $weatherClient;
        $this->apiKey = "3f80b65764be97f2b952631b4343501c";
        $this->serializer = $serializer;
    }

    public function getCurrent()
    {
        $uri = "/data/2.5/weather?q=Paris&appid=".$this->apiKey;
        $response = $this->weatherClient->get($uri);

        $data = $this->serializer->deserialize(
            $response->getBody()->getContents(),
            'array',
            'json');

        return [
            'city' => $data['name'],
            'description' => $data['weather'][0]['main']
        ];
    }
}
