<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application's locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');
        $type = $request->input('type', 'global');
        if (!array_key_exists($locale, config('app.available_locales', [])))
        {
            return redirect()->back();
        }

        if ($type === 'global')
        {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        else
        {
            Session::put('crud_locale', $locale);
        }

        return redirect()->back();
    }
}
