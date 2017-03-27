<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
    *
    * URL /tasks/
    * method GET
    *
    * Get list of tasks
    * 
    * @return response json 
    *
    */
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }


    /**
    *
    * URL /tasks/{id}
    * method GET
    *
    * Get one task
    *
    * @param int $id
    *
    * @return response json 
    */
    public function show($id)
    {
        $task = Task::find($id);
		if (!$task) {
            return response()->json(["error" => "Task not found, $id"], 404);		
		}

		return response()->json($task, 200);
    }


    /**
    *
    * URL /tasks
    * method POST
    * 
    * Store task on database
    *
    * @return response json
    */
     function store(Request $request){

        $inputs = $request->all();


        $this->validate($request, [
            "name"    => "required",
        ]);

        $task = new Task;
        $task->name = $inputs['name'];
        $task->save();

        return response()->json($task, 201);
    }


    /**
    *
    * URL /tasks
    * method PUT
    * 
    * Update task on the database
    *
    * @param int $id
    *
    * @return response json
    */
    function update(Request $request, $id){

        $inputs = $request->all();
        $task = Task::find($id);
        if (!$task) {
            return response()->json(["error" => "Task not found, $id"], 404);       
        }
        $task->name = $inputs['name'];
        $task->save();

        return response()->json($task, 200);
    }

    /**
    *
    * URL /tasks/{$id}
    * method DELETE
    *
    * Delete task from database
    *
    * @param int $id 
    *
    * @return empty array
    */
    function delete(Request $request, $id)
    {
		$task = Task::find($id);
        if (!$task) {
            return response()->json(["error" => "Task not found, $id"], 404);       
        }
        $task->delete();
		return response()->json([], 200);
    }
}