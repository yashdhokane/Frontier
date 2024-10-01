  @if(Route::currentRouteName() != 'dash')
@extends('home')

@section('content')
 @endif
    @include('schedule.demoContent')
      @if(Route::currentRouteName() != 'dash')
@endsection
 @endif