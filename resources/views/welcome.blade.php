@extends('layouts.app')
@section('title', __('Welcome'))
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
                <h4 class="page-title">Welcome</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">

        <div class="col-12">

            <div class="card-box">

                <div class="row justify-content-between">
                    <h4 class="header-title mb-3">Welcome</h4>
                </div>

                <h5>
                    @guest

                        {{ __('Welcome to') }} {{ config('app.name', 'Laravel') }} !!!
                        <hr>
                        Please <a href="{{ route('login') }}">Login</a>

                    @else
                        Hi {{ Auth::user()->name }}, Welcome back to {{ config('app.name', 'Laravel') }}.
                    @endif
                </h5>

            </div>

        </div>

    </div>

</div>
@endsection
