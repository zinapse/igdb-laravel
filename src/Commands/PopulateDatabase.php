<?php

namespace Zinapse\IgdbLaravel\Commands;

use Illuminate\Console\Command;

class PopulateDatabase extends Command
{

    private \GuzzleHttp\Client $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'igdb:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the IGDB tables';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // Validate config variables
        if (empty(config('igdb.auth_url')) || empty(config('igdb.client_id')) || empty(config('igdb.client_secret'))) {
            $this->error('Required config variables not found. Please make sure you have IGDB_CLIENT_ID and IGDB_CLIENT_SECRET set in your .env file');
            return;
        }

        $this->client = new \GuzzleHttp\Client();

        $token = \Zinapse\IgdbLaravel\Models\API::GetAccessToken($this->client);
        if (empty($token)) {
            $this->error('No access token returned');
            return;
        }

        // Get the requested endpoint
        $endpoint = strtolower($this->choice('Endpoint', [
            'All',
            'Game',
            'Genre',
            'Platform',
            'Company'
        ], 0));

        // Switch endpoints to set body data
        $body = 'fields *; ';
        switch ($endpoint) {
            case 'game':
                $search_string = $this->ask('Search String ("*" for all games)', '*');
                if ($search_string === '*') {
                    $body .= 'search "' . $search_string . '";';
                }
                break;
        }

        // Send final POST request to get data
        $response = $this->client->request('POST', config('igdb.base_url') . strtolower($endpoint), [
            'headers' => [
                'Client-ID' => config('igdb.client_id'),
                'Authorization' => 'Bearer ' . $token
            ],
            'body' => $body,
        ]);

        $this->info($response->getBody());

        return;
    }
}