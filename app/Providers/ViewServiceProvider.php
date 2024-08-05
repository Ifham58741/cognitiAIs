<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Services\HelperService;
use App\Models\MainSetting;

class ViewServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->shareSetting();
    }


    public function shareSetting(): void
    {
        $checkDBStatus = HelperService::checkDBStatus();

        if ($checkDBStatus) {
            if (Schema::hasTable('main_settings')) {
                $settings = MainSetting::first();
                View::share('settings', $settings);
            }
        }

       
    }

 
}
