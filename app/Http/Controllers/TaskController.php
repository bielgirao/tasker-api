<?php

namespace App\Http\Controllers;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class TaskController extends Controller
{
    /**
     * @var Task
     */

    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function index(): JsonResponse
    {
        $data = $this->task->all()
            ->map(function($task) {
                $task->deadline = date('d-m-Y', strtotime($task->deadline));
                return $task;
            });
        return response()->json($data);
    }

    public function show($id): JsonResponse
    {
        $task = $this->task->find($id);

        if(!$task){
            return response()->json(ApiError::errorMessage('Task not found', 4040),404);
        }

        $data = $task;
        return response()->json($data);
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|max:25',
                'status' => 'required|in:doing,done,to-do',
                'description' => 'nullable|max:250',
                'deadline' => 'required|date',
            ]);

            $taskData = $request->all();
            $this->task->create($taskData);

            return response()->json(['msg'=>'Task created successfully!']);
        } catch (Exception $e){
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(),1010), 500);
            }
            return response()->json(ApiError::errorMessage('There was an error creating the task.',1010),500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|max:25',
                'status' => 'required|in:doing,done,to-do',
                'description' => 'nullable|max:250',
                'deadline' => 'required|date',
            ]);

            $taskData = $request->all();
            $product = $this->task->find($id);
            $product->update($taskData);

            $return = ['data' => ['msg' => 'Task updated successfully!']];
            return response()->json($return, 201);
        } catch (Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('There was an error updating the task.', 1011), 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $task =$this->task->find($id);
            if(!$task){
                return response()->json(ApiError::errorMessage('Task not found.', 4040),404);
            }

            $task->delete();

            $return = ['data' => ['msg' => 'Task ' . $task->name . ' deleted successfully!']];
            return response()->json($return);
        } catch (Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('There was an error deleting the task.', 1012), 500);
        }
    }


}
