@extends('home')
@section('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    
      
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="row gx-0">
                        <div class="col-lg-12">
                            <div class="p-4 calender-sidebar app-calendar">
                                    <h4 class="fc-toolbar-title text-center" id="fc-dom-1">Fri, November 17, 2023</h4>
                            </div>
                        </div>
                        <!-- table -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <div class="table-responsive dat">
                            <table  class="table table-bordered m-t-30 table-hover contact-list text-nowrap" data-paging="true" data-paging-size="7">
                                <thead>
                                    <tr>
                                        <th>Hour</th>
                                        @foreach($technicians as $technician)
                                            <th><a href="javascript:void(0)" class="link">
                                                <img src="{{asset('public/admin/assets/images/users/1.jpg') }}"
                                                alt="user"
                                                width="40"
                                                class="rounded-circle"
                                                /><br>{{ $technician->name }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody id="yourTable" data-toggle="modal" data-target="#addTaskModal">
                                    @for($i = 1; $i <= 24; $i++)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            @foreach($technicians as $technician)
                                                <td class="edit-task"   data-hour="{{ $i }}"  
                                                                        data-technician_id="{{ $technician->id }}" 
                                                                        data-task_description="@foreach($tasks->where('hour', $i)->where('technician_id', $technician->id) as $task) {{ $task->task_description }}@endforeach"
                                                                        data-id="@foreach($tasks->where('hour', $i)->where('technician_id', $technician->id) as $task) {{ $task->id }}@endforeach"
                                                                        data-task_title="@foreach($tasks->where('hour', $i)->where('technician_id', $technician->id) as $task) {{ $task->task_title }}@endforeach"
                                                                        data-technician_id="{{ $technician->id }}">
                                                    @foreach($tasks->where('hour', $i)->where('technician_id', $technician->id) as $task)
                                                        <span  data-toggle="modal1" data-target="#editTaskModal">
                                                            <strong>Title : </strong> {{$task->task_title}} <br>
                                                            <strong>Description : </strong>{{ $task->task_description }} <br>
                                                            <strong>Date : </strong>{{$task->created_at}} <br>
                                                        </span>
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>	

                        <!-- table -->
                    </div>	
                </div>
            </div>
        </div>
    </div>
       <!-- Create Task Modal -->
       <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createTaskForm"action="{{ route('tasks.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="technician_id">Technician ID:</label>
                            <input type="text" class="form-control" id="technician_id" name="technician_id" readonly>
                        </div>
                        <div class="form-group">
                            <label for="hour">Hour:</label>
                            <input type="text" class="form-control" id="hour" name="hour" readonly>
                        </div>
                        <div class="form-group">
                            <label for="task_title">Task Title:</label>
                            <input type="text" class="form-control" id="task_title" name="task_title">
                        </div>
                        <div class="form-group">
                            <label for="task_description">Task Description:</label>
                            <textarea class="form-control" id="task_description" name="task_description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    
    @if(isset($task))
    <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm" action="{{ route('tasks.update', $task->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_hour" name="hour">
                        <input type="hidden" id="edit_technician_id" name="technician_id">
                        <input type="hidden" id="edit_task_id" name="id">
                        <div class="form-group">
                            <label for="edit_task_title">Task Title:</label>
                            <input type="text" class="form-control" id="edit_task_title" name="task_title" value="{{$task->task_title}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_task_description">Task Description:</label>
                            <textarea class="form-control" id="edit_task_description" name="task_description">{{$task->task_description}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    


<!-- script -->


    <script>
        $(document).ready(function() {
            
            $('.edit-task').click(function() {
                var hour = $(this).data('hour');
                var technician = $(this).data('technician_id');
                var taskId = $(this).data('id');
                var taskTitle = $(this).data('task_title');
                var taskDescription = $(this).data('task_description');

                if (!taskDescription || taskDescription.trim() === '') {
                    $('#createTaskModal #technician_id').val(technician);
                    $('#createTaskModal #hour').val(hour);
                    $('#createTaskModal #task_title').val(taskTitle);
                    $('#createTaskModal #task_description').val(taskDescription);
                    $('#createTaskModal').modal('show');
                } else {
                    $('#editTaskModal #edit_technician_id').val(technician);
                    $('#editTaskModal #edit_hour').val(hour);
                    $('#editTaskModal #edit_task_id').val(taskId);
                    $('#editTaskModal #edit_task_title').val(taskTitle);
                    $('#editTaskModal #edit_task_description').val(taskDescription);
                    $('#editTaskModal').modal('show');
                    $.ajax({
                        url: '{{ route("tasks.edit", [ $task->id]) }}',
                        type: 'GET',
                        data: {
                            hour: hour,
                            technician_id: technician,
                            id: taskId,
                            task_title: taskTitle
                        },
                        success: function(response) {
                            $('#editTaskModal #editTaskForm').html(response);
                            $('#yourTable').load(window.location.reload() + ' #yourTable'); 
                            $('#editTaskModal').modal('show');
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });

            $('#createTaskForm').submit(function(e) {
                e.preventDefault();
                var formData = $('#createTaskForm').serialize();
                $.ajax({
                    url: '{{ route("tasks.store") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#yourTable').load(window.location.reload() + ' #yourTable'); 
                        $('#createTaskModal').modal('hide');
                    },
                    error: function(error) {
                        console.log(error);
                        // Handle errors - maybe display an error message
                    }
                });
            });

            $('#editTaskForm').submit(function(e) {
                e.preventDefault();
                var formData = $('#editTaskForm').serialize();
                $.ajax({
                    url: '{{ route("tasks.update" , [ $task->id]) }}',
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#yourTable').load(window.location.reload() + ' #yourTable'); 
                        $('#editTaskModal').modal('hide');
                    },
                    error: function(error) {
                        console.log(error);
                        // Handle errors - maybe display an error message
                    }
                });
            });
        });

    </script>
    @else
    <script>
        $('.edit-task').click(function() {
                var hour = $(this).data('hour');
                var technician = $(this).data('technician_id');
                var taskId = $(this).data('id');
                var taskTitle = $(this).data('task_title');
                var taskDescription = $(this).data('task_description');
                $('#createTaskModal #technician_id').val(technician);
                $('#createTaskModal #hour').val(hour);
                $('#createTaskModal #task_title').val(taskTitle);
                $('#createTaskModal #task_description').val(taskDescription);
                $('#createTaskModal').modal('show');
            }); 

            $('#createTaskForm').submit(function(e) {
                e.preventDefault();
                var formData = $('#createTaskForm').serialize();
                $.ajax({
                    url: '{{ route("tasks.store") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#yourTable').load(window.location.reload() + ' #yourTable'); 
                        $('#createTaskModal').modal('hide');
                    },
                    error: function(error) {
                        console.log(error);
                        // Handle errors - maybe display an error message
                    }
                });
            });
    </script> 
    @endif
@endsection