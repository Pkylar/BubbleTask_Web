@extends('layouts.app')

@section('title', 'Add Task')

{{-- Tambah CDN CSS Flatpickr --}}
@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<h2 class="text-2xl mb-4">Add New Task</h2>

<form action="{{ route('tasks.store') }}" method="POST" class="space-y-4 max-w-md">
    @csrf
    <div>
        <label class="block font-semibold mb-1" for="title">Title</label>
        <input type="text" id="title" name="title" required
               class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('title') }}">
        @error('title')
        <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1" for="description">Description</label>
        <textarea id="description" name="description"
                  class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description') }}</textarea>
    </div>

    <div>
        <label class="block font-semibold mb-1" for="due_date">Due Date & Time</label>
        <input
            type="text"
            id="due_date"
            name="due_date"
            required
            class="w-full border border-gray-300 rounded px-3 py-2"
            value="{{ old('due_date') }}"
            placeholder="Select date and time"
        >
        @error('due_date')
        <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1 mb-2">Priority</label>
        <div class="flex space-x-4">
            <button type="button" id="btnHigh" class="px-6 py-2 rounded font-semibold text-white" style="background-color: #E45E5E;">
                High
            </button>
            <button type="button" id="btnLow" class="px-6 py-2 rounded font-semibold text-white" style="background-color: #6FD886;">
                Low
            </button>
        </div>
        <input type="hidden" id="priority" name="priority" value="{{ old('priority', 'low') }}">
        @error('priority')
        <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Task</button>
</form>

{{-- Import JS Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#due_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        defaultDate: "{{ old('due_date') ?? '' }}",
        minDate: "today",  // Menetapkan batas minimum untuk memilih tanggal adalah hari ini
    });

    const btnHigh = document.getElementById('btnHigh');
    const btnLow = document.getElementById('btnLow');
    const inputPriority = document.getElementById('priority');

    function setActive(button) {
        btnHigh.classList.remove('ring-4', 'ring-red-500');
        btnLow.classList.remove('ring-4', 'ring-green-500');

        if(button === 'high') {
            btnHigh.classList.add('ring-4', 'ring-red-500');
            inputPriority.value = 'high';
        } else {
            btnLow.classList.add('ring-4', 'ring-green-500');
            inputPriority.value = 'low';
        }
    }

    // Inisialisasi pilihan sesuai old value
    setActive(inputPriority.value);

    btnHigh.addEventListener('click', () => setActive('high'));
    btnLow.addEventListener('click', () => setActive('low'));
</script>
@endsection
