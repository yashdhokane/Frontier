<!-- resources/views/clients/edit.blade.php -->

@extends('home')

@section('content')
    <div class="container">
        <h2>Edit Client</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}">
            </div>
            <!-- Add other client fields as needed -->
            <button type="submit" class="btn btn-primary">Update Client</button>
        </form>
    </div>
@endsection
