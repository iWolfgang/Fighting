<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Yansongda\Pay\Pay;
use DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {//监控sql语句的log文件
            $sql = $query->sql;
            $bindings = $query->bindings;
            $time = $query->time;
             //写入sql
         if ($bindings) {
            file_put_contents('.sqls', "[" . date("Y-m-d H:i:s") . "]" . $sql . "\r\nparmars:" . json_encode($bindings, 320) . "\r\n\r\n", FILE_APPEND);
          } else {
            file_put_contents('.sqls', "[" . date("Y-m-d H:i:s") . "]" . $sql . "\r\n\r\n", FILE_APPEND);
            }
         });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
   public function register()
    {
       
    }
}
