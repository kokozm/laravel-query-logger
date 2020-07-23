<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020-07-22
 * Time: 15:20
 */

namespace Kokozm\LaravelQueryLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel_query_logger.php',
            'laravel_query_logger'
        );
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publisheFiles();
        
        $this->listenQueryExecuted();
    }
    
    /**
     * @return void
     */
    private function publisheFiles()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel_query_logger.php' => config_path('laravel_query_logger.php'),
        ], 'config');
    }
    
    /**
     * @return void
     */
    private function listenQueryExecuted()
    {
        if (!$this->app[ 'config' ]->get('laravel_query_logger.enabled', false)) {
            return;
        }
        
        DB::listen(function (QueryExecuted $query) {
            $sql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
            
            $bindings = $query->connection->prepareBindings($query->bindings);
            
            if (count($bindings) > 0) {
                $sql = vsprintf($sql, array_map(
                    [$query->connection->getPdo(), 'quote'],
                    $bindings
                ));
            }
            
            $now = now();
            
            $duration = $this->formatDuration($query->time);
            
            $log = "[{$now->toDateTimeString()}] [{$duration}] {$sql}\r\n";
            
            $filename = storage_path('logs/sql-' . $now->toDateString() . '.log');
            
            file_put_contents($filename, $log, FILE_APPEND);
        });
    }
    
    /**
     * @param float $milliseconds
     * @return string
     */
    private function formatDuration($milliseconds)
    {
        if ($milliseconds < 1) {
            return round($milliseconds * 1000) . ' μs';
        } elseif ($milliseconds > 1000) {
            return round($milliseconds / 1000, 2) . ' s';
        }
        
        return round($milliseconds, 2) . ' ms';
    }
}
