<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(EquipmentTableManager::class, function ($app) {
            return new EquipmentTableManager();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Product::observe(ProductObserver::class);

        Paginator::useBootstrapFive();

        // Define default settings
        $defaultSettings = [
            'company_name' => 'Extreme Coders',
            'email' => 'info@extremecoders.us',
            'mobile' => '0772353119',
            'logo' => 'logo',
            'favicon' => 'favicon',
            'login_img' => 'login_path',
            'profile' => 'profile',
            'desc' => 'Software Development Company',
            'tags' => 'Jaffna,Software Company, Custom Laravel App',
            'solution' => 'Extreme Coders 🚀'
        ];

        // Check if the settings table exists and retrieve settings if it does
        if (Schema::hasTable('settings')) {
            $settings = Setting::find(1) ?? (object) $defaultSettings; // Use default settings if not found
        } else {
            $settings = (object) $defaultSettings; // Convert array to object for consistency
        }

        View::composer(['layouts.admin.nav', 'layouts.admin.app', 'layouts.admin.auth', 'dashboard'], function ($view) use ($settings) {
            $view->with('setting', $settings);
        });

    }
}
