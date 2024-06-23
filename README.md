# zinapse/igdb-laravel
 Interact with the IGDB API with Laravel!

# Usage
 *Make sure you have `IGDB_CLIENT_ID` and `IGDB_CLIENT_SECRET` set in your `.env`*

 To get an access token for the API, you would use the static function from the `API` model:

 ```php
    // This isn't required, but if you don't want multiple Guzzle
    // clients created you should make a Client object and pass it
    // around to each of the functions
    $client = new \GuzzleHttp\Client();

    // Returns your token as a string
    $token = \Zinapse\IgdbLaravel\Models\API::GetAccessToken($client);
 ```
 