<?php

namespace App\Infrastructures\News;

use App\Infrastructures\exceptions\ApiRequestFailedException;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Log;
use Storage;

class News
{
    public function getLatestNewsHeadlines()
    {
        $data = [
            'query' => [
                'country' => 'us',
                'apiKey' => env('NEWS_API_KEY'),
            ]
        ];

        $response = $this->requestApi('GET', 'v2/top-headlines', $data);
        // $data = json_decode($response->getBody(), true);
        dd($response);
    }

    private function requestApi(
        string $method,
        string $endPoint,
        array $data = []
    ) {
        $client = new Client(['base_uri' => config('const.news_api_url')]);

        try {
            $response = $client->request($method, $endPoint, $data);
            dd($response);
            // $responseBody = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\GuzzleHttp\Exception\GuzzleException | \JsonException $e) {
            Log::info($e->getMessage());
            report($e);
            // throw new ApiRequestFailedException('GoogleドライブAPIのリクエストでエラーが発生しました。');
        }

        // return $responseBody;
    }
}
