@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#newTask">Add New Task</button>
                </div>

                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Taskname</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr class="text-center">
                                    <td>{{ $task->taskname }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>
                                        <a href="{{ URL('edit') }}/{{ $task->id }}" data-id="{{ $task->id }}" class="btn btn-info edit"><i class="glyphicon glyphicon-edit"></i></a>
                                        <a href="{{ URL('delete') }}/{{ $task->id }}" data-id="{{ $task->id }}" class="btn btn-danger remove"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add New Task Modal --}}
<div class="modal fade" tabindex="-1" id="newTask" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Add New Task</h4>
            </div>
            <form action="" method="POST" id="addNew">
                <div class="modal-body">
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="text" name="taskname" id="taskname" placeholder="Task Name" class="form-control">
                    </div>
                    <div class="form-group">
                        <textarea name="description" id="description" placeholder="Task Description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Task Modal --}}
<div class="modal fade" tabindex="-1" id="editTask" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Edit Task</h4>
            </div>
            <form action="" method="POST" id="editForm">
                <div class="modal-body">
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="hidden" id="id" name="id">
                        <input type="text" name="taskname" id="taskname" placeholder="Task Name" class="form-control">
                    </div>
                    <div class="form-group">
                        <textarea name="description" id="description" placeholder="Task Description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Updates</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        $(function(){

            $("form#addNew").on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'home',
                    data: $(this).serialize(),
                    type: "POST",
                    success: function(response) {
                        if (response.errors) {
                            $.each(response.errors, function(name, error) {
                                $("#"+name).parents(".form-group").addClass("has-error").append("<p class='text-danger'>"+error+"</p>");
                            });
                        } else {
                            window.location.reload();
                        }
                    }
                });
            });

            $(".edit").on("click", function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr("href"),
                    type: "GET",
                    data: $(this).data("id"),
                    success: function(response) {
                        $("#editTask #id").val(response.task.id);
                        $("#editTask #taskname").val(response.task.taskname);
                        $("#editTask #description").val(response.task.description);

                        $("#editTask").modal("show");
                    }
                });
            });

            $("form#editForm").on("submit", function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'edit',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.reload();
                    }
                });
            });

            $(".remove").on("click", function(event) {
                event.preventDefault();
                var element = $(this);
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this taskname!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        url: element.attr("href"),
                        type: 'GET',
                        data: {id: element.data('id')},
                        success: function(response) {
                            swal("Deleted!", "Your tsk has been deleted.", "success");
                            element.parents("tr").remove();
                        }
                    });
                });
            });
        
        });
    </script>
@endsection