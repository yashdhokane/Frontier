<?php

// app/Http/Controllers/CrmController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client; // Assuming you have a Client model

class CrmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Use the middleware to check if user is an admin for these actions
    }

    // Show a list of clients
    public function showClients()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    // Show a form to create a new client
    public function createClientForm()
    {
        return view('clients.create');
    }

    // Store a newly created client in the database
    public function storeClient(Request $request)
    {
        // Validation might be needed here

        $client = new Client();
        $client->name = $request->input('name');
        // Set other client attributes
        $client->save();

        return redirect()->route('clients.index')->with('success', 'The client has been created successfully.');
    }

    // Show the form to edit a client
    public function editClientForm($id)
    {
        $client = Client::find($id);
        return view('clients.edit', compact('client'));
    }

    // Update the specified client in the database
    public function updateClient(Request $request, $id)
    {
        // Validation might be needed here

        $client = Client::find($id);
        $client->name = $request->input('name');
        // Update other client attributes
        $client->save();

        return redirect()->route('clients.index')->with('success', 'The client has been updated successfully.');
    }

    // Delete the specified client from the database
    public function deleteClient($id)
    {
        $client = Client::find($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'The client has been deleted successfully.');
    }
}

