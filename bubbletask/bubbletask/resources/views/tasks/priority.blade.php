@extends('layouts.app')

@section('title', 'Priority Tasks')

@section('content')
<h2 class="text-2xl mb-6 font-semibold text-gray-800">Priority Tasks</h2>

@if($tasks->isEmpty())
    <p>No tasks found.</p>
@else
    @php
        $currentDate = null;
    @endphp

    <ul>
        @foreach($tasks as $task)
            @php
                $taskDate = $task->due_date->format('l, d F Y'); // contoh: Monday, 03 June 2025
            @endphp

            {{-- Tampilkan tanggal baru jika berbeda dari sebelumnya --}}
            @if($taskDate !== $currentDate)
                @php
                    $currentDate = $taskDate;
                @endphp
                <li class="mt-6 mb-2 text-lg font-bold text-gray-700 border-b border-gray-300 pb-1">
                    {{ $taskDate }}
                </li>
            @endif

            <li class="mb-4 p-4 bg-white rounded shadow flex justify-between items-start space-x-4 relative">
                {{-- Tombol Hapus "x" kecil di kiri atas --}}
                <div class="absolute top-2 left-2">
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                        @csrf
                        @method('DELETE') <!-- Menggunakan DELETE karena kita menghapus task -->
                        <button type="submit" class="text-red-600 hover:text-red-800 text-xl">&times;</button> <!-- Tanda x kecil -->
                    </form>
                </div>

                {{-- Wrapper untuk High/Low priority --}}
                <div class="flex-1">
                    <h3 class="font-semibold">{{ $task->title }}</h3>
                    <p>{{ $task->description }}</p>
                    <small>Due: {{ $task->due_date->format('d M Y, H:i') }}</small>
                </div>

                {{-- High/Low Priority yang diletakkan di bawah "x" --}}
                <div class="mt-2">
                    @if($task->priority == 'high')
                        <span class="px-3 py-1 bg-red-500 text-white rounded">High</span>
                    @else
                        <span class="px-3 py-1 bg-green-500 text-white rounded">Low</span>
                    @endif
                </div>

                {{-- Tombol Edit di sebelah kanan --}}
                <div class="mt-2">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                </div>
            </li>
        @endforeach
    </ul>
@endif
@endsection
