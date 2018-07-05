@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Forum</div>

                    <div class="panel-body">
                        @auth()
                            Welcome {{ auth()->user()->name }}
                        @endauth
                        @guest()
                            Please <a href="/login">login</a> or <a href="/register">register</a> to participate in our forum
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
