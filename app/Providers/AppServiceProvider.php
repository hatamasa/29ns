<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
                        : gmdate('G', (strtotime('now') - strtotime($expression)))."時間前"
                ?>
EOT;
        });

        Blade::directive('addtimestamp', function ($expression) {
            $path = public_path($expression);

            try {
                $timestamp = File::lastModified($path);
            } catch (\Exception $e) {
                $timestamp = Carbon::now()->timestamp;
                report($e);
            }

            return asset($expression) . '?v=' . $timestamp;
        });

    }
}
