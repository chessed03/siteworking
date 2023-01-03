<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
	use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'idCustomer';

    protected $fillable = ['customerName','customerStatus','customerCreatedBy'];

    public function getDataForCustomersView( $keyWord, $paginateNumber, $orderBy )
    {
        $query = DB::table('customers');

        $query->whereRaw('customerName LIKE "' . $keyWord . '"');

        $query->whereRaw('customerStatus = 1');

        if ( $orderBy == 1 ) {

            $query->orderByRaw('customerName ASC');

        }

        if ( $orderBy == 2 ) {

            $query->orderByRaw('customerName DESC');

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

    public function validateNewCustomerNoRepeat( $idCustomer, $customerName)
    {

        $query = DB::table('customers');

        if ( $idCustomer ) {

            $query->whereRaw('idCustomer != "'. $idCustomer . '"');

        }

        $query->whereRaw('customerName =  "'. $customerName . '"');

        $query->whereRaw('customerStatus = 1');

        $result = $query->first();

        if ( $result ) {

            return false;

        }

        return true;

    }

    public function getCustomersActives()
    {

        $query = DB::table('customers')
            ->whereRaw('customerStatus = 1');

        $result = $query->get();

        return $result;
    }

    public function validateCustomerActiveOnSites( $id )
    {

        $result = false;

        $ativeOnSites = DB::table('sites')
            ->whereRaw('idCustomer = "' . $id . '"')
            ->whereRaw('siteStatus = 1')
            ->first();

        if ( $ativeOnSites ) {

            $result = true;

        }

        return $result;

    }

}
