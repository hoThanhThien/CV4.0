<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = null;

        // 1. Kiểm tra query param ?lang=vi hoặc ?lang=en (Hỗ trợ Googlebot & crawler đa ngôn ngữ)
        if ($request->has('lang') && in_array($request->get('lang'), ['vi', 'en'])) {
            $locale = $request->get('lang');
            session()->put('locale', $locale);
        }
        // 2. Kiểm tra session lưu trữ trước đó
        elseif (session()->has('locale') && in_array(session()->get('locale'), ['vi', 'en'])) {
            $locale = session()->get('locale');
        }
        // 3. Tự động nhận diện ngôn ngữ từ trình duyệt người dùng (Accept-Language)
        elseif ($request->header('Accept-Language')) {
            $preferred = $request->getPreferredLanguage(['vi', 'en']);
            if ($preferred) {
                $locale = $preferred;
            }
        }

        if ($locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
