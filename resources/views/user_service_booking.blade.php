<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - {{ $serviceName ?? 'Service' }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Sidebar and Main Content Layout adjustments */
        .sidebar-transition {
            transition: transform 0.3s ease-out;
        }

        .sidebar-aware-margin {
            margin-left: 0;
            /* Default for mobile */
            transition: margin-left 0.3s ease-out, padding-left 0.3s ease-out;
        }

        @media (min-width: 640px) {
            .sidebar-aware-margin {
                margin-left: 256px;
                /* Match sidebar width */
            }
        }

        .time-slot {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            text-align: center;
        }

        .time-slot.available {
            background-color: #e0f2fe;
            /* blue-50 */
            color: #1d4ed8;
            /* blue-700 */
            border: 1px solid #93c5fd;
            /* blue-300 */
        }

        .time-slot.available:hover {
            background-color: #bfdbfe;
            /* blue-100 */
        }

        .time-slot.selected {
            background-color: #3b82f6;
            /* blue-600 */
            color: white;
            border: 1px solid #3b82f6;
        }

        .time-slot.booked {
            background-color: #f3f4f6;
            /* gray-100 */
            color: #9ca3af;
            /* gray-400 */
            cursor: not-allowed;
            border: 1px solid #e5e7eb;
            /* gray-200 */
            text-decoration: line-through;
        }

        .time-slot.past {
            background-color: #f3f4f6;
            /* gray-100 */
            color: #d1d5db;
            /* gray-300 */
            cursor: not-allowed;
            border: 1px solid #e5e7eb;
            /* gray-200 */
        }

        /* Modal Styles */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 90%;
            max-width: 500px;
            position: relative;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    @include('common.header')
    @include('common.user_sidebar')

    <div class="flex flex-1">
        <main id="main-content-area" class="flex-1 p-6 custom-scrollbar overflow-y-auto sidebar-aware-margin">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Book Service: {{ $serviceName ?? 'Service' }}</h1>

            <div id="loading" class="text-center text-gray-600 text-lg mb-4">Loading suppliers...</div>
            <div id="error-message" class="text-center text-red-600 text-lg mb-4 hidden"></div>

            <section id="supplier-selection"
                class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 p-6 hidden">
                <h2 class="font-bold text-2xl text-gray-800 mb-4">Choose a Supplier</h2>
                <div id="supplier-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                </div>
                <div id="no-suppliers-message" class="hidden text-gray-600 text-lg">No suppliers available for this
                    service.</div>
            </section>

            <section id="time-slot-selection"
                class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 p-6 hidden">
                <h2 class="font-bold text-2xl text-gray-800 mb-4">Select a Date and Time Slot</h2>
                <button id="back-to-suppliers"
                    class="text-blue-600 hover:text-blue-800 font-semibold mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Suppliers
                </button>

                <div class="mb-4">
                    <label for="date-picker" class="block text-sm font-medium text-gray-700 mb-2">Select Date:</label>
                    <input type="date" id="date-picker"
                        class="form-input rounded-md shadow-sm border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div id="time-slots-container"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                </div>
                <div id="no-slots-message" class="hidden text-gray-600 text-lg mt-4">No available slots for the selected
                    date.</div>

                <button id="confirm-booking-btn"
                    class="mt-8 w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 shadow-md hidden">
                    Confirm Booking
                </button>
            </section>
        </main>
    </div>

    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Confirm Your Booking</h3>
            <div class="space-y-3 text-lg text-gray-700 mb-6">
                <p><strong>Service:</strong> <span id="modal-service-name"></span></p>
                <p><strong>Supplier:</strong> <span id="modal-supplier-name"></span></p>
                <p><strong>Date:</strong> <span id="modal-booking-date"></span></p>
                <p><strong>Time Slot:</strong> <span id="modal-time-slot"></span></p>
                <p><strong>Price:</strong> <span id="modal-price"></span></p>
            </div>
            <div class="flex justify-end space-x-4">
                <button id="cancelModalBtn"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition duration-200">Cancel</button>
                <button id="bookNowBtn"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Book
                    Now</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const serviceId = {{ $serviceId }}; // Passed from Laravel controller
            const serviceName = "{{ $serviceName ?? 'Service' }}"; // Pass service name to JS
            const loadingElement = document.getElementById('loading');
            const errorMessageElement = document.getElementById('error-message');
            const supplierSelectionSection = document.getElementById('supplier-selection');
            const supplierListElement = document.getElementById('supplier-list');
            const noSuppliersMessage = document.getElementById('no-suppliers-message');
            const timeSlotSelectionSection = document.getElementById('time-slot-selection');
            const backToSuppliersButton = document.getElementById('back-to-suppliers');
            const datePicker = document.getElementById('date-picker');
            const timeSlotsContainer = document.getElementById('time-slots-container');
            const noSlotsMessage = document.getElementById('no-slots-message');
            const confirmBookingBtn = document.getElementById('confirm-booking-btn');

            // Modal elements
            const confirmationModal = document.getElementById('confirmationModal');
            const closeModalBtn = document.querySelector('#confirmationModal .close-button');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const bookNowBtn = document.getElementById('bookNowBtn');
            const modalServiceName = document.getElementById('modal-service-name');
            const modalSupplierName = document.getElementById('modal-supplier-name');
            const modalBookingDate = document.getElementById('modal-booking-date');
            const modalTimeSlot = document.getElementById('modal-time-slot');
            const modalPrice = document.getElementById('modal-price');


            let selectedSupplier = null;
            let selectedDate = null;
            let selectedTimeSlotStart = null; // Store just the start time string (e.g., "10:00")
            let selectedTimeSlotEnd = null; // Store just the end time string (e.g., "10:30")
            let suppliersData = []; // To store fetched supplier data

            // Set min and max date for date picker (next 7 days from today)
            const today = new Date();
            const minDate = new Date(today);
            minDate.setDate(today.getDate()); // Start from today
            datePicker.min = minDate.toISOString().split('T')[0];

            const maxDate = new Date(today);
            maxDate.setDate(today.getDate() + 6); // Up to 7 days from today
            datePicker.max = maxDate.toISOString().split('T')[0];


            // Function to fetch suppliers
            async function fetchSuppliers() {
                loadingElement.classList.remove('hidden');
                supplierSelectionSection.classList.add('hidden');
                timeSlotSelectionSection.classList.add('hidden');
                errorMessageElement.classList.add('hidden');
                try {
                    const response = await fetch(`http://localhost:8001/api/suppliers?service_id=${serviceId}`);
                    const result = await response.json();

                    if (result.success && result.data && result.data.length > 0) {
                        suppliersData = result.data;
                        displaySuppliers(suppliersData);
                        supplierSelectionSection.classList.remove('hidden');
                        noSuppliersMessage.classList.add('hidden');
                    } else {
                        noSuppliersMessage.classList.remove('hidden');
                        supplierSelectionSection.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Error fetching suppliers:', error);
                    errorMessageElement.textContent = 'Failed to load suppliers. Please try again.';
                    errorMessageElement.classList.remove('hidden');
                } finally {
                    loadingElement.classList.add('hidden');
                }
            }

            // Function to display suppliers
            function displaySuppliers(suppliers) {
                supplierListElement.innerHTML = '';
                suppliers.forEach(item => {
                    const supplierDiv = document.createElement('div');
                    supplierDiv.classList.add('bg-white', 'p-6', 'border', 'border-gray-200', 'rounded-xl', 'shadow-sm', 'hover:shadow-md', 'transition', 'duration-200', 'flex', 'flex-col', 'justify-between', 'cursor-pointer');
                    supplierDiv.innerHTML = `
                        <h3 class="text-xl font-bold text-gray-900 mb-2">${item.name}</h3>
                        <p class="text-gray-600 text-sm mb-1">Email: ${item.email}</p>
                        <p class="text-gray-700 font-semibold text-lg mb-4">Price: ₹${parseFloat(item.price).toFixed(2)}</p>
                        ${item.provider_schedules && item.provider_schedules.length > 0 ? `
                            <p class="text-gray-600 text-sm">Working Hours: ${item.provider_schedules[0].start_time.substring(0, 5)} - ${item.provider_schedules[0].end_time.substring(0, 5)}</p>
                            <p class="text-gray-600 text-sm">Slot Duration: ${item.provider_schedules[0].slot_duration} minutes</p>
                            <p class="text-gray-600 text-sm">Available on Weekend: ${item.provider_schedules[0].on_weekend ? 'Yes' : 'No'}</p>
                        ` : '<p class="text-gray-600 text-sm">Schedule not set.</p>'}
                        <button class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 select-supplier-btn" data-supplier-email="${item.email}">Select Supplier</button>
                    `;
                    supplierDiv.querySelector('.select-supplier-btn').addEventListener('click', () => {
                        selectedSupplier = item;
                        showTimeSlotSelection();
                    });
                    supplierListElement.appendChild(supplierDiv);
                });
            }

            // Function to show time slot selection view
            function showTimeSlotSelection() {
                supplierSelectionSection.classList.add('hidden');
                timeSlotSelectionSection.classList.remove('hidden');
                selectedDate = null;
                datePicker.value = '';
                timeSlotsContainer.innerHTML = '';
                confirmBookingBtn.classList.add('hidden');
                noSlotsMessage.classList.add('hidden');
            }

            // Function to go back to supplier selection
            backToSuppliersButton.addEventListener('click', () => {
                timeSlotSelectionSection.classList.add('hidden');
                supplierSelectionSection.classList.remove('hidden');
                selectedSupplier = null;
                selectedDate = null;
                selectedTimeSlotStart = null;
                selectedTimeSlotEnd = null;
            });

            // Function to generate time slots
            function generateTimeSlots() {
                timeSlotsContainer.innerHTML = '';
                noSlotsMessage.classList.add('hidden');
                confirmBookingBtn.classList.add('hidden');
                selectedTimeSlotStart = null;
                selectedTimeSlotEnd = null;

                if (!selectedSupplier || !selectedDate) {
                    return;
                }

                const schedule = selectedSupplier.provider_schedules?.[0];
                if (!schedule) {
                    noSlotsMessage.textContent = "Supplier schedule not available.";
                    noSlotsMessage.classList.remove('hidden');
                    return;
                }

                const startParts = schedule.start_time.split(':').map(Number);
                const endParts = schedule.end_time.split(':').map(Number);
                const slotDuration = schedule.slot_duration;
                const onWeekend = schedule.on_weekend;

                const selectedDateObj = new Date(selectedDate);
                selectedDateObj.setHours(0, 0, 0, 0); // Normalize to start of day for comparison

                const dayOfWeek = selectedDateObj.getDay(); // 0 for Sunday, 6 for Saturday

                if ((dayOfWeek === 0 || dayOfWeek === 6) && !onWeekend) {
                    noSlotsMessage.textContent = "Supplier is not available on weekends.";
                    noSlotsMessage.classList.remove('hidden');
                    return;
                }

                const currentDateTime = new Date();
                const isToday = selectedDateObj.toDateString() === today.toDateString();


                let hasAvailableSlots = false;

                const startHour = startParts[0];
                const startMinute = startParts[1];
                const endHour = endParts[0];
                const endMinute = endParts[1];

                let currentSlotTime = new Date(selectedDateObj);
                currentSlotTime.setHours(startHour, startMinute, 0, 0);

                const finalEndTime = new Date(selectedDateObj);
                finalEndTime.setHours(endHour, endMinute, 0, 0);


                while (currentSlotTime.getTime() < finalEndTime.getTime()) {
                    const slotStartTime = new Date(currentSlotTime);
                    const slotEndTime = new Date(currentSlotTime.getTime() + slotDuration * 60 * 1000);

                    // If the current slot ends after the provider's final end time
                    if (slotEndTime.getTime() > finalEndTime.getTime() + 1000) { // Add a small buffer for comparison
                        break;
                    }

                    const startString = `${String(slotStartTime.getHours()).padStart(2, '0')}:${String(slotStartTime.getMinutes()).padStart(2, '0')}`;
                    const endString = `${String(slotEndTime.getHours()).padStart(2, '0')}:${String(slotEndTime.getMinutes()).padStart(2, '0')}`;
                    const timeRangeString = `${startString}-${endString}`;

                    const slotDiv = document.createElement('div');
                    slotDiv.classList.add('time-slot');
                    slotDiv.textContent = timeRangeString;
                    slotDiv.dataset.startTime = startString;
                    slotDiv.dataset.endTime = endString;


                    const isPast = isToday && (slotEndTime.getTime() <= currentDateTime.getTime()); // Check if the slot *ends* in the past on the current day

                    // --- REVISED BOOKING CHECK LOGIC ---
                    const isBooked = selectedSupplier.bookings.some(booking => {
                        // First, filter bookings by date
                        if (booking.booking_date !== selectedDate) {
                            return false;
                        }

                        // Parse the booking start and end times from API (they are UTC)
                        const bookedUtcStartTime = new Date(booking.start_time); // This is a UTC Date object
                        const bookedUtcEndTime = new Date(booking.end_time);   // This is a UTC Date object

                        // To compare correctly with locally generated slots,
                        // we need to create local Date objects from the UTC times
                        // on the *selected date*.
                        const bookedLocalStart = new Date(selectedDate);
                        bookedLocalStart.setHours(bookedUtcStartTime.getUTCHours(), bookedUtcStartTime.getUTCMinutes(), bookedUtcStartTime.getUTCSeconds());

                        const bookedLocalEnd = new Date(selectedDate);
                        bookedLocalEnd.setHours(bookedUtcEndTime.getUTCHours(), bookedUtcEndTime.getUTCMinutes(), bookedUtcEndTime.getUTCSeconds());

                        // Now compare the local slot times with the local representations of the booked times
                        // Check for overlap: [slotStartTime, slotEndTime) overlaps with [bookedLocalStart, bookedLocalEnd)
                        // A and B overlap if A_start < B_end AND A_end > B_start
                        return (slotStartTime.getTime() < bookedLocalEnd.getTime() && slotEndTime.getTime() > bookedLocalStart.getTime());
                    });
                    // --- END REVISED BOOKING CHECK LOGIC ---


                    if (isPast) {
                        slotDiv.classList.add('past');
                    } else if (isBooked) {
                        slotDiv.classList.add('booked');
                    } else {
                        slotDiv.classList.add('available');
                        hasAvailableSlots = true;
                        slotDiv.addEventListener('click', () => {
                            document.querySelectorAll('.time-slot.selected').forEach(s => s.classList.remove('selected'));
                            slotDiv.classList.add('selected');
                            selectedTimeSlotStart = slotDiv.dataset.startTime;
                            selectedTimeSlotEnd = slotDiv.dataset.endTime;
                            confirmBookingBtn.classList.remove('hidden');
                        });
                    }
                    timeSlotsContainer.appendChild(slotDiv);

                    currentSlotTime.setTime(currentSlotTime.getTime() + slotDuration * 60 * 1000); // Move to next slot
                }

                if (!hasAvailableSlots) {
                    noSlotsMessage.textContent = "No available slots for the selected date for this supplier.";
                    noSlotsMessage.classList.remove('hidden');
                }
            }
            
            // Date picker change event
            datePicker.addEventListener('change', () => {
                selectedDate = datePicker.value;
                generateTimeSlots();
            });

            // Confirm booking button (triggers modal)
            confirmBookingBtn.addEventListener('click', () => {
                if (selectedSupplier && selectedDate && selectedTimeSlotStart && selectedTimeSlotEnd) {
                    modalServiceName.textContent = serviceName;
                    modalSupplierName.textContent = selectedSupplier.name;
                    modalBookingDate.textContent = new Date(selectedDate).toLocaleDateString('en-IN', {
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                    });
                    modalTimeSlot.textContent = `${selectedTimeSlotStart} - ${selectedTimeSlotEnd}`;
                    modalPrice.textContent = `₹${parseFloat(selectedSupplier.price).toFixed(2)}`;
                    confirmationModal.style.display = 'flex'; // Show modal
                } else {
                    alert('Please select a supplier, date, and time slot.');
                }
            });

            // Modal Close Button
            closeModalBtn.addEventListener('click', () => {
                confirmationModal.style.display = 'none';
            });

            // Modal Cancel Button
            cancelModalBtn.addEventListener('click', () => {
                confirmationModal.style.display = 'none';
            });

            // Book Now Button (inside modal)
            bookNowBtn.addEventListener('click', async () => {
                if (selectedSupplier && selectedDate && selectedTimeSlotStart && selectedTimeSlotEnd) {
                    try {
                        bookNowBtn.textContent = 'Booking...';
                        bookNowBtn.disabled = true;

                        const response = await fetch('/api/book_service', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                // Include CSRF token if your API is protected by Laravel's web middleware
                                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                service_id: serviceId,
                                supplier_id: selectedSupplier.id, // Ensure your supplier object has an 'id'
                                start_time: `${selectedTimeSlotStart}:00`, // Add seconds for H:i:s format
                                end_time: `${selectedTimeSlotEnd}:00`, // Add seconds for H:i:s format
                                date: selectedDate // YYYY-MM-DD format
                            })
                        });

                        const data = await response.json();
                        console.log(data);
                        if (response.ok && data.success) {
                            alert('Booking successful!');
                            confirmationModal.style.display = 'none';
                            // Optionally, refresh suppliers or go back to dashboard
                            // For simplicity, we'll just reload the page to show updated availability
                            window.location.reload();
                        } else {
                            // Handle API errors
                            let errorMessage = 'Booking failed.';
                            if (data.message) {
                                errorMessage += ' ' + data.message;
                            } else if (data.errors) {
                                // If Laravel validation errors are returned
                                for (const key in data.errors) {
                                    errorMessage += '\n' + data.errors[key].join(', ');
                                }
                            }
                            alert(errorMessage);
                        }
                    } catch (error) {
                        console.error('Error during booking:', error);
                        alert('An error occurred during booking. Please try again.');
                    } finally {
                        bookNowBtn.textContent = 'Book Now';
                        bookNowBtn.disabled = false;
                    }
                }
            });

            // Close modal if user clicks outside of it
            window.addEventListener('click', (event) => {
                if (event.target == confirmationModal) {
                    confirmationModal.style.display = 'none';
                }
            });

            // Initial fetch of suppliers when the page loads
            fetchSuppliers();
        });
    </script>
</body>

</html>