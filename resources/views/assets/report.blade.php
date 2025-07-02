<x-app-layout>
    <style>
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #schedule-report, #schedule-report * {
                visibility: visible;
            }
            #schedule-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
    <body class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-white">Doctor's Practice Schedule</h1>
                            <p class="text-blue-100 mt-1">Daily patient appointments report</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex items-center space-x-3">
                            <button onclick="printReport()" class="no-print bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition flex items-center">
                                <i class="fas fa-print mr-2"></i> Print Report
                            </button>
                            <button onclick="filterData()" class="no-print bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition flex items-center">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <!-- Date selector -->
                    <div class="mt-6 flex flex-col md:flex-row items-center space-y-3 md:space-y-0 md:space-x-4">
                        <div class="flex items-center bg-white bg-opacity-20 rounded-lg p-2">
                            <i class="fas fa-calendar-alt text-white mr-2"></i>
                            <input type="date" id="report-date" class="bg-transparent text-white placeholder-blue-200 outline-none border-none">
                        </div>
                        
                        <div class="flex items-center bg-white bg-opacity-20 rounded-lg p-2 w-full md:w-auto">
                            <i class="fas fa-search text-white mr-2"></i>
                            <input type="text" id="search-input" placeholder="Search patient or doctor..." class="bg-transparent text-white placeholder-blue-200 outline-none border-none w-full">
                        </div>
                    </div>
                </div>
                
                <!-- Report content -->
                <div id="schedule-report" class="p-6">
                    <!-- Summary cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Total Appointments</p>
                                    <h3 class="text-2xl font-bold text-blue-800" id="total-appointments">24</h3>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Completed</p>
                                    <h3 class="text-2xl font-bold text-green-800" id="completed-appointments">18</h3>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Pending</p>
                                    <h3 class="text-2xl font-bold text-orange-800" id="pending-appointments">6</h3>
                                </div>
                                <div class="bg-orange-100 p-3 rounded-full">
                                    <i class="fas fa-clock text-orange-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter chips -->
                    <div class="flex flex-wrap gap-2 mb-6 no-print" id="filter-chips"></div>
                    
                    <!-- Table -->
                    <div class="border rounded-xl overflow-hidden custom-scrollbar">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="appointments-body">
                                    <!-- Data will be inserted here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Empty state -->
                    <div id="empty-state" class="hidden py-12 text-center">
                        <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-500">No appointments found</h3>
                        <p class="text-gray-400 mt-1">Try adjusting your search or filter criteria</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter modal -->
        <div id="filter-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Filter Report</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                            <select id="doctor-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Doctors</option>
                                <option value="Dr. Sarah Johnson">Dr. Sarah Johnson</option>
                                <option value="Dr. Michael Chen">Dr. Michael Chen</option>
                                <option value="Dr. Lisa Wong">Dr. Lisa Wong</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" id="start-date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <input type="date" id="end-date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button onclick="resetFilters()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Reset
                        </button>
                        <button onclick="applyFilters()" class="px-4 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Sample data
            const appointments = [
                { id: 1, patient: "John Doe", doctor: "Dr. Sarah Johnson", date: "2023-06-15", time: "09:00 AM", status: "completed", notes: "Regular checkup", statusClass: "bg-green-100 text-green-800" },
                { id: 2, patient: "Jane Smith", doctor: "Dr. Michael Chen", date: "2023-06-15", time: "10:30 AM", status: "completed", notes: "Follow-up visit", statusClass: "bg-green-100 text-green-800" },
                { id: 3, patient: "Robert Johnson", doctor: "Dr. Sarah Johnson", date: "2023-06-15", time: "11:15 AM", status: "pending", notes: "Initial consultation", statusClass: "bg-yellow-100 text-yellow-800" },
                { id: 4, patient: "Maria Garcia", doctor: "Dr. Lisa Wong", date: "2023-06-15", time: "01:30 PM", status: "pending", notes: "Annual physical", statusClass: "bg-yellow-100 text-yellow-800" },
                { id: 5, patient: "David Wilson", doctor: "Dr. Michael Chen", date: "2023-06-15", time: "02:45 PM", status: "completed", notes: "Vaccination", statusClass: "bg-green-100 text-green-800" },
                { id: 6, patient: "Emily Davis", doctor: "Dr. Sarah Johnson", date: "2023-06-15", time: "04:00 PM", status: "canceled", notes: "Patient rescheduled", statusClass: "bg-red-100 text-red-800" },
                { id: 7, patient: "James Brown", doctor: "Dr. Lisa Wong", date: "2023-06-16", time: "08:30 AM", status: "pending", notes: "Blood test results", statusClass: "bg-yellow-100 text-yellow-800" },
                { id: 8, patient: "Patricia Miller", doctor: "Dr. Michael Chen", date: "2023-06-16", time: "10:00 AM", status: "completed", notes: "Prescription refill", statusClass: "bg-green-100 text-green-800" },
            ];

            // DOM elements
            const appointmentsBody = document.getElementById('appointments-body');
            const emptyState = document.getElementById('empty-state');
            const totalAppointmentsElement = document.getElementById('total-appointments');
            const completedAppointmentsElement = document.getElementById('completed-appointments');
            const pendingAppointmentsElement = document.getElementById('pending-appointments');
            const filterChipsContainer = document.getElementById('filter-chips');
            const filterModal = document.getElementById('filter-modal');
            const searchInput = document.getElementById('search-input');
            const reportDate = document.getElementById('report-date');

            // Set today's date as default
            const today = new Date();
            reportDate.value = today.toISOString().split('T')[0];

            // Initial render
            renderAppointments(appointments);

            // Render appointments function
            function renderAppointments(data) {
                appointmentsBody.innerHTML = '';
                
                if (data.length === 0) {
                    emptyState.classList.remove('hidden');
                    return;
                } else {
                    emptyState.classList.add('hidden');
                }
                
                data.forEach((appointment, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 transition';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${appointment.patient}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${appointment.doctor}</div>
                            <div class="text-sm text-gray-500">Cardiology</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${formatDate(appointment.date)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${appointment.time}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${appointment.statusClass}">
                                ${capitalizeFirstLetter(appointment.status)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate">
                            ${appointment.notes}
                        </td>
                    `;
                    appointmentsBody.appendChild(row);
                });
                
                updateSummaryStats(data);
            }

            // Format date
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'short', day: 'numeric', weekday: 'short' };
                return new Date(dateString).toLocaleDateString('en-US', options);
            }

            // Capitalize first letter
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            // Update summary stats
            function updateSummaryStats(data) {
                totalAppointmentsElement.textContent = data.length;
                completedAppointmentsElement.textContent = data.filter(a => a.status === 'completed').length;
                pendingAppointmentsElement.textContent = data.filter(a => a.status === 'pending').length;
            }

            // Filter functions
            function filterData() {
                filterModal.classList.remove('hidden');
            }

            function closeModal() {
                filterModal.classList.add('hidden');
            }

            function resetFilters() {
                document.getElementById('doctor-filter').value = '';
                document.getElementById('status-filter').value = '';
                document.getElementById('start-date').value = '';
                document.getElementById('end-date').value = '';
                
                // Remove all filter chips
                filterChipsContainer.innerHTML = '';
                
                // Reset to original data
                renderAppointments(appointments);
            }

            function applyFilters() {
                const doctorFilter = document.getElementById('doctor-filter').value;
                const statusFilter = document.getElementById('status-filter').value;
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;
                
                let filteredData = [...appointments];
                
                // Apply filters
                if (doctorFilter) {
                    filteredData = filteredData.filter(appointment => appointment.doctor === doctorFilter);
                }
                
                if (statusFilter) {
                    filteredData = filteredData.filter(appointment => appointment.status === statusFilter);
                }
                
                if (startDate && endDate) {
                    filteredData = filteredData.filter(appointment => {
                        const apptDate = new Date(appointment.date);
                        const filterStartDate = new Date(startDate);
                        const filterEndDate = new Date(endDate);
                        return apptDate >= filterStartDate && apptDate <= filterEndDate;
                    });
                }
                
                // Update UI
                renderAppointments(filteredData);
                updateFilterChips(doctorFilter, statusFilter, startDate, endDate);
                closeModal();
            }

            function updateFilterChips(doctor, status, startDate, endDate) {
                filterChipsContainer.innerHTML = '';
                
                if (doctor) {
                    addFilterChip(`Doctor: ${doctor}`, 'doctor');
                }
                
                if (status) {
                    addFilterChip(`Status: ${capitalizeFirstLetter(status)}`, 'status');
                }
                
                if (startDate && endDate) {
                    addFilterChip(`Date: ${formatDate(startDate)} to ${formatDate(endDate)}`, 'date');
                }
            }

            function addFilterChip(text, type) {
                const chip = document.createElement('div');
                chip.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center';
                chip.innerHTML = `
                    ${text}
                    <button onclick="removeFilterChip('${type}')" class="ml-1 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                filterChipsContainer.appendChild(chip);
            }

            function removeFilterChip(type) {
                // This is a simplified version - you'd need to implement proper filter state management
                resetFilters();
            }

            // Search functionality
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                
                if (!searchTerm) {
                    renderAppointments(appointments);
                    return;
                }
                
                const filtered = appointments.filter(appointment => 
                    appointment.patient.toLowerCase().includes(searchTerm) || 
                    appointment.doctor.toLowerCase().includes(searchTerm)
                );
                
                renderAppointments(filtered);
            });

            // Date change handler
            reportDate.addEventListener('change', (e) => {
                const selectedDate = e.target.value;
                const filtered = appointments.filter(appointment => appointment.date === selectedDate);
                renderAppointments(filtered);
            });

            // Print function
            function printReport() {
                window.print();
            }
        </script>
    </body>
</x-app-layout>
