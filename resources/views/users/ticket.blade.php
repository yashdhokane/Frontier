
@extends('home')

@section('content')
  <!-- users/tickets.blade.php -->

<h1>Tickets Assigned to {{ $user->name }}</h1>

@foreach($tickets as $ticket)
    <div>
        <h2>{{ $ticket->title }}</h2>
        <p>{{ $ticket->description }}</p>
        <!-- Display other ticket details -->
        <form method="POST" action="{{ route('tickets.assign', $ticket->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit">Assign to {{ $user->name }}</button>
        </form>
    </div>
@endforeach


@endsection