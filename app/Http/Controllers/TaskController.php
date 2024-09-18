<?php

namespace App\Http\Controllers;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $data = Auth::user()->tasks->map(function($task) {
            $task->deadline = date('d-m-Y', strtotime($task->deadline));
            return $task;
        });
        return response()->json($data);
    }

    public function show($id): JsonResponse
    {
        $task = Auth::user()->tasks()->find($id);

        if (!$task) {
            return response()->json(ApiError::errorMessage('Task not found', 4040), 404);
        }

        return response()->json($task);
    }


    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:25',
                'status' => 'required|in:doing,done,to-do',
                'description' => 'nullable|string|max:250',
                'deadline' => 'required|date',
            ]);

            $task = Auth::user()->tasks()->create($request->all());
            return response()->json($task, 201);
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
            $task = Auth::user()->tasks()->find($id);

            if (!$task) {
                return response()->json(ApiError::errorMessage('Task not found or you do not have permission to update this task.', 4040), 404);
            }

            $request->validate([
                'name' => 'required|max:25',
                'status' => 'required|in:doing,done,to-do',
                'description' => 'nullable|max:250',
                'deadline' => 'required|date',
            ]);

            $task->update($request->all());

            $return = [
                'data' => [
                    'msg' => 'Task updated successfully!',
                    'task' => $task
                ]
            ];
            return response()->json($return, 201);
        } catch (Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('There was an error updating the task.', 1011), 500);
        }
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'required|in:doing,done,to-do',
            ]);

            $task = Auth::user()->tasks()->find($id);

            if (!$task) {
                return response()->json(ApiError::errorMessage('Task not found or you do not have permission to update this task.', 4040), 404);
            }

            $task->status = $request->status;
            $task->save();

            return response()->json([
                'message' => 'Task status updated successfully',
                'task' => $task
            ], 200);
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('There was an error updating the task status.', 1011), 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $task = Auth::user()->tasks()->find($id);

            if (!$task) {
                return response()->json(ApiError::errorMessage('Task not found or you do not have permission to delete this task.', 4040), 404);
            }

            $task->delete();

            $return = [
                'data' => [
                    'msg' => 'Task ' . $task->name . ' deleted successfully!',
                    'task'=> $task
                ]
            ];
            return response()->json($return);
        } catch (Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('There was an error deleting the task.', 1012), 500);
        }
    }


}
