<?php

namespace Zinapse\IgdbLaravel\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * API model to handle internal API functionality
 */
class API extends Model
{
    public array $supportedEndpoints = [
        'games', 
        'developers',
    ];

    public int $IGDB_SUCCESS = 0;

    public int $IGDB_ERROR = 1;

    /**
     * Return an access token for IGDB
     *
     * @param \GuzzleHttp\Client|null $client
     * @return string|null
     */
    public static function GetAccessToken(\GuzzleHttp\Client $client = null): string|null
    {
        if (is_null($client)) $client = new \GuzzleHttp\Client();

        $auth_uri =
            config('igdb.auth_url') . '?client_id=' . config('igdb.client_id') .
            '&client_secret=' . config('igdb.client_secret') .
            '&grant_type=client_credentials';
        $auth = $client->request('POST', $auth_uri);
        $auth = json_decode($auth->getBody());

        return $auth->access_token ?? null;
    }

    public function queryAPI(\GuzzleHttp\Client $client = null, string $endpoint, string|array $data = null, string $token): array
    {
        if ((empty($endpoint) || empty($token)) || ($endpoint != 'all' && empty($data))) return [
            'status' => API::$IGDB_ERROR
        ];

        if (is_null($client)) $client = new \GuzzleHttp\Client();
        if ($endpoint == 'all') $this->populateAllData($client, $token);

        return [
            'status' => API::$IGDB_SUCCESS
        ];
    }

    private function populateAllData(\GuzzleHttp\Client $client = null, string $token): void
    {
        if (is_null($client)) $client = new \GuzzleHttp\Client();

        $body = 'fields *; ';
        foreach ($this->supportedEndpoints as $endpoint) {
            // Send final POST request to get data
            $response = $client->request('POST', config('igdb.base_url') . strtolower($endpoint), [
                'headers' => [
                    'Client-ID' => config('igdb.client_id'),
                    'Authorization' => 'Bearer ' . $token
                ],
                'body' => rtrim($body),
            ]);
        }
    }
}