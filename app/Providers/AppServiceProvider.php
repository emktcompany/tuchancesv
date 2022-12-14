<?php

namespace App\Providers;

use App\TuChance\Contracts\Repositories\Bidders as BiddersContract;
use App\TuChance\Contracts\Repositories\Candidates as CandidatesContract;
use App\TuChance\Contracts\Repositories\Opportunities as OpportunitiesContract;
use App\TuChance\Contracts\Repositories\Posts as PostsContract;
use App\TuChance\Repositories\Bidders;
use App\TuChance\Repositories\Candidates;
use App\TuChance\Repositories\Opportunities;
use App\TuChance\Repositories\Posts;
use Facebook\Facebook;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_AnalyticsReporting;
use Google_Service_Oauth2;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['db']
            ->connection()
            ->getSchemaBuilder()
            ->defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OpportunitiesContract::class, Opportunities::class);
        $this->app->bind(BiddersContract::class, Bidders::class);
        $this->app->bind(CandidatesContract::class, Candidates::class);
        $this->app->bind(PostsContract::class, Posts::class);

        $this->app->singleton(Google_Client::class, function () {
            $client = new Google_Client;
            $client->setApplicationName(env('APP_NAME'));

            if (is_file(storage_path('app/credentials.json'))) {
                $client->setAuthConfig(
                    storage_path('app/credentials.json')
                );
            }

            $client->setRedirectUri('postmessage');
            return $client;
        });

        $this->app->singleton(Google_Service_Oauth2::class, function () {
            return new Google_Service_Oauth2(app(Google_Client::class));
        });

        $this->app->singleton(Google_Service_Analytics::class, function () {
            $client = app(Google_Client::class);

            if (is_file(storage_path('app/analytics.json'))) {
                $client->setAuthConfig(
                    storage_path('app/analytics.json')
                );
            }

            $client->setScopes([
                'https://www.googleapis.com/auth/analytics.readonly',
            ]);
            return new Google_Service_Analytics($client);
        });

        $this->app
            ->singleton(Google_Service_AnalyticsReporting::class, function () {
                $client = app(Google_Client::class);

                if (is_file(storage_path('app/analytics.json'))) {
                    $client->setAuthConfig(
                        storage_path('app/analytics.json')
                    );
                }

                $client->setScopes([
                    'https://www.googleapis.com/auth/analytics.readonly',
                ]);
                return new Google_Service_AnalyticsReporting($client);
            });

        $this->app->singleton(Facebook::class, function () {
            $config = $this->app['config']->get('services.facebook');

            return new Facebook([
                'app_id'                => $config['client_id'],
                'app_secret'            => $config['client_secret'],
                'default_graph_version' => 'v2.2',
            ]);
        });
    }
}
