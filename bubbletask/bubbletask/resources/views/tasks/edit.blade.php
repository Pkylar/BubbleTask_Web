@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<h2 class="text-2xl mb-6 font-semibold">Edit Task</h2>

<form method="POST" action="{{ route('tasks.update', $task->id) }}" class="max-w-md space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <label for="title" class="block font-semibold mb-1">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}"
               class="w-full border border-gray-300 rounded px-3 py-2" required>
        @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block font-semibold mb-1">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description', $task->description) }}</textarea>
        @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="due_date" class="block font-semibold mb-1">Due Date</label>
        <input type="datetime-local" id="due_date" name="due_date"
               value="{{ old('due_date', $task->due_date->format('Y-m-d\TH:i')) }}"
               class="w-full border border-gray-300 rounded px-3 py-2" required>
        @error('due_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="priority" class="block font-semibold mb-1">Priority</label>
        <select id="priority" name="priority" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
        </select>
        @error('priority') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status" class="block font-semibold mb-1">Status</label>
        <select id="status" name="status" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Update Task</button>
</form>
@endsection
