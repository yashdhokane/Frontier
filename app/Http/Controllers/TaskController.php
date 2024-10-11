<?php

namespace App\Http\Controllers;


use App\Models\Task;
use App\Models\Technician;
use Illuminate\Http\Request;

class TaskController extends Controller
{
      
    public function index()
    {
        $tasks = Task::with('technician')->get();
        $technicians = Technician::all();
      

        return view('tasks.index', compact('tasks', 'technicians'));
    }

    public function create()
    {
        $technicians = Technician::all();

        return view('tasks.create', compact('technicians'));
    }

    public function store(Request $request)
    {
           // Validate the incoming request data
           $validatedData = $request->validate([
            'hour' => 'required',
            'technician_id' => 'required',
            'task_title' => 'required',
            'task_description' => 'required',
        ]);

        $task = Task::create($validatedData);

        return response()->json(['message' => 'The task has been created successfully.', 'task' => $task]);
    }

    public function edit($id)
    {
        $technicians = Technician::all();
        $task = Task::findOrFail($id);

        return view('tasks.edit', compact('task', 'technicians'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'hour' => 'required',
            'technician_id' => 'required',
            'task_title' => 'required',
            'task_description' => 'required',
        ]);

        // Find the task by ID and update its attributes
        $task = Task::findOrFail($id);
        $task->update($validatedData);

        return response()->json(['message' => 'The task has been updated successfully.', 'task' => $task]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'The task has been deleted successfully.']);
    }
}
