<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Faculty
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            if ($this->auth->user()->role === 'faculty' || $this->auth->user()->role === 'admin') {
                return $next($request);
            } else {
                // session()->flash('authMessage', 'You\'re not allowed to access this page.');
                return redirect('/');
            }
        }
        session()->flash('authMessage', 'You need to login to acess this page.');
        return redirect('/auth/login');
    }
}
