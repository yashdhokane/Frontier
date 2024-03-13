<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container">
        <h2>Clients</h2>
        <a href="{{ route('clients.create') }}" class="btn btn-primary mb-3">Add Client</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('clients.delete', $client->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
