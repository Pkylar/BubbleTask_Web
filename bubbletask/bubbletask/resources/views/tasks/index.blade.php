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
                <button onclick="confirmMarkDone({{ $task->id }}, '{{ addslashes($task->title) }}')" 
                        title="Mark as Done"
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

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Penyelesaian Task</h3>
        <p class="text-gray-600 mb-6">
            Apakah Anda yakin sudah menyelesaikan task "<span id="taskTitle" class="font-semibold"></span>"? 
            Task akan dihapus dari to-do list.
        </p>
        <div class="flex justify-end space-x-4">
            <button onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Batal
            </button>
            <button id="confirmBtn" 
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Ya, Hapus Task
            </button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden z-40">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span>Menghapus task...</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentTaskId = null;

    function confirmMarkDone(taskId, taskTitle) {
        currentTaskId = taskId;
        document.getElementById('taskTitle').textContent = taskTitle;
        document.getElementById('confirmModal').classList.remove('hidden');
        
        // Set up confirm button click
        document.getElementById('confirmBtn').onclick = function() {
            markDone(taskId);
        };
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        currentTaskId = null;
    }

    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    async function markDone(taskId) {
        try {
            // Close modal and show loading
            closeModal();
            showLoading();

            const response = await fetch(`/tasks/${taskId}/complete`, {
                method: 'PATCH', // Changed from 'DELETE' to 'PATCH'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Remove task from DOM with animation
                const taskElement = document.getElementById(`task-${taskId}`);
                if (taskElement) {
                    taskElement.style.transition = 'all 0.3s ease-out';
                    taskElement.style.transform = 'translateX(100%)';
                    taskElement.style.opacity = '0';
                    
                    setTimeout(() => {
                        taskElement.remove();
                        showSuccessMessage('Task berhasil dihapus!');
                    }, 300);
                }
            } else {
                throw new Error(data.message || 'Gagal menghapus task');
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorMessage('Gagal menghapus task: ' + error.message);
        } finally {
            hideLoading();
        }
    }

    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successDiv.textContent = message;
        document.body.appendChild(successDiv);
        
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }

    function showErrorMessage(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // Close modal when clicking outside
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('confirmModal').classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endpush

@endsection