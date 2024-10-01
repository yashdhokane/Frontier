  @if(Route::currentRouteName() != 'dash')
@extends('home')

@section('content')
 @endif
    @include('schedule.demoContent_iframe')
      @if(Route::currentRouteName() != 'dash')
@endsection
 @endif