<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class notify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
     
          $ret = DB::table('test')
            ->insert([
            'respsone' => json_encode($request->all()),
            'time' => date('Y-m-d H:i:s'),
            'url' => $request->path(),
        ]);
         return $next($request);      
    }
}
