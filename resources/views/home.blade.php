@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-box">

                <h5>Hi <strong>{{ Auth::user()->name }},</strong> {{ __('You are logged in to ') }}{{ config('app.name', 'Laravel') }}</h5>
                </br>
                <hr>

                <div class="row w-100">
                        <div class="col-md-3">
                            <div class="card border-info mx-sm-1 p-3">
                                <div class="card border-info text-info p-3" ><span class="text-center fa fa-plane-departure" aria-hidden="true"></span></div>
                                <div class="text-info text-center mt-3"><h4>Flights</h4></div>
                                <div class="text-info text-center mt-2"><h1>234</h1></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success mx-sm-1 p-3">
                                <div class="card border-success text-success p-3 my-card"><span class="text-center fa fa-luggage-cart" aria-hidden="true"></span></div>
                                <div class="text-success text-center mt-3"><h4>Baggage</h4></div>
                                <div class="text-success text-center mt-2"><h1>9,332</h1></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger mx-sm-1 p-3">
                                <div class="card border-danger text-danger p-3 my-card" ><span class="text-center fa fa-person-booth" aria-hidden="true"></span></div>
                                <div class="text-danger text-center mt-3"><h4>Passengers</h4></div>
                                <div class="text-danger text-center mt-2"><h1>12,762</h1></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning mx-sm-1 p-3">
                                <div class="card border-warning text-warning p-3 my-card" ><span class="text-center fa fa-users" aria-hidden="true"></span></div>
                                <div class="text-warning text-center mt-3"><h4>Users</h4></div>
                                <div class="text-warning text-center mt-2"><h1>{{ Auth::user()->count() }}</h1></div>
                            </div>
                        </div>
                     </div>
                </div>

        </div>
    </div>
</div>
@endsection
