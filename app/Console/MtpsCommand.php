<?php

namespace App\Console;

use App\Http\Controllers\UploadsAttachments;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;

abstract class MtpsCommand extends Command
{
    use UploadsAttachments;

    /**
     * The database importer
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The database importer
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The database importer
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(DatabaseManager $db, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->connection = $db->connection('mysql');
        $this->client     = new Client([
            'base_uri' => env('MTPS_API_PREFIX'),
            'headers'  => [
                'Authorization' => 'Bearer ' . env('MTPS_API_KEY'),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ]);
        parent::__construct();
    }

    /**
     * Import an api endpoint and all subsequent pages
     * @param  string  $endpoint
     * @param  array   $params
     * @param  \Closure $callback
     * @return void
     */
    public function import($endpoint, array $params, Closure $callback)
    {
        $total_pages  = 1;
        $current_page = 1;

        while ($total_pages >= $current_page) {
            $params['page']['number'] = $current_page;

            $response     = $this->getPage($endpoint, $params);
            $total_pages  = $response->meta->last_page;
            $current_page = $response->meta->current_page + 1;

            collect($response->data)
                ->each($callback);
        }
    }

    /**
     * Apply import callback to each page in a given endpoint call
     * @param  string  $endpoint
     * @param  array   $params
     * @return array
     */
    public function getPage($endpoint, array $params)
    {
        $url      = "/api/v1/{$endpoint}?" . http_build_query($params);
        $response = $this->client->request('get', $url);
        return json_decode($response->getBody()->getContents());

    }

}
