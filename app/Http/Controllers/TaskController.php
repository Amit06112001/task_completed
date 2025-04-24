<?php

namespace App\Http\Controllers;
use App\Models\TaskModel;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $data['task'] = TaskModel::where('is_completed',0)->orderByDesc('id')->get();
        return view('tasks.index', $data);
    }

    public function fetchTasks()
    {
        // echo "test"; die;
        $tasks = TaskModel::orderByDesc('id')->get();
        return response()->json($tasks);
    }

    public function fetchallTasks()
    {
        $tasks = TaskModel::where('is_completed',0)->orderByDesc('id')->get();
        return response()->json($tasks);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:tbl_tasks,title'
        ]);

        $task = TaskModel::create(['title' => $request->title]);
        return response()->json($task);
    }

    public function complete($id)
    {
        $task = TaskModel::findOrFail($id);
        $task->is_completed = true;
        $task->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        TaskModel::destroy($id);
        return response()->json(['success' => true]);
    }
}
