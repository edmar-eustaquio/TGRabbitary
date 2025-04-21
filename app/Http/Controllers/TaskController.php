<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use CLog;

class TaskController{
    public function index(){
        return inertia('Task/Index');
    }
    public function completed(){
        return inertia('Task/Completed');
    }

    public function get(Request $req){
        return response(
            Task::with(['rabbit'])->whereNull('completed_at')->paginate($req['page_size'] ?? 10)
        );
    }

    public function getCompleted(Request $req){
        return response(
            Task::select(['id', 'title', 'desc', 'completed_at'])
                ->whereNotNull('completed_at')
                ->paginate($req['page_size'] ?? 10)
        );
    }

    public function store(TaskRequest $req){
        $data = $req->validated();
        $data['due_date'] = date('Y-m-d', strtotime(substr($data['due_date'], 0, 10) . ' +1 day'));
        Task::create($data);

        CLog::create('Created', 'Task');
    }

    public function seen(){
        Task::where('id', $id)->update($req->validated());

        CLog::create('Updated', 'Task');
    }

    public function update(TaskRequest $req, $id){
        Task::where('id', $id)->update($req->validated());

        CLog::create('Updated', 'Task');
    }

    public function updateCompleted($id){
        Task::where('id', $id)->update([
            'completed_at' => now()
        ]);

        CLog::create('Completed', 'Task');
    }

    public function delete($id){
        Task::where('id', $id)->delete();

        CLog::create('Deleted', 'Task');
    }
}
