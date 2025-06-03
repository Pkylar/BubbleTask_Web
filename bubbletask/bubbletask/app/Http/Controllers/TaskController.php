<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function index(Request $request)
    {
        $query = Task::where('user_id', auth()->id())
                     ->where('status', 'pending'); // hanya tampilkan task pending di home

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

        return view('tasks.index', compact('tasks'));
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
                'url' => route('tasks.show', $task->id),
                'color' => $task->priority == 'high' ? '#f87171' : '#60a5fa',
            ];
        })->values();

        return view('tasks.calendar', compact('events'));
    }

    public function priority()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->orderBy('due_date', 'asc')
            ->orderByRaw("CASE WHEN priority = 'high' THEN 1 ELSE 2 END ASC")
            ->get();

        return view('tasks.priority', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:high,low',
        ]);

        Task::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Task berhasil dibuat!');
    }

    // Method untuk tandai task selesai
    public function complete(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->status = 'done';
        $task->save();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
