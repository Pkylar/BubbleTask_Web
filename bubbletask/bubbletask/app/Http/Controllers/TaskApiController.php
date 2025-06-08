<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('user_id', auth()->id())->where('status', 'pending');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('due_date', 'asc')
                       ->orderByRaw("CASE WHEN priority = 'high' THEN 1 ELSE 2 END ASC")
                       ->get();

        return response()->json($tasks);
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return response()->json($task);
    }

    public function store(Request $request)
    {
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

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }

    public function complete(Task $task)
    {
        $this->authorizeTask($task);

        $task->status = 'done';
        $task->save();

        return response()->json(['message' => 'Task marked as complete']);
    }

    public function calendar()
    {
        $tasks = Task::where('user_id', auth()->id())->get();

        $events = $tasks->map(function ($task) {
            return [
                'title' => $task->title . ' (' . ucfirst($task->priority) . ')',
                'start' => $task->due_date instanceof \Carbon\Carbon
                    ? $task->due_date->format('Y-m-d\TH:i:s')
                    : $task->due_date,
                'color' => $task->priority == 'high' ? '#f87171' : '#60a5fa',
            ];
        });

        return response()->json($events);
    }

    public function priority()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->orderBy('due_date', 'asc')
            ->orderByRaw("CASE WHEN priority = 'high' THEN 1 ELSE 2 END ASC")
            ->get();

        return response()->json($tasks);
    }

    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
