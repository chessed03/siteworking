<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    //
    public function index( Request $request )
    {
        return view('livewire.sites.index');
    }

    public function scraping( Request $request)
    {

        $sites = Site::getActivesSites();

        $urlFails = [];

        foreach ( $sites as $key => $site ) {

            $result = __consumeScraperService( $site->siteUrl );

            if ( $result ) {

                $urlFails[$key] = (object)[
                    'idSite'  => $site->idSite,
                    'siteUrl' => $result->url,
                    'status'  => $result->code
                ];

            }
        }

        //dd($urlFails);

    }


}
