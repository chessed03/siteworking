<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Email extends Model
{
	use HasFactory;

    protected $table = 'emails';

    protected $primaryKey = 'idEmail';

    protected $fillable = ['emailUser','emailAddress','emailStatus','emailCreatedBy'];

    public function getDataForEmailsView( $keyWord, $paginateNumber, $orderBy )
    {
        $query = DB::table('emails');

        $query->whereRaw('emailUser LIKE "' . $keyWord . '"');

        $query->whereRaw('emailStatus = 1');

        if ( $orderBy == 1 ) {

            $query->orderByRaw('emailUser ASC');

        }

        if ( $orderBy == 2 ) {

            $query->orderByRaw('emailUser DESC');

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

    public function validateNewEmailNoRepeat( $idEmail, $emailUser)
    {

        $query = DB::table('emails');

        if ( $idEmail ) {

            $query->whereRaw('idEmail != "'. $idEmail . '"');

        }

        $query->whereRaw('emailUser =  "'. $emailUser . '"');

        $query->whereRaw('emailStatus = 1');

        $result = $query->first();

        if ( $result ) {

            return false;

        }

        return true;

    }

    public function getActivesEmails()
    {
        $query = DB::table('emails')
            ->select(DB::raw('
                idEmail,
                emailAddress
            '));

        $query->whereRaw('emailStatus = 1');

        $result = $query->get();

        return $result;
    }

}
