<?php

namespace App\Http\Middleware;

use Closure;

class CustomAuth
{
	private $excerpt =['logout', 'profile', 'dashboard'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if ($request->session()->has('user')) {
			if(\App\User::isSuperuser()){
				return $next($request);
			}else{
				$routeName =str_replace("read_", "", $request->route()->getName());
				$slug =str_replace(["_", "edit", "store"], ["-", "update", "create"], $routeName);
				//dd($slug);
				if(in_array($slug, $this->excerpt)){
					return $next($request);
				}else if(\Permissions::can("view-".$slug) || \Permissions::can($slug)){
					return $next($request);
				}
			}
			
			return redirect('/forbidden');
			
			//$dataUser = $request->session()->get("user");
		}else{
			return redirect('/login');
		}
    }
}
