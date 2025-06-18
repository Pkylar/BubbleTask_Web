<header class="px-6 py-4 flex justify-between items-center border-b border-gray-200" style="background-color: #F9E2F2;">
    <div class="flex items-center space-x-4">
        @auth
            {{-- Foto Profil --}}
            <div class="relative">
                <img src="{{ auth()->user()->profile_picture ?: asset('images/default-profile.png') }}" 
                     alt="Profile Picture" 
                     class="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     onload="console.log('Avatar loaded successfully');">
                
                {{-- Fallback jika gambar tidak bisa dimuat --}}
                <div class="w-20 h-20 rounded-full bg-gray-400 text-white flex items-center justify-content font-bold text-lg border-2 border-gray-300" 
                     style="display: none;">
                    {{ auth()->user()->initials ?? strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
            
            <div>
                <h1 class="text-xl font-semibold text-gray-900" id="greeting"></h1>
                <p class="text-gray-600 text-sm">{{ auth()->user()->email ?? '' }}</p>
                
                <!-- {{-- Debug info (hapus setelah berhasil) --}}
                <p class="text-xs text-blue-600 mt-1">
                    Debug: {{ auth()->user()->profile_picture ? 'Has avatar URL' : 'No avatar URL' }}
                </p> -->
            </div>
        @else
            {{-- Jika belum login --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Welcome, Guest!</h1>
                <p class="text-gray-600 text-sm">Please login to access your tasks</p>
            </div>
        @endauth
    </div>

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline font-semibold">Logout</button>
        </form>
    @endauth
</header>

{{-- Script untuk menyesuaikan sapaan berdasarkan waktu --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    @auth
        const currentHour = new Date().getHours();  // Mengambil jam lokal pengguna
        let greetingMessage;

        // Menentukan sapaan berdasarkan waktu
        if (currentHour >= 5 && currentHour < 12) {
            greetingMessage = 'Good Morning';
        } else if (currentHour >= 12 && currentHour < 18) {
            greetingMessage = 'Good Afternoon';
        } else if (currentHour >= 18 && currentHour < 21) {
            greetingMessage = 'Good Evening';
        } else {
            greetingMessage = 'Good Night';
        }

        document.getElementById('greeting').textContent = `${greetingMessage}, {{ auth()->user()->name ?? 'User' }}!`;
        console.log('Profile picture URL:', '{{ auth()->user()->profile_picture }}');
        console.log('User name:', '{{ auth()->user()->name }}');
        console.log('User initials:', '{{ auth()->user()->initials ?? "N/A" }}');
    @endauth
});
</script>
