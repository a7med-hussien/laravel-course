<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $tasks   = User::find($user_id)->tasks;

        // dd($tasks);

        return view('home', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "taskname"    => "required",
            "description" => "required|max:200",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $task = new Task;
            $task->taskname    = $request->get("taskname");
            $task->description = $request->get("description");
            $task->user_id     = Auth::user()->id;
            $task->save();
        }
    }

    public function edit($id)
    {
        $task = Task::find($id);
        return response()->json(['task' => $task]);
    }

    public function update(Request $request)
    {
        $task = Task::find($request->id);
        $task->taskname = $request->get("taskname");
        $task->description = $request->get("description");
        $task->save();
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json(['success' => true]);
    }
}