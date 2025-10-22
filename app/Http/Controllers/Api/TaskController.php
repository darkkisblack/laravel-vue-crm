<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasAuthenticatedUser;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    use HasAuthenticatedUser;
    public function index(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();
            $query = $user->tasks()->with('deal');

            if ($request->has('completed')) {
                $query->where('completed', filter_var($request->completed, FILTER_VALIDATE_BOOLEAN));
            }

            if ($request->has('deal_id')) {
                $query->where('deal_id', $request->deal_id);
            }

            return $query->orderByDesc('created_at')->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tasks'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'deal_id' => 'required|exists:deals,id',
                'title' => 'required|string|max:255',
                'completed' => 'boolean',
            ]);

            $deal = \App\Models\Deal::where('id', $validated['deal_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $user = $this->getAuthenticatedUser();
            $task = $user->tasks()->create($validated);

            return response()->json($task->load('deal'), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);
            return response()->json($task->load('deal'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch task'], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'completed' => 'sometimes|boolean',
            ]);

            $task->update($validated);
            return response()->json($task->load('deal'));
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete task'], 500);
        }
    }
}
