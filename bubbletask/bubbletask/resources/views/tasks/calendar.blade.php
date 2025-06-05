@extends('layouts.app')

@section('title', 'Calendar')

@section('head')
    {{-- Coba beberapa CDN alternatif --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css" rel="stylesheet" />
    
    {{-- Fallback CSS jika CDN gagal --}}
    <style>
        #calendar {
            min-height: 600px;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .manual-calendar {
            font-family: Arial, sans-serif;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            border: 1px solid #ddd;
        }
        
        .calendar-cell {
            min-height: 80px;
            padding: 8px;
            border: 1px solid #eee;
            background: white;
        }
        
        .calendar-cell.header {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .day-number {
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .event-item {
            background: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 12px;
            margin: 2px 0;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl mb-6">Calendar</h2>

    {{-- Debug dan status - hanya tampil jika ada masalah --}}
    <div id="status-container" class="mb-4 p-4 bg-blue-100 rounded" style="display: none;">
        <strong>Loading Status:</strong>
        <div id="loading-status">
            <p>🔄 Checking FullCalendar availability...</p>
        </div>
    </div>

    {{-- Container untuk calendar --}}
    <div id="calendar-wrapper">
        <div id="calendar"></div>
    </div>
</div>

{{-- JavaScript dengan multiple CDN fallback --}}
<script>
// Array CDN alternatif
const cdnSources = [
    'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.js',
    'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js',
    'https://unpkg.com/fullcalendar@6.1.8/main.min.js'
];

let currentCdnIndex = 0;

function updateStatus(message, isError = false) {
    const statusEl = document.getElementById('loading-status');
    const containerEl = document.getElementById('status-container');
    
    // Hanya tampilkan container jika ada masalah
    if (isError) {
        containerEl.style.display = 'block';
    }
    
    statusEl.innerHTML = `<p class="${isError ? 'text-red-600' : 'text-blue-600'}">${message}</p>`;
}

function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

async function tryLoadFullCalendar() {
    // Langsung skip CDN, pakai fallback
    console.log('Using manual calendar fallback');
    return false;
    
    /* Kode CDN - disabled untuk sementara
    for (let i = 0; i < cdnSources.length; i++) {
        try {
            updateStatus(`🔄 Trying CDN ${i + 1}/${cdnSources.length}: ${cdnSources[i]}`);
            await loadScript(cdnSources[i]);
            
            // Cek apakah FullCalendar berhasil dimuat
            if (typeof FullCalendar !== 'undefined') {
                updateStatus('✅ FullCalendar loaded successfully!');
                return true;
            }
        } catch (error) {
            console.warn(`CDN ${i + 1} failed:`, error);
            updateStatus(`❌ CDN ${i + 1} failed, trying next...`);
        }
    }
    
    updateStatus('❌ All CDNs failed. Using fallback calendar.', true);
    return false;
    */
}

function createManualCalendar() {
    const calendarEl = document.getElementById('calendar');
    const events = @json($events ?? []);
    
    // Buat calendar manual yang lebih interaktif
    const now = new Date();
    let currentYear = now.getFullYear();
    let currentMonth = now.getMonth();
    
    function renderCalendar() {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        
        const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        
        let html = `
            <div class="manual-calendar">
                <div class="calendar-header">
                    <button onclick="changeMonth(-1)" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">◀ Prev</button>
                    <h3 class="text-xl font-bold">${monthNames[currentMonth]} ${currentYear}</h3>
                    <button onclick="changeMonth(1)" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Next ▶</button>
                </div>
                <div class="calendar-grid">
        `;
        
        // Header hari
        dayNames.forEach(day => {
            html += `<div class="calendar-cell header">${day}</div>`;
        });
        
        // Hari-hari dalam bulan
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        // Hari kosong di awal
        for (let i = 0; i < firstDay; i++) {
            html += `<div class="calendar-cell"></div>`;
        }
        
        // Hari-hari dalam bulan
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayEvents = events.filter(event => event.start && event.start.startsWith(dateStr));
            
            html += `<div class="calendar-cell">
                <div class="day-number">${day}</div>`;
            
            dayEvents.forEach(event => {
                const eventTitle = event.title || 'Event';
                html += `<div class="event-item" title="${eventTitle}" onclick="showEventDetails('${eventTitle}')">${eventTitle}</div>`;
            });
            
            html += `</div>`;
        }
        
        html += `</div></div>`;
        
        calendarEl.innerHTML = html;
    }
    
    // Fungsi untuk navigasi bulan
    window.changeMonth = function(direction) {
        currentMonth += direction;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        } else if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    }
    
    // Fungsi untuk menampilkan detail event
    window.showEventDetails = function(title) {
        alert('Event: ' + title);
    }
    
    // Render calendar pertama kali
    renderCalendar();
}

function initializeFullCalendar() {
    const calendarEl = document.getElementById('calendar');
    const events = @json($events ?? []);
    
    try {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: events,
            eventClick: function(info) {
                if(info.event.url) {
                    info.jsEvent.preventDefault();
                    window.open(info.event.url, "_blank");
                }
            }
        });
        
        calendar.render();
        updateStatus('✅ Calendar rendered successfully!');
        
    } catch (error) {
        console.error('Error initializing FullCalendar:', error);
        updateStatus('❌ Error initializing FullCalendar, using fallback', true);
        createManualCalendar();
    }
}

// Fungsi untuk navigasi manual calendar
function changeMonth(direction) {
    // Implementasi sederhana - reload halaman dengan parameter bulan
    console.log('Change month:', direction);
}

// Main initialization
document.addEventListener('DOMContentLoaded', async function() {
    console.log('Initializing calendar...');
    
    // Coba load FullCalendar dari CDN
    const fullCalendarLoaded = await tryLoadFullCalendar();
    
    if (fullCalendarLoaded) {
        initializeFullCalendar();
    } else {
        createManualCalendar();
    }
});
</script>
@endsection