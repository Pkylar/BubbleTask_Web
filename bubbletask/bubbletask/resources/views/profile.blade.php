@extends('layouts.app')

@section('title', 'Priority Tasks')

@section('content')
<h2 class="text-2xl mb-6 font-semibold text-gray-800">Priority Tasks</h2>

@if($tasks->isEmpty())
    <p>No tasks found.</p>
@else
    @php $currentDate = null; @endphp

    <ul>
        @foreach($tasks as $task)
            @php
                $taskDate = $task->due_date->format('l, d F Y');
            @endphp

            @if($taskDate !== $currentDate)
                @php $currentDate = $taskDate; @endphp
                <li class="mt-6 mb-2 text-lg font-bold text-gray-700 border-b border-gray-300 pb-1">
                    {{ $taskDate }}
                </li>
            @endif

            <li class="mb-4 p-4 rounded shadow flex justify-between items-center
                {{ $task->status == 'done' ? 'bg-gray-100 text-gray-500 line-through' : 'bg-white text-gray-900' }}">
                <div>
                    <h3 class="font-semibold">{{ $task->title }}</h3>
                    <p>{{ $task->description }}</p>
                    <small>Due: {{ $task->due_date->format('d M Y, H:i') }}</small>
                </div>
                <div>
                    @if($task->priority == 'high')
                        <span class="px-3 py-1 bg-red-500 text-white rounded">High</span>
                    @else
                        <span class="px-3 py-1 bg-green-500 text-white rounded">Low</span>
                    @endif

                    @if($task->status == 'done')
                        <span class="ml-2 px-3 py-1 bg-gray-300 text-gray-700 rounded font-semibold">Done</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
@endif
@endsection
