<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard SuperAdmin') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between">
                        <h1 class="text-2xl font-bold mb-4">Schedules Patients Management</h1>
                        <button id="openAddPatientModal"
                            class="bg-sky-500/100 hover:bg-sky-500/50 text-white px-4 py-2 rounded">Add
                            Schedule</button>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No.</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Doctor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Appointment Date & Time</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @if ($patientSchedules->isEmpty())
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No schedules found.
                                    </td>
                                </tr>
                            @else
                                @foreach ($patientSchedules as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $no++ }}.
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->patient->users->nip ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->patient->users->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->schedules->doctor->users->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($schedule->schedules->appointment_date)->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if ($schedule->status == 1)
                                                Scheduled
                                            @elseif ($schedule->status == 2)
                                                Cancelled
                                            @else
                                                Complited
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $schedule->notes ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button data-target="edit-patient-schedule-{{ $schedule->id }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                                            <form action="{{ route('schedules-patient.destroy', $schedule->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition duration-200"
                                                    id="delete-patient">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            <!-- Modal Edit Patient Schedule -->
                            @foreach ($patientSchedules as $schedule)
                                <div id="edit-patient-schedule-{{ $schedule->id }}"
                                    class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-20 hidden">
                                    <div class="bg-white p-6 rounded shadow-lg w-full max-w-xl">
                                        <h2 class="text-xl font-bold mb-4">Edit Patient Schedule</h2>
                                        <form action="{{ route('schedules-patient.update', $schedule->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <label for="edit_patient_id_{{ $schedule->id }}"
                                                    class="block text-sm font-medium text-gray-700">Patient</label>
                                                <select id="edit_patient_id_{{ $schedule->id }}" name="id_patients"
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                                                    required>
                                                    <option value="">Select Patient</option>
                                                    @foreach ($patients as $patient)
                                                        <option value="{{ $patient->id }}"
                                                            {{ $schedule->id_patients == $patient->id ? 'selected' : '' }}>
                                                            {{ $patient->users->name ?? '-' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="doctor_id"
                                                    class="block text-sm font-medium text-gray-700">Doctor</label>
                                                <select id="doctor_id" name="doctor_id"
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                                                    required>
                                                    <option value="">Select Doctor</option>
                                                    @foreach ($doctors as $doctor)
                                                        <option value="{{ $doctor->id }}"
                                                            {{ $schedule->schedules->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                            {{ $doctor->users->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="edit_appointment_date_{{ $schedule->id }}"
                                                    class="block text-sm font-medium text-gray-700">Appointment Date &
                                                    Time</label>
                                                <input type="datetime-local"
                                                    id="edit_appointment_date_{{ $schedule->id }}"
                                                    name="appointment_date"
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                                                    value="{{ \Carbon\Carbon::parse($schedule->schedules->appointment_date)->format('Y-m-d\TH:i') }}"
                                                    readonly>
                                                <small id="edit-doctor-schedule-warning-{{ $schedule->id }}"
                                                    class="text-blue-600 hidden"></small>
                                            </div>
                                            <div class="mb-4">
                                                <label for="notes"
                                                    class="block text-sm font-medium text-gray-700">Notes</label>
                                                <textarea id="notes" name="notes"
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                                                    rows="3" placeholder="Enter notes (optional)">{{ $schedule->notes }}</textarea>
                                            </div>
                                            {{-- <div class="mb-5">
                                                <label for="status"
                                                    class="block text-sm font-medium text-gray-700">Status</label>
                                                <div class="flex items-center space-x-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="status" value="1"
                                                            class="form-radio text-blue-600"
                                                            {{ $schedule->status == 1 ? 'checked' : '' }} required>
                                                        <span class="ml-2">Scheduled</span>
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="status" value="0"
                                                            class="form-radio text-blue-600"
                                                            {{ $schedule->status == 0 ? 'checked' : '' }} required>
                                                        <span class="ml-2">Complited</span>
                                                    </label>
                                                </div>
                                            </div> --}}
                                            <div class="flex justify-end space-x-2">
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                                    Save
                                                </button>
                                                <button type="button"
                                                    class="cancelEditPatient bg-gray-300 text-white px-4 py-2 rounded">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Patient Schedule -->
    <div id="add-patient" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-20 hidden">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-xl">
            <h2 class="text-xl font-bold mb-4">Add Patient Schedule</h2>
            <form action="{{ route('schedules-patient.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                    <select id="patient_id" name="id_patients"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                        required>
                        <option value="">Select Patient</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->users->name ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="add_doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                    <select id="add_doctor_id" name="doctor_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                        required>
                        <option value="">Select Doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->users->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="add_appointment_date" class="block text-sm font-medium text-gray-700">Appointment Date
                        & Time</label>
                    <select id="add_appointment_date" name="appointment_date"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                        required>
                        <option value="-" readonly selected>Select Appointment Date & Time</option>
                        {{-- Options will be dynamically populated via JS --}}
                    </select>
                    <small id="add-doctor-schedule-warning" class="text-blue-600 hidden"></small>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Injected JSON from Laravel
                        const doctorSchedules = @json(
                            \App\Models\Schedules::select('doctor_id', 'appointment_date')->get()->groupBy('doctor_id')->map(function ($schedules) {
                                    return $schedules->pluck('appointment_date')->map(function ($date) {
                                        return \Carbon\Carbon::parse($date)->format('Y-m-d\TH:i');
                                    });
                                }));

                        const doctorSelect = document.getElementById('add_doctor_id');
                        const appointmentSelect = document.getElementById('add_appointment_date');
                        const warning = document.getElementById('add-doctor-schedule-warning');

                        doctorSelect?.addEventListener('change', function() {
                            const doctorId = this.value;
                            // Clear previous options except the first
                            while (appointmentSelect.options.length > 1) {
                                appointmentSelect.remove(1);
                            }
                            warning.classList.add('hidden');

                            if (doctorId && doctorSchedules[doctorId]?.length > 0) {
                                const dates = doctorSchedules[doctorId];
                                dates.slice().sort().forEach(date => {
                                    const option = document.createElement('option');
                                    option.value = date;
                                    option.textContent = date.replace('T', ' ');
                                    appointmentSelect.appendChild(option);
                                });
                                warning.textContent = 'Select available appointment for this doctor.';
                                warning.classList.remove('hidden');
                            }
                        });
                    });
                </script>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring focus:ring-blue-200"
                        rows="3" placeholder="Enter notes (optional)"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Save
                    </button>
                    <button type="button"
                        class="cancelAddPatient bg-gray-300 text-white px-4 py-2 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openAddPatientBtn = document.getElementById('openAddPatientModal');
            const addPatientModal = document.getElementById('add-patient');
            const editPatientModals = document.querySelectorAll('[id^="edit-patient-schedule-"]');
            const cancelAddPatientBtns = document.querySelectorAll('.cancelAddPatient');
            const cancelEditPatientBtns = document.querySelectorAll('.cancelEditPatient');

            openAddPatientBtn?.addEventListener('click', () => {
                addPatientModal.classList.remove('hidden');
                addPatientModal.classList.add('flex');
            });

            editPatientModals.forEach(modal => {
                const openEditBtn = document.querySelector(`[data-target="${modal.id}"]`);
                openEditBtn?.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            cancelAddPatientBtns.forEach(btn => {
                btn?.addEventListener('click', () => {
                    addPatientModal.classList.add('hidden');
                    addPatientModal.classList.remove('flex');
                });
            });

            cancelEditPatientBtns.forEach(btn => {
                btn?.addEventListener('click', () => {
                    const modal = btn.closest('.fixed');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addPatientForm = document.querySelector('#add-patient form');
            addPatientForm?.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Success',
                    text: 'Patient Schedule Created!',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    timer: 2000
                }).then(() => {
                    addPatientForm.submit();
                });
            });
        });
    </script>

    <script>
        // Edit Doctor forms submit with SweetAlert
        const editForms = document.querySelectorAll('[id^="edit-user-"] form');
        editForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Success',
                    text: 'Patient Schedule Updated!',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    timer: 2000
                }).then(() => {
                    form.submit();
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('form button#delete-patient');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The patient schedule has been deleted.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                form.submit();
                            });
                        }
                    });
                });
            });
        });
    </script>

    @if ($patientSchedules->isNotEmpty())
        @foreach ($patientSchedules as $schedule)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Injected JSON from Laravel
                    const doctorSchedules = @json(
                        \App\Models\Schedules::select('doctor_id', 'appointment_date')->get()->groupBy('doctor_id')->map(function ($schedules) {
                                return $schedules->pluck('appointment_date')->map(function ($date) {
                                    return \Carbon\Carbon::parse($date)->format('Y-m-d\TH:i');
                                });
                            }));

                    const doctorSelect = document.getElementById('doctor_id');
                    const appointmentInput = document.getElementById('edit_appointment_date_{{ $schedule->id }}');
                    const warning = document.getElementById('edit-doctor-schedule-warning-{{ $schedule->id }}');

                    doctorSelect?.addEventListener('change', function() {
                        const doctorId = this.value;
                        appointmentInput.value = '';
                        warning.classList.add('hidden');

                        if (doctorId && doctorSchedules[doctorId]?.length > 0) {
                            const dates = doctorSchedules[doctorId];
                            const latest = dates.slice().sort().reverse()[0]; // latest by datetime string

                            if (latest) {
                                appointmentInput.value = latest;
                                const readable = latest.replace('T', ' ');
                                warning.textContent = 'Latest available appointment for this doctor: ' + readable;
                                warning.classList.remove('hidden');
                            }
                        }
                    });
                });
            </script>
        @endforeach
    @endif
</x-app-layout>
