<?php

namespace App\Service;


use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService
{
    private $client;
    public function __construct(
    ){
        $this->client = RedisAdapter::createConnection('redis://localhost:6379');
    }
    public function getClient(){
        return $this->client;
    }
}