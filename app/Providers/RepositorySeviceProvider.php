<?php

namespace App\Providers;

use App\Repositories\ClientRepository;
use App\Repositories\ClientRepositoryInterface;
use App\Services\ClientService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ClientService::class, function ($app) {
            return new ClientService($app->make(ClientRepositoryInterface::class));
        });
    }
}
