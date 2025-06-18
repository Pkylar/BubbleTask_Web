<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Task::where('user_id', auth()->id());

            // Filter berdasarkan status jika ada
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            } else {
                // Default hanya tampilkan pending tasks
                $query->where('status', 'pending');
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $tasks = $query->orderBy('due_date', 'asc')
                           ->orderByRaw("CASE WHEN priority = 'high' THEN 1 ELSE 2 END ASC")
                           ->get();

            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => $tasks
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'priority' => 'required|in:high,low',
            ]);

            $task = Task::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'],
                'priority' => $validated['priority'],
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        try {
            // Pastikan task milik user yang sedang login
            if ($task->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this task'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Task retrieved successfully',
                'data' => $task
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        try {
            // Pastikan task milik user yang sedang login
            if ($task->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this task'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'priority' => 'required|in:high,low',
                'status' => 'required|in:pending,completed',
            ]);

            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'],
                'priority' => $validated['priority'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => $task->fresh()
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            // Pastikan task milik user yang sedang login
            if ($task->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this task'
                ], 403);
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark task as completed.
     */
    public function complete(Task $task): JsonResponse
    {
        try {
            // Pastikan task milik user yang sedang login
            if ($task->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this task'
                ], 403);
            }

            $task->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Task marked as completed',
                'data' => $task->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tasks for calendar view.
     */
    public function calendar(): JsonResponse
    {
        try {
            $tasks = Task::where('user_id', auth()->id())->get();

            $events = $tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title . ' (' . ucfirst($task->priority) . ')',
                    'start' => $task->due_date instanceof \Carbon\Carbon
                        ? $task->due_date->format('Y-m-d\TH:i:s')
                        : $task->due_date,
                    'color' => $task->priority == 'high' ? '#f87171' : '#60a5fa',
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'description' => $task->description,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Calendar events retrieved successfully',
                'data' => $events
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve calendar events',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tasks ordered by priority.
     */
    public function priority(): JsonResponse
    {
        try {
            $tasks = Task::where('user_id', auth()->id())
                ->orderBy('due_date', 'asc')
                ->orderByRaw("CASE WHEN priority = 'high' THEN 1 ELSE 2 END ASC")
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Priority tasks retrieved successfully',
                'data' => $tasks
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve priority tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get completed tasks.
     */
    public function completed(): JsonResponse
    {
        try {
            $tasks = Task::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->orderBy('updated_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Completed tasks retrieved successfully',
                'data' => $tasks
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve completed tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard summary.
     */
    public function dashboard(): JsonResponse
    {
        try {
            $userId = auth()->id();
            
            $totalTasks = Task::where('user_id', $userId)->count();
            $pendingTasks = Task::where('user_id', $userId)->where('status', 'pending')->count();
            $completedTasks = Task::where('user_id', $userId)->where('status', 'completed')->count();
            $highPriorityTasks = Task::where('user_id', $userId)
                ->where('status', 'pending')
                ->where('priority', 'high')
                ->count();
            
            // Tasks yang akan jatuh tempo dalam 7 hari
            $upcomingTasks = Task::where('user_id', $userId)
                ->where('status', 'pending')
                ->whereBetween('due_date', [now(), now()->addDays(7)])
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Dashboard data retrieved successfully',
                'data' => [
                    'total_tasks' => $totalTasks,
                    'pending_tasks' => $pendingTasks,
                    'completed_tasks' => $completedTasks,
                    'high_priority_tasks' => $highPriorityTasks,
                    'upcoming_tasks' => $upcomingTasks,
                    'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}