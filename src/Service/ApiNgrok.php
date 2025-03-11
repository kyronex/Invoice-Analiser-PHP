<?php

// src/Service/ApiNgrok.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiNgrok
{
    private $client;
    private $urlNgrok;

    public function __construct(HttpClientInterface $client, string $urlNgrok)
    {
        $this->client = $client;
        $this->urlNgrok = $urlNgrok;
    }

    public function getUrl(): ?string
    {
        try {
            $response = $this->client->request('GET', $this->urlNgrok);
            if ($response->getStatusCode() !== 200) {
                return false;
            }
            $tunnels = json_decode($response->getContent(), true);
            foreach ($tunnels['tunnels'] as $tunnel) {
                if ($tunnel['proto'] === 'https') {
                    $result = $tunnel['public_url'];
                    break;
                }
            }
            return $result;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}
