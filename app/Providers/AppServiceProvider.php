<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer for sharing site settings globally
        view()->composer('*', function ($view) {
            try {
                if (\Schema::hasTable('settings')) {
                    $settings = \App\Models\Setting::all()->pluck('value', 'key');
                    
                    // Create an anonymous object with a get method for backward compatibility
                    $settingsObj = new class($settings) {
                        private $data;
                        public function __construct($data) { $this->data = $data; }
                        public function get($key, $default = null) {
                            return $this->data->get($key, $default);
                        }
                    };
                    
                    $view->with('site_settings', $settingsObj);
                }
            } catch (\Exception $e) {
                // Silently ignore errors during migrations
            }
        });
    }
}
