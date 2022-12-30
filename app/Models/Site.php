<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Site extends Model
{
	use HasFactory;

    protected $table      = 'sites';

    protected $primaryKey = 'idSite';

    protected $fillable   = ['siteName','siteUrl','siteStatus','siteCreatedBy'];

    public function getDataForSitesView( $keyWord, $paginateNumber, $orderBy )
    {
        $query = DB::table('sites');

        $query->whereRaw('siteName LIKE "' . $keyWord . '"');

        $query->whereRaw('siteStatus = 1');

        if ( $orderBy == 1 ) {

            $query->orderByRaw('siteName ASC');

        }

        if ( $orderBy == 2 ) {

            $query->orderByRaw('siteName DESC');

        }

        if ( $orderBy == 3 ) {

            $query->orderByRaw('created_at DESC');

        }

        if ( $orderBy == 4 ) {

            $query->orderByRaw('created_at ASC');

        }

        $result = $query->paginate($paginateNumber);

        return $result;
    }

    public function validateNewSiteNoRepeat( $idSite, $siteName)
    {

        $query = DB::table('sites');

        if ( $idSite ) {

            $query->whereRaw('idSite != "'. $idSite . '"');

        }

        $query->whereRaw('siteName =  "'. $siteName . '"');

        $query->whereRaw('siteStatus = 1');

        $result = $query->first();

        if ( $result ) {

            return false;

        }

        return true;

    }

    public function getActivesSites()
    {
        $query = DB::table('sites')
            ->select(DB::raw('
                idSite,
                siteUrl
            '));

        $query->whereRaw('siteStatus = 1');

        $result = $query->get();

        return $result;
    }

    public function changeSiteHealtForScrapingProcess( $urlFails )
    {
        $sitesProcessed = self::getActivesSites();

        $idSites        = [];

        foreach ( $sitesProcessed as $key => $siteProcessed ) {

            array_push($idSites, $siteProcessed->idSite);

        }

        $idSitesFails = [];

        foreach ( $urlFails as $key => $fail ) {

            array_push($idSitesFails, $fail->idSite);

        }

        if ( !empty( $idSitesFails ) ) {

            $idSitesSuccess = array_diff($idSites, $idSitesFails);

        } else {

            $idSitesSuccess = $idSites;

        }

        self::sitesProccesSuccess( $idSitesSuccess );

        self::sitesProcessFail( $idSitesFails );

        return true;

    }

    public function sitesProccesSuccess( $idSites )
    {

        foreach ( $idSites as $key => $idSite ) {

            $site = self::find( $idSite );

            $site->siteHealth = 1;

            $site->update();

        }

    }

    public function sitesProcessFail( $idSites )
    {

        foreach ( $idSites as $key => $idSite ) {

            $site = self::find( $idSite );

            $site->siteHealth = 2;

            $site->update();

        }

    }

}
