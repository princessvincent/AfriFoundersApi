<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    private function authorizeTask(Task $task): void
    {
        if ($task->user_id !== Auth::id()) {
            response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
    }
    public function getTasks(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $query = Task::where('user_id', Auth::id());

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createTask(CreateTaskRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $task = Task::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'] ?? 'pending',
            ]);

            return response()->json([
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Task $task): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorizeTask($task);

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Task $task): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorizeTask($task);

            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'status' => 'sometimes|in:pending,in-progress,completed'
            ]);

            $task->update($request->only(['title', 'description', 'status']));

            return response()->json([
                'message' => 'Task updated successfully',
                'task' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update task',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function destroy(Task $task): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorizeTask($task);

            $task->delete();

            return response()->json([
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
