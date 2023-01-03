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

    protected $fillable   = ['idCustomer','siteUrl','siteHealth','siteStatus','siteCreatedBy'];

    public function getDataForSitesView( $keyWord, $paginateNumber, $orderBy )
    {
        $query = DB::table('sites');

        $query->leftJoin('customers', 'sites.idCustomer', '=', 'customers.idCustomer');

        $query->whereRaw('customerName LIKE "' . $keyWord . '"');

        $query->whereRaw('siteStatus = 1');

        if ( $orderBy == 1 ) {

            $query->orderByRaw('customerName ASC');

        }

        if ( $orderBy == 2 ) {

            $query->orderByRaw('customerName DESC');

        }

        if ( $orderBy == 3 ) {

            $query->orderByRaw('sites.created_at DESC');

        }

        if ( $orderBy == 4 ) {

            $query->orderByRaw('sites.created_at ASC');

        }

        $result = $query->paginate($paginateNumber);

        return $result;
    }

    public function validateNewSiteNoRepeat( $idSite, $siteUrl)
    {

        $query = DB::table('sites');

        if ( $idSite ) {

            $query->whereRaw('idSite != "'. $idSite . '"');

        }

        $query->whereRaw('siteUrl =  "'. $siteUrl . '"');

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

        self::sitesProccessSuccess( $idSitesSuccess );

        self::sitesProcessFail( $idSitesFails );

        return true;

    }

    public function sitesProccessSuccess( $idSites )
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

    public function getCustomersActives()
    {
        $query = Customer::getCustomersActives();

        return $query;
    }

}
