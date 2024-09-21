<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MarvelApiService
{
    private $client;
    private $publicKey;
    private $privateKey;
    private $baseUrl;

    public function __construct(HttpClientInterface $client, string $publicKey, string $privateKey, string $baseUrl)
    {
        $this->client = $client;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->baseUrl = rtrim($baseUrl, '/'); // Ensure no trailing slash
    }

    public function fetchMarvelData(string $endpoint, array $params = []): array
    {
        $ts = time();
        $hash = md5($ts.$this->privateKey.$this->publicKey);

        $params = array_merge($params, [
            'apikey' => $this->publicKey,
            'ts' => $ts,
            'hash' => $hash,
        ]);
        // echo "here<br/>";
        //   print_r($endpoint);
        //   echo "<br/>here<br/>";
        //   print_r($params);
        $response = null;
        if ('charactersbyid' == $endpoint) {
            $hero_id = $params['id'];
            unset($params['id']);
            $response = $this->client->request('GET', $this->baseUrl.'/v1/public/characters/'.$hero_id, [
                'query' => $params,
            ]);
        } else {
            $response = $this->client->request('GET', $this->baseUrl.'/v1/public/'.$endpoint, [
                'query' => $params,
            ]);
        }

        return $response->toArray();
    }
}
