<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

if (!function_exists('getSiteSettings')) {
    /**
     * Get site settings by ID.
     *
     * @param  int  $id
     * @return mixed
     */
    function getSiteSettings($id = 1)
    {
        return DB::table('site_settings')->where('id', $id)->first();
    }
}
