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
                        <h1 class="text-2xl font-bold mb-4">Patients Management</h1>
                        <button id="openAddUserModal"
                            class="bg-sky-500/100 hover:bg-sky-500/50 text-white px-4 py-2 rounded">Add
                            Patient</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No.</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Profile Photo</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Induk Pasien</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIK</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User Name</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Address</th>
                                    {{-- <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created At</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Updated At</th> --}}
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $no = 1;
                                @endphp
                                @if ($patients->isEmpty())
                                    <tr>
                                        <td colspan="12" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No patient found.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($patients as $patient)
                                        <tr class="hover:bg-gray-100 transition-colors">
                                            <td class="px-4 py-4 text-sm text-gray-900 text-center align-middle">
                                                {{ $no++ }}.
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 text-center align-middle">
                                                <div class="flex justify-center">
                                                    @if ($patient->photo)
                                                        <img src="{{ asset('storage/' . $patient->photo) }}"
                                                            alt="Profile Photo"
                                                            class="w-10 h-10 rounded-full object-cover border border-gray-300 shadow">
                                                    @else
                                                        <img src="{{ asset('images/default-profile.png') }}"
                                                            alt="Default Profile Photo"
                                                            class="w-10 h-10 rounded-full object-cover border border-gray-300 shadow">
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle">
                                                <span class="font-semibold">{{ $patient->nip }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle">
                                                {{ Str::limit($patient->nik ?? '-', 16, '') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle">
                                                {{ $patient->name }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle">
                                                <span
                                                    class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-medium">
                                                    {{ $roles->firstWhere('id', $patient->role_id)->name ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle">
                                                {{ $patient->email }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle">
                                                {{ Str::limit($patient->phone ?? '-', 13, '') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900 align-middle max-w-xs break-words truncate"
                                                style="max-width: 200px;" title="{{ $patient->address ?? '-' }}">
                                                {{ Str::limit($patient->address ?? '-', 40) }}
                                            </td>
                                            {{-- <td class="px-4 py-4 text-xs text-gray-500 align-middle">
                                                {{ $patient->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-4 text-xs text-gray-500 align-middle">
                                                {{ $patient->updated_at->format('Y-m-d') }}
                                            </td> --}}
                                            <td class="px-4 py-4 text-sm font-medium align-middle space-x-2">
                                                <button data-target="edit-user-{{ $patient->id }}"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow transition duration-150">Edit</button>
                                                <form action="{{ route('users.destroy', $patient->id) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow transition duration-150"
                                                        id="delete">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                <!-- Modal Edit Role -->
                                @foreach ($patients as $patient)
                                    <div id="edit-user-{{ $patient->id }}"
                                        class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-20 hidden">
                                        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md mx-4 sm:mx-0">
                                            <h2 class="text-lg font-bold mb-4 text-center">Edit Patient</h2>
                                            <form action="{{ route('users.update', $patient->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-4 flex flex-col items-center">
                                                    <img src="{{ $patient->photo ? asset('storage/' . $patient->photo) : asset('images/default-profile.png') }}"
                                                        alt="Profile Photo"
                                                        class="w-20 h-20 rounded-full object-cover border border-gray-300 shadow">
                                                </div>
                                                <div class="mb-4 flex flex-col items-center">
                                                    <input type="file" name="photo" id="photo"
                                                        class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:ring focus:ring-blue-200" />
                                                    <p class="mt-1 text-xs text-gray-500 text-center">Upload a new
                                                        profile photo.</p>
                                                </div>
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div class="mb-2">
                                                        <label for="nik"
                                                            class="block text-xs font-medium text-gray-700">Nomor Induk
                                                            Kependudukan</label>
                                                        <input type="text" id="nik" name="nik"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                                            value="{{ $patient->nik }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="name"
                                                            class="block text-xs font-medium text-gray-700">User
                                                            Name</label>
                                                        <input type="text" id="name" name="name"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                                            value="{{ $patient->name }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="role"
                                                            class="block text-xs font-medium text-gray-700">Role</label>
                                                        <select id="role" name="role_id"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                                            required>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $patient->role_id == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="email"
                                                            class="block text-xs font-medium text-gray-700">Email</label>
                                                        <input type="email" id="email" name="email"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                                            value="{{ $patient->email }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="phone"
                                                            class="block text-xs font-medium text-gray-700">Phone</label>
                                                        <input type="text" id="phone" name="phone"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                                            value="{{ $patient->phone }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="address"
                                                            class="block text-xs font-medium text-gray-700">Address</label>
                                                        <textarea id="address" name="address"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                                            rows="2">{{ $patient->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end space-x-2 mt-3">
                                                    <button type="submit"
                                                        class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">
                                                        Save
                                                    </button>
                                                    <button type="button" id="cancelDelete"
                                                        class="bg-gray-300 text-gray-700 px-3 py-1.5 rounded text-xs hover:bg-gray-400">Cancel</button>
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

        <!-- Modal Tambah Role -->
        <div id="add-user" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-20 hidden">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md mx-4 sm:mx-0">
                <h2 class="text-lg font-bold mb-4 text-center">Add New Patient</h2>
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 flex flex-col items-center">
                        <input type="file" name="photo" id="photo"
                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:ring focus:ring-blue-200" />
                        <p class="mt-1 text-xs text-gray-500 text-center">Upload a new profile photo.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="mb-2">
                            <label for="nik" class="block text-xs font-medium text-gray-700">Nomor Induk
                                Kependudukan</label>
                            <input type="text" id="nik" name="nik"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                placeholder="Enter NIK" required>
                        </div>
                        <div class="mb-2">
                            <label for="name" class="block text-xs font-medium text-gray-700">User Name</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                placeholder="Enter User Name" required>
                        </div>
                        <div class="mb-2">
                            <label for="role" class="block text-xs font-medium text-gray-700">Role</label>
                            <select id="role" name="role_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == 3 ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="email" class="block text-xs font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                placeholder="Enter email address" required>
                        </div>
                        <div class="mb-2">
                            <label for="phone" class="block text-xs font-medium text-gray-700">Phone</label>
                            <input type="number" id="phone" name="phone"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                required>
                        </div>
                        <div class="mb-2">
                            <label for="address" class="block text-xs font-medium text-gray-700">Address</label>
                            <textarea id="adress" name="adress"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                rows="2"></textarea>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-1.5 text-xs focus:ring focus:ring-blue-200"
                                placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">
                            Save
                        </button>
                        <button type="button" id="cancelDelete"
                            class="bg-gray-300 text-gray-700 px-3 py-1.5 rounded text-xs hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const openAddUserBtn = document.getElementById('openAddUserModal');
                const modals = document.querySelectorAll('[id^="edit-user-"]');
                const cancelBtns = document.querySelectorAll('#cancelDelete');

                openAddUserBtn?.addEventListener('click', () => {
                    const addRoleModal = document.getElementById('add-user');
                    addRoleModal.classList.remove('hidden');
                    addRoleModal.classList.add('flex');
                });

                modals.forEach(modal => {
                    const openEditUserBtn = document.querySelector(`[data-target="${modal.id}"]`);
                    openEditUserBtn?.addEventListener('click', () => {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                cancelBtns.forEach(cancelBtn => {
                    cancelBtn?.addEventListener('click', () => {
                        const modal = cancelBtn.closest('.fixed');
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                });

                const cancelBtn = document.getElementById('cancelDelete');

                openBtn?.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });

                cancelBtn?.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addRoleForm = document.querySelector('#add-user form');

                addRoleForm?.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Success',
                        text: 'Patient Created!',
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        timer: 2000 // Show the alert for 1 second
                    }).then(() => {
                        addRoleForm.submit();
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
                        text: 'Patient Updated!',
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
                const deleteButtons = document.querySelectorAll('form button#delete');

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
                                    text: 'The user has been deleted.',
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
</x-app-layout>
