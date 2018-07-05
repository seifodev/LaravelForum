@extends('layouts.app')

@section('content')
    <div class="container">


        <div class="page-header">
            <h2>
                {{ $profileUser->name }} <small>Since: {{$profileUser->created_at->diffForHumans()}}</small>
            </h2>
        </div>


        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @forelse($activities as $date => $records)
                    <h4 class="page-header">{{ $date }}</h4>
                    @foreach($records as $activity)
                        @if(view()->exists("profiles.activities.{$activity->type}"))
                            @include("profiles.activities.{$activity->type}")
                        @endif

                    @endforeach
                    @empty
                    <p class="alert alert-info">No activities found</p>
                @endforelse


            </div><!-- end .col -->
        </div><!-- end .row -->
    </div><!-- end .container -->




@endsection
