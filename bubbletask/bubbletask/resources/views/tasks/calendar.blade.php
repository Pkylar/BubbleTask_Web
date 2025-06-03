@extends('layouts.app')

@section('title', 'Calendar')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
@endsection

@section('content')
<h2 class="text-2xl mb-6">Calendar</h2>

{{-- Debug: cek data events --}}
<pre>{{ json_encode($events, JSON_PRETTY_PRINT) }}</pre>

<div id="calendar"></div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: @json($events),
        eventClick: function(info) {
            if(info.event.url) {
                info.jsEvent.preventDefault();
                window.open(info.event.url, "_blank");
            }
        }
    });

    calendar.render();
});
</script>

<style>
    #calendar {
        max-width: 900px;
        min-height: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
    }
</style>
@endsection
