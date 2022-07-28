<?php

namespace App\Providers;

use App\Interfaces\ISearchRepository;
use App\Services\SearchService;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ISearchRepository::class, function () {
            // Это полезно, если мы хотим выключить наш кластер
            // или при развертывании поиска на продакшене
            if (!config('services.search.enabled')) {
                return new SearchService();
            }
            return new SearchService(
                $this->app->make(Client::class)
            );
        });
        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        DB::listen(function ($query) {
////            if ($query->time >= 50) {
//                $location = collect(debug_backtrace())->filter(function ($trace) {
//                    return key_exists('file', $trace) && !str_contains($trace['file'], 'vendor/');
//                })->first(); // берем первый элемент не из каталога вендора
//                $bindings = implode(", ", $query->bindings); // форматируем привязку как строку
//                Log::info("
//               ------------
//               Sql: $query->sql
//               Bindings: $bindings
//               Time: $query->time
//               File: ${location['file']}
//               Line: ${location['line']}
//               ------------
//               ");
////            }
//        });
    }
}
