<?php

namespace App\Http\Middleware;

use App\Exceptions\ReturnResponseException;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    protected $app;

    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Content-Language');

        if (!$locale) {
            $locale = $this->app->getLocale() ?? config('app.locale');
        }

        if (!in_array($locale, config('locales'))) {
            throw new ReturnResponseException('Language not supported', 400);
        }

        $this->app->setLocale($locale);

        $response = $next($request);

        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}
