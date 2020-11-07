<?php

namespace Buddhika\Laralog;

use Monolog\Logger;
use Illuminate\Support\Facades\DB;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\RotatingFileHandler;

class LaralogServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/laralog.php', 'laralog');
        $this->publishes(
            [
                __DIR__.'/config/laralog.php' => config_path('laralog.php'),
            ]
        );

        $this->log();
    }

    private function log()
    {
        $loggingEnabled = config('laralog.laralog_active');
        $logRotationDays = config('laralog.log_rotation_days');

        if ($loggingEnabled) {
            DB::listen(
                function ($query) use ($logRotationDays) {
                    $log = new Logger('DB');
                    $handler = new RotatingFileHandler(
                        storage_path('logs/query.log'),
                        $logRotationDays,
                        Logger::DEBUG
                    );
                    $handler->setFormatter(new LineFormatter("[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n", 'Y-m-d H:i:s'));
                    $log->pushHandler($handler);
                    $log->debug(sprintf('%07.2f', $query->time), [$query->sql => $query->bindings]);
                }
            );
        }
    }
}