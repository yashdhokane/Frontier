@extends('home')
@section('content')

<!-- Form for assigning a ticket -->
<form action="{{ route('tickets.updateAssign', $ticket->id) }}" method="POST">
    @csrf
    <!-- Dropdown list for selecting user/team -->
    <select name="assigned_user_id">
        @foreach($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <!-- Add more input fields for additional assignment details if needed -->
    <button type="submit">Assign Job</button>
</form>


@endsection