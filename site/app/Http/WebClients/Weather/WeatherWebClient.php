<?php
declare(strict_types=1);

namespace App\Http\WebClients\Weather;

use App\Http\WebClients\Weather\Exception\OperationException;
use App\Http\WebClients\Weather\Exception\ValidationException;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class WeatherWebClient
{
    private const API_PATH = '/jsonrpc';
    private const LAST_DAYS = 30;
    private const METHOD_GET_BY_DATE = 'weather.getByDate';
    private const METHOD_GET_HISTORY = 'weather.getHistory';

    private string $apiHost;

    private Client $httpClient;

    public function __construct(string $apiHost, Client $httpClient)
    {
        $this->apiHost = $apiHost;
        $this->httpClient = $httpClient;
    }

    /**
     * @param Carbon $date
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getByDate(Carbon $date): array
    {
        return $this->request(static::METHOD_GET_BY_DATE, [
            'date' => $date->toDateString(),
        ]);
    }

    /**
     * @param int $lastDays
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHistory($lastDays = self::LAST_DAYS): array
    {
        return $this->request(static::METHOD_GET_HISTORY, [
            'lastDays' => $lastDays,
        ]);
    }

    /**
     * @param string $method
     * @param array $params
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $method, array $params): array
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => 1,
        ];
        $effectiveUri = '';
        $response = $this->httpClient->post($this->baseUrl($method), [
                RequestOptions::JSON => $data,
                RequestOptions::ON_STATS => function (TransferStats $stats) use (&$effectiveUri) {
                    $effectiveUri = urldecode((string)$stats->getEffectiveUri());
                },
            ]
        );

        return $this->handleResponse($response, $effectiveUri);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    private function baseUrl($method)
    {
        return 'http://' . $this->apiHost . self::API_PATH;
    }

    /**
     * @throws OperationException
     */
    private function handleResponse(ResponseInterface $response, string $effectiveUri): array
    {
        try {
            $content = $response->getBody()->getContents();
            $contentJson = json_decode($content, true);
        } catch (\Exception $e) {
            $content = $response->getBody()->getContents();
            throw new OperationException($effectiveUri, $content, 'Invalid JSON response');
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new OperationException($effectiveUri, $content, $response->getStatusCode());
        }

        $errors = $contentJson['error']['data']['violations'] ?? [];
        if ($errors) {
            throw new ValidationException($effectiveUri, $content, $contentJson['error']['code'], $errors);
        }

        $responseParams = $contentJson['result'] ?? null;

        if (!$responseParams) {
            throw new OperationException($effectiveUri, $content, 'Invalid JSON response');
        }

        return $responseParams ?? [];
    }
}
