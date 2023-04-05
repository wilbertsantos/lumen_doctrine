<?php
namespace App\Providers;
use App\Importers\CustomerImporter;
use App\Importers\ImporterInterface;

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
        $this->app->bind(ImporterInterface::class, CustomerImporter::class);
    }
}
