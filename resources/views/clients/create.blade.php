<!-- resources/views/clients/create.blade.php -->

@extends('home')

@section('content')
    <div class="container">
        <h2>Add Client</h2>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <!-- Add other client fields as needed -->
            <button type="submit" class="btn btn-primary">Add Client</button>
        </form>
    </div>
@endsection
