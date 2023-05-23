<?php

namespace Squareconcepts\ScFontAwesome\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Squareconcepts\ScFontAwesome\Components\FontAwesomeComponent;

class ScFontAwesomeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'sc-fontawesome');
        $this->loadViewsFrom(__DIR__ . '/../views', 'sc-fontawesome');

        $this->publishes([
            __DIR__ . '/../../config/sc-fontawesome.php' => config_path('sc-fontawesome.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/vendor/sc-fontawesome'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../lang' => resource_path('views/vendor/sc-fontawesome'),
        ], 'views');

        Livewire::component('sc-fontawesome-component', FontAwesomeComponent::class);
    }
}
