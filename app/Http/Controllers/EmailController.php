<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{

    public function index( Request $request )
    {
        return view('livewire.emails.index');
    }

}
