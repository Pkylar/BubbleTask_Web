@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="mb-4 flex justify-between items-center max-w-md">
    <form action="{{ route('home') }}" method="GET" class="flex-1 mr-4">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search tasks..."
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
    </form>

    <a href="{{ route('tasks.create') }}"
       class="bg-orange-400 hover:bg-orange-500 text-white font-semibold px-5 py-2 rounded shadow transition-colors duration-300 whitespace-nowrap">
       + Add New Task
    </a>
</div>

<h2 class="text-2xl mb-4 font-semibold text-gray-800">Your Tasks</h2>

@if($tasks->isEmpty())
    <p>No tasks found. <a href="{{ route('tasks.create') }}" class="text-blue-600">Add one?</a></p>
@else
    <ul>
        @foreach($tasks as $task)
            <li id="task-{{ $task->id }}" class="mb-2 p-2 bg-white rounded shadow flex items-center justify-between">
                <button onclick="markDone({{ $task->id }})" title="Mark as Done"
                    class="w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center text-white font-bold mr-4">
                    ✓
                </button>
                <div class="flex-1">
                    <h3 class="font-semibold">{{ $task->title }}</h3>
                    <p>{{ $task->description }}</p>
                    <small>Due: {{ $task->due_date->format('d M Y, H:i') }}</small>
                </div>
                <div class="flex items-center space-x-4">
                    @if($task->priority == 'high')
                        <span class="px-3 py-1 bg-red-500 text-white rounded">High</span>
                    @else
                        <span class="px-3 py-1 bg-green-500 text-white rounded">Low</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
@endif

@push('scripts')
<script>
    function markDone(taskId) {
        fetch(`/tasks/${taskId}/complete`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if(response.ok) {
                document.getElementById(`task-${taskId}`).remove();
            } else {
                alert("Gagal update status task.");
            }
        }).catch(() => alert("Terjadi kesalahan koneksi."));
    }
</script>
@endpush

@endsection
