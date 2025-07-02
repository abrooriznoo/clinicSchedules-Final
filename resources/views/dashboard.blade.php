<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- DOCTOR SCHEDULES --}}
            @if (Auth::user()->role_id == 2)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Patient Schedules</h1>
                    </div>
                    <div x-data="calendarPatientComponent()" x-init="init()" class="max-w-md mx-auto p-6">
                        {{-- Header Bulan & Navigasi --}}
                        <div class="mb-4 flex items-center justify-between">
                            <button @click="prevMonth"
                                class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold"
                                aria-label="Previous Month">
                                &lt;
                            </button>
                            <div class="font-bold text-lg text-center flex-1">
                                <span x-text="monthYear"></span>
                            </div>
                            <button @click="nextMonth"
                                class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold"
                                aria-label="Next Month">
                                &gt;
                            </button>
                        </div>

                        {{-- Nama Hari --}}
                        <div
                            class="grid grid-cols-7 gap-1 text-center font-semibold text-gray-700 dark:text-gray-200 mb-1">
                            <template x-for="day in days" :key="day">
                                <div x-text="day"></div>
                            </template>
                        </div>

                        {{-- Kalender --}}
                        <div class="grid grid-cols-7 gap-1 text-center">
                            <template x-for="blank in blanks" :key="'b' + blank">
                                <div></div>
                            </template>
                            <template x-for="date in daysInMonth" :key="date">
                                <div class="relative flex flex-col items-center justify-center h-12">
                                    <button class="z-10 text-sm w-8 h-8 rounded-full transition-colors duration-150"
                                        :class="{
                                            'bg-green-500 text-white font-bold': isToday(date),
                                            'hover:bg-green-100 dark:hover:bg-gray-700': !isToday(date)
                                        }"
                                        @click="openModal(date)">
                                        <span x-text="date"></span>
                                    </button>
                                    <template x-if="hasSchedules(date)">
                                        <span
                                            class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-green-500 rounded-full"></span>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Modal --}}
                        <div x-show="showModal" x-transition
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            style="display: none;" @click.outside="showModal = false">
                            <div class="bg-white dark:bg-gray-900 w-full max-w-md p-6 rounded shadow-lg relative">
                                <h2 class="text-xl font-bold mb-4">
                                    Jadwal Tanggal
                                    <span x-text="selectedDateLabel"></span>
                                </h2>
                                <template x-if="schedules.length > 0">
                                    <ul class="space-y-2 max-h-60 overflow-y-auto">
                                        <template x-for="item in schedules" :key="item.id">
                                            <li class="p-3 bg-gray-100 dark:bg-gray-800 rounded shadow text-sm">
                                                <div>
                                                    <strong>Pasien:</strong>
                                                    <span x-text="item.patient_name ?? 'N/A'"></span>
                                                </div>
                                                <div>
                                                    <strong>Dokter:</strong>
                                                    <span x-text="item.doctor_name ?? 'N/A'"></span>
                                                </div>
                                                <div>
                                                    <strong>Status:</strong>
                                                    <span x-text="item.status == 1 ? 'Scheduled' : 'Complited'"></span>
                                                </div>
                                                <div>
                                                    <strong>Tanggal:</strong>
                                                    <span x-text="formatDate(item.appointment_date)"></span>
                                                </div>
                                                <div>
                                                    <strong>Waktu:</strong>
                                                    <span x-text="formatTime(item.appointment_date)"></span>
                                                </div>
                                                <div>
                                                    <strong>Catatan:</strong>
                                                    <span x-text="item.notes || '-'"></span>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="schedules.length === 0">
                                    <p class="text-gray-600 dark:text-gray-300">Tidak ada jadwal pada tanggal
                                        ini.</p>
                                </template>
                                <button @click="showModal = false"
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl font-bold leading-none"
                                    aria-label="Close modal">&times;</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $no = 1;
                        $todayDate = \Carbon\Carbon::today()->format('Y-m-d');

                        // Ambil semua jadwal hari ini
                        $schedules = \App\Models\Schedules::whereDate('appointment_date', $todayDate)->get();

                        // Ambil ID dokter berdasarkan user login
                        $doctorId = \App\Models\Doctors::where(
                            'id_users',
                            \Illuminate\Support\Facades\Auth::id(),
                        )->value('id');

                        // Ambil semua jadwal pasien hari ini berdasarkan dokter login
                        $todayPatientSchedules = \App\Models\PatientsSchedules::with([
                            'patient.users',
                            'schedules.doctor.users',
                        ])
                            ->whereHas('schedules.doctor', function ($query) use ($doctorId) {
                                if ($doctorId) {
                                    $query->where('id', $doctorId);
                                }
                            })
                            ->whereHas('schedules', function ($query) use ($todayDate) {
                                $query->whereDate('appointment_date', $todayDate);
                            })
                            ->get();
                    @endphp
                    @forelse ($todayPatientSchedules as $item)
                        <div
                            class="bg-white dark:bg-gray-800 rounded shadow p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Antrian
                                    #{{ $no++ }}</span>
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold
                                    {{ $item->status == 0 ? 'bg-gray-200 text-gray-700' : ($item->status == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $item->status == 0 ? 'Completed' : ($item->status == 1 ? 'Scheduled' : 'Cancelled') }}
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-600 dark:text-gray-300">Nama Pasien:</span>
                                <span class="text-gray-800 dark:text-gray-100">
                                    {{ $item->patient && $item->patient->users ? $item->patient->users->name : '-' }}
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-600 dark:text-gray-300">Tanggal:</span>
                                <span class="text-gray-800 dark:text-gray-100">
                                    {{ $item->schedules ? \Carbon\Carbon::parse($item->schedules->appointment_date)->format('d-m-Y') : '-' }}
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-600 dark:text-gray-300">Waktu:</span>
                                <span class="text-gray-800 dark:text-gray-100">
                                    {{ $item->schedules ? \Carbon\Carbon::parse($item->schedules->appointment_date)->format('H:i') : '-' }}
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-600 dark:text-gray-300">Catatan:</span>
                                <span class="text-gray-800 dark:text-gray-100">
                                    {{ $item->notes ?? '-' }}
                                </span>
                            </div>
                            @if ($item->status == 1)
                                <div class="mt-3 flex gap-2">
                                    <form method="POST" action="{{ route('schedules.confirm', $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="0">
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-green-500 text-white text-sm font-semibold hover:bg-green-600">
                                            Confirmation
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('schedules.confirm', $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-red-500 text-white text-sm font-semibold hover:bg-red-600">
                                            Cancel
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 dark:text-gray-300 py-8">
                            Tidak ada jadwal pasien hari ini.
                        </div>
                    @endforelse
                </div>
            @elseif (Auth::user()->role_id == 3)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Jadwal Saya</h1>
                    </div>
                    @php
                        $todayDate = \Carbon\Carbon::today()->format('Y-m-d');
                        $userId = Auth::id();
                        // Ambil jadwal pasien yang login (role_id == 3)
                        $mySchedules = \App\Models\PatientsSchedules::with(['schedules.doctor.users'])
                            ->whereHas('patient', function ($q) use ($userId) {
                                $q->where('id_users', $userId);
                            })
                            ->whereHas('schedules')
                            ->orderByDesc('id')
                            ->get();
                    @endphp
                    <div class="p-6">
                        @if ($mySchedules->count())
                            <ul class="space-y-4">
                                @foreach ($mySchedules as $item)
                                    <li class="p-4 bg-gray-100 dark:bg-gray-800 rounded shadow text-sm">
                                        <div>
                                            <strong>Tanggal:</strong>
                                            <span>
                                                {{ $item->schedules ? \Carbon\Carbon::parse($item->schedules->appointment_date)->format('d-m-Y') : '-' }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>Jam:</strong>
                                            <span>
                                                {{ $item->schedules ? \Carbon\Carbon::parse($item->schedules->appointment_date)->format('H:i') : '-' }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>Dokter:</strong>
                                            <span>
                                                {{ $item->schedules && $item->schedules->doctor && $item->schedules->doctor->users ? $item->schedules->doctor->users->name : '-' }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>Status:</strong>
                                            <span>
                                                {{ $item->status == 1 ? 'Scheduled' : ($item->status == 2 ? 'Completed' : 'Cancelled') }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>Catatan:</strong>
                                            <span>{{ $item->notes ?? '-' }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-gray-500 dark:text-gray-300 py-8">
                                Tidak ada jadwal ditemukan.
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Doctor Schedules</h1>
                    </div>
                    <div x-data="calendarComponent()" x-init="init()" class="max-w-md mx-auto p-6">
                        {{-- Header Bulan & Navigasi --}}
                        <div class="mb-4 flex items-center justify-between">
                            <button @click="prevMonth"
                                class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold"
                                aria-label="Previous Month">
                                &lt;
                            </button>
                            <div class="font-bold text-lg text-center flex-1">
                                <span x-text="monthYear"></span>
                            </div>
                            <button @click="nextMonth"
                                class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold"
                                aria-label="Next Month">
                                &gt;
                            </button>
                        </div>

                        {{-- Nama Hari --}}
                        <div
                            class="grid grid-cols-7 gap-1 text-center font-semibold text-gray-700 dark:text-gray-200 mb-1">
                            <template x-for="day in days" :key="day">
                                <div x-text="day"></div>
                            </template>
                        </div>

                        {{-- Kalender --}}
                        <div class="grid grid-cols-7 gap-1 text-center">
                            <template x-for="blank in blanks" :key="'b' + blank">
                                <div></div>
                            </template>
                            <template x-for="date in daysInMonth" :key="date">
                                <div class="relative flex flex-col items-center justify-center h-12">
                                    <button class="z-10 text-sm w-8 h-8 rounded-full transition-colors duration-150"
                                        :class="{
                                            'bg-blue-500 text-white font-bold': isToday(date),
                                            'hover:bg-blue-100 dark:hover:bg-gray-700': !isToday(date)
                                        }"
                                        @click="openModal(date)">
                                        <span x-text="date"></span>
                                    </button>
                                    <template x-if="hasSchedules(date)">
                                        <span
                                            class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-blue-500 rounded-full"></span>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Modal --}}
                        <div x-show="showModal" x-transition
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            style="display: none;" @click.outside="showModal = false">
                            <div class="bg-white dark:bg-gray-900 w-full max-w-md p-6 rounded shadow-lg relative">
                                <h2 class="text-xl font-bold mb-4">
                                    Jadwal Tanggal
                                    <span x-text="selectedDateLabel"></span>
                                </h2>
                                <template x-if="schedules.length > 0">
                                    <ul class="space-y-2 max-h-60 overflow-y-auto">
                                        <template x-for="item in schedules" :key="item.id">
                                            <li class="p-3 bg-gray-100 dark:bg-gray-800 rounded shadow text-sm">
                                                <div>
                                                    <strong>Dokter:</strong>
                                                    <span x-text="item.doctor_name ?? 'N/A'"></span>
                                                </div>
                                                <div>
                                                    <strong>Status:</strong>
                                                    <span
                                                        x-text="item.status == 1 ? 'Available' : 'Unavailable'"></span>
                                                </div>
                                                <div>
                                                    <strong>Tanggal:</strong>
                                                    <span x-text="formatDate(item.appointment_date)"></span>
                                                </div>
                                                <div>
                                                    <strong>Waktu:</strong>
                                                    <span
                                                        x-text="item.appointment_date ? formatTime(item.appointment_date) : '-'"></span>
                                                </div>
                                                <div>
                                                    <strong>Catatan:</strong>
                                                    <span x-text="item.notes || '-'"></span>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="schedules.length === 0">
                                    <p class="text-gray-600 dark:text-gray-300">Tidak ada jadwal pada tanggal ini.</p>
                                </template>
                                <button @click="showModal = false"
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl font-bold leading-none"
                                    aria-label="Close modal">&times;</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    {{-- PATIENT SCHEDULES --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Patient Schedules</h1>
                </div>
                <div x-data="calendarPatientComponent()" x-init="init()" class="max-w-md mx-auto p-6">
                    {{-- Header Bulan & Navigasi --}}
                    <div class="mb-4 flex items-center justify-between">
                        <button @click="prevMonth"
                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold"
                            aria-label="Previous Month">
                            &lt;
                        </button>
                        <div class="font-bold text-lg text-center flex-1">
                            <span x-text="monthYear"></span>
                        </div>
                        <button @click="nextMonth"
                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold"
                            aria-label="Next Month">
                            &gt;
                        </button>
                    </div>

                    {{-- Nama Hari --}}
                    <div
                        class="grid grid-cols-7 gap-1 text-center font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        <template x-for="day in days" :key="day">
                            <div x-text="day"></div>
                        </template>
                    </div>

                    {{-- Kalender --}}
                    <div class="grid grid-cols-7 gap-1 text-center">
                        <template x-for="blank in blanks" :key="'b' + blank">
                            <div></div>
                        </template>
                        <template x-for="date in daysInMonth" :key="date">
                            <div class="relative flex flex-col items-center justify-center h-12">
                                <button class="z-10 text-sm w-8 h-8 rounded-full transition-colors duration-150"
                                    :class="{
                                        'bg-green-500 text-white font-bold': isToday(date),
                                        'hover:bg-green-100 dark:hover:bg-gray-700': !isToday(date)
                                    }"
                                    @click="openModal(date)">
                                    <span x-text="date"></span>
                                </button>
                                <template x-if="hasSchedules(date)">
                                    <span
                                        class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-green-500 rounded-full"></span>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Modal --}}
                    <div x-show="showModal" x-transition
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                        style="display: none;" @click.outside="showModal = false">
                        <div class="bg-white dark:bg-gray-900 w-full max-w-md p-6 rounded shadow-lg relative">
                            <h2 class="text-xl font-bold mb-4">
                                Jadwal Tanggal
                                <span x-text="selectedDateLabel"></span>
                            </h2>
                            <template x-if="schedules.length > 0">
                                <ul class="space-y-2 max-h-60 overflow-y-auto">
                                    <template x-for="item in schedules" :key="item.id">
                                        <li class="p-3 bg-gray-100 dark:bg-gray-800 rounded shadow text-sm">
                                            <div>
                                                <strong>Pasien:</strong>
                                                <span x-text="item.patient_name ?? 'N/A'"></span>
                                            </div>
                                            <div>
                                                <strong>Dokter:</strong>
                                                <span x-text="item.doctor_name ?? 'N/A'"></span>
                                            </div>
                                            <div>
                                                <strong>Status:</strong>
                                                <span x-text="item.status == 1 ? 'Scheduled' : 'Complited'"></span>
                                            </div>
                                            <div>
                                                <strong>Tanggal:</strong>
                                                <span x-text="formatDate(item.appointment_date)"></span>
                                            </div>
                                            <div>
                                                <strong>Waktu:</strong>
                                                <span x-text="formatTime(item.appointment_date)"></span>
                                            </div>
                                            <div>
                                                <strong>Catatan:</strong>
                                                <span x-text="item.notes || '-'"></span>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            <template x-if="schedules.length === 0">
                                <p class="text-gray-600 dark:text-gray-300">Tidak ada jadwal pada tanggal ini.</p>
                            </template>
                            <button @click="showModal = false"
                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl font-bold leading-none"
                                aria-label="Close modal">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    @php
        $today = \Carbon\Carbon::today();
        $allSchedules = \App\Models\Schedules::with(['doctor.users'])
            ->whereBetween('appointment_date', [
                $today->copy()->startOfYear()->subMonths(1),
                $today->copy()->endOfYear()->addMonths(1),
            ])
            ->get()
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->appointment_date)->format('Y-m-d'))
            ->mapWithKeys(function ($items, $date) {
                return [
                    $date => $items
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'status' => $item->status,
                                'appointment_date' => $item->appointment_date,
                                'notes' => $item->notes,
                                'doctor_name' =>
                                    $item->doctor_id && $item->doctor->users ? $item->doctor->users->name : 'N/A',
                            ];
                        })
                        ->toArray(),
                ];
            });

        // Patient Schedules
        $allPatientSchedules = \App\Models\PatientsSchedules::with(['patient.users', 'doctor.users', 'schedules'])
            ->whereHas('schedules', function ($query) use ($today) {
                $query->whereBetween('appointment_date', [
                    $today->copy()->startOfYear()->subMonths(1),
                    $today->copy()->endOfYear()->addMonths(1),
                ]);
            })
            ->get()
            ->groupBy(function ($item) {
                // Ambil appointment_date dari relasi schedules
                return $item->schedules
                    ? \Carbon\Carbon::parse($item->schedules->appointment_date)->format('Y-m-d')
                    : null;
            })
            ->filter(function ($_, $date) {
                return !is_null($date);
            })
            ->mapWithKeys(function ($items, $date) {
                return [
                    $date => $items
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'status' => $item->status,
                                // Ambil appointment_date dari relasi schedules
                                'appointment_date' => $item->schedules ? $item->schedules->appointment_date : null,
                                'notes' => $item->notes,
                                'patient_name' =>
                                    $item->patient && $item->patient->users ? $item->patient->users->name : 'N/A',
                                'doctor_name' =>
                                    $item->schedules && $item->schedules->doctor && $item->schedules->doctor->users
                                        ? $item->schedules->doctor->users->name
                                        : 'N/A',
                            ];
                        })
                        ->toArray(),
                ];
            });
    @endphp

    <script>
        function calendarComponent() {
            return {
                // Data
                currentMonth: {{ $today->month }},
                currentYear: {{ $today->year }},
                today: '{{ $today->format('Y-m-d') }}',
                days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                showModal: false,
                selectedDate: '',
                schedules: [],
                allSchedules: @json($allSchedules),
                // Computed
                get daysInMonth() {
                    return new Date(this.currentYear, this.currentMonth, 0).getDate();
                },
                get firstDayOfWeek() {
                    return new Date(this.currentYear, this.currentMonth - 1, 1).getDay();
                },
                get blanks() {
                    return Array.from({
                        length: this.firstDayOfWeek
                    }, (_, i) => i);
                },
                get monthYear() {
                    const date = new Date(this.currentYear, this.currentMonth - 1);
                    return date.toLocaleString('default', {
                        month: 'long',
                        year: 'numeric'
                    });
                },
                get selectedDateLabel() {
                    if (!this.selectedDate) return '';
                    const date = new Date(this.selectedDate);
                    return date.toLocaleDateString();
                },
                // Methods
                init() {
                    // nothing needed
                },
                isToday(date) {
                    const d = new Date(this.currentYear, this.currentMonth - 1, date);
                    return d.toISOString().slice(0, 10) === this.today;
                },
                hasSchedules(date) {
                    const fullDate = this.formatFullDate(date);
                    return this.allSchedules[fullDate] && this.allSchedules[fullDate].length > 0;
                },
                openModal(date) {
                    this.selectedDate = this.formatFullDate(date);
                    this.schedules = this.allSchedules[this.selectedDate] || [];
                    this.showModal = true;
                },
                formatFullDate(date) {
                    return `${this.currentYear}-${String(this.currentMonth).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                },
                formatDate(datetime) {
                    return new Date(datetime).toLocaleDateString();
                },
                formatTime(datetime) {
                    return new Date(datetime).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },
                prevMonth() {
                    if (this.currentMonth === 1) {
                        this.currentMonth = 12;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },
                nextMonth() {
                    if (this.currentMonth === 12) {
                        this.currentMonth = 1;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                }
            }
        }

        function calendarPatientComponent() {
            return {
                currentMonth: {{ $today->month }},
                currentYear: {{ $today->year }},
                today: '{{ $today->format('Y-m-d') }}',
                days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                showModal: false,
                selectedDate: '',
                schedules: [],
                allSchedules: @json($allPatientSchedules),
                get daysInMonth() {
                    return new Date(this.currentYear, this.currentMonth, 0).getDate();
                },
                get firstDayOfWeek() {
                    return new Date(this.currentYear, this.currentMonth - 1, 1).getDay();
                },
                get blanks() {
                    return Array.from({
                        length: this.firstDayOfWeek
                    }, (_, i) => i);
                },
                get monthYear() {
                    const date = new Date(this.currentYear, this.currentMonth - 1);
                    return date.toLocaleString('default', {
                        month: 'long',
                        year: 'numeric'
                    });
                },
                get selectedDateLabel() {
                    if (!this.selectedDate) return '';
                    const date = new Date(this.selectedDate);
                    return date.toLocaleDateString();
                },
                init() {},
                isToday(date) {
                    const d = new Date(this.currentYear, this.currentMonth - 1, date);
                    return d.toISOString().slice(0, 10) === this.today;
                },
                hasSchedules(date) {
                    const fullDate = this.formatFullDate(date);
                    return this.allSchedules[fullDate] && this.allSchedules[fullDate].length > 0;
                },
                openModal(date) {
                    this.selectedDate = this.formatFullDate(date);
                    this.schedules = this.allSchedules[this.selectedDate] || [];
                    this.showModal = true;
                },
                formatFullDate(date) {
                    return `${this.currentYear}-${String(this.currentMonth).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                },
                formatDate(datetime) {
                    return new Date(datetime).toLocaleDateString();
                },
                formatTime(datetime) {
                    const date = new Date(datetime);
                    return date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                },
                prevMonth() {
                    if (this.currentMonth === 1) {
                        this.currentMonth = 12;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },
                nextMonth() {
                    if (this.currentMonth === 12) {
                        this.currentMonth = 1;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                }
            }
        }
    </script>
</x-app-layout>
