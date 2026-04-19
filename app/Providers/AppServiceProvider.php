<?php

namespace App\Providers;

use App\Services\BrailleConverter;
use App\Services\ChromaService;
use App\Services\DocumentProcessor;
use App\Services\EduBrailleService;
use App\Services\RagService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ChromaService::class);
        $this->app->singleton(DocumentProcessor::class);
        $this->app->singleton(BrailleConverter::class);
        $this->app->singleton(EduBrailleService::class);
        $this->app->singleton(RagService::class, function ($app) {
            return new RagService($app->make(ChromaService::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
