<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Services\PostsService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('datetime', function ($expression) {
            return "<?php echo (new \DateTimeImmutable($expression))->format('Y/m/d H:i'); ?>";
        });

        Blade::directive('time_diff', function ($expression) {
            return  <<<EOT
            <?php echo floor((strtotime('now') - strtotime($expression)) / 86400)
                    ? floor((strtotime('now') - strtotime($expression)) / 86400)."日前"
                    : gmdate('H', (strtotime('now') - strtotime($expression)))."時間前"
            ?>
EOT;
        });
    }
}
