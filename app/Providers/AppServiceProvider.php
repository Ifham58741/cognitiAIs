<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use App\Services\HelperService;
use App\Models\MainSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        app()->useLangPath(
            base_path('lang')
        );

        $locale = 'en';

        $checkDBStatus = HelperService::checkDBStatus();

        if ($checkDBStatus) {
            if (Schema::hasTable('main_settings')) {
                $locale = HelperService::checkField('default_language',$locale);
            }
        }
        

        app()->setLocale($locale);

        // URL::forceRootUrl(config('app.url'));
        // if (str_contains(config('app.url'), 'https://')) {
        //     URL::forceScheme('https');
        // }
    }
}
