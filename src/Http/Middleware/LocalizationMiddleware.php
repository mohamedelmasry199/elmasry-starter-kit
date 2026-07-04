<?php

namespace Elmasry\StarterKit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check session first
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } 
        // Check browser language
        elseif ($request->server('HTTP_ACCEPT_LANGUAGE')) {
            $browserLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            $locale = in_array($browserLang, ['en', 'ar']) ? $browserLang : Config::get('starter-kit.locale.default', 'en');
        }
        // Fallback to default
        else {
            $locale = Config::get('starter-kit.locale.default', 'en');
        }

        App::setLocale($locale);
        Config::set('app.locale', $locale);

        return $next($request);
    }
}
