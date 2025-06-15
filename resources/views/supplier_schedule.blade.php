<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Schedule</title>
@vite('resources/css/app.css')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
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

    .sidebar-transition {
        transition: transform 0.3s ease-out;
    }

    .main-content-margin {
        margin-left: 0;
        transition: margin-left 0.3s ease-out;
    }

    @media (min-width: 640px) {
        .main-content-margin {
            margin-left: 256px;
        }
    }

    .time-select-group {
        display: flex;
        gap: 8px;
    }
    .time-select-group select {
        flex: 1;
    }
</style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

@include('common.header', ['user' => $user ?? (object)['name' => 'Supplier']])
@include('common.supplier_sidebar')
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden sm:hidden"></div>

<div class="flex flex-1">
    <main class="flex-1 p-6 main-content-margin custom-scrollbar overflow-y-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900">
                {{ isset($schedule) ? 'Edit Schedule' : 'Create New Schedule' }}
            </h1>
            <a href="/dashboard"
                class="flex items-center text-blue-600 hover:text-blue-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <section class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 max-w-2xl mx-auto">
            <form id="scheduleForm" method="POST">
                @csrf
                @if(isset($schedule))
                @method('PUT')
                @endif

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Availability: <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2">
                        <label
                            class="flex items-center cursor-pointer p-3 bg-gray-50 rounded-lg shadow-sm hover:bg-blue-100 transition duration-200">
                            <input type="checkbox" name="weekend_available" id="weekend_available" value="1"
                                class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{
                                isset($schedule['weekend_available']) && $schedule['weekend_available'] ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700 font-medium">Available on weekends (Saturday &
                                Sunday)</span>
                        </label>
                    </div>
                    <p class="text-gray-600 text-xs mt-1">If unchecked, your schedule will apply to weekdays (Monday-Friday) only.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="start_hour" class="block text-gray-700 text-sm font-bold mb-2">
                            Start Time: <span class="text-red-500">*</span>
                        </label>
                        <div class="time-select-group flex flex-wrap gap-2"> <select id="start_hour" name="start_hour"
                                class="form-select bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1 min-w-[70px]"
                                required> <option value="">Hour</option>
                                @foreach(range(1, 12) as $hour)
                                <option value="{{ sprintf('%02d', $hour) }}" {{ isset($schedule['start_hour']) &&
                                    $schedule['start_hour']==$hour ? 'selected' : '' }}>{{ $hour }}</option>
                                @endforeach
                            </select>
                            <select id="start_minute" name="start_minute"
                                class="form-select bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1 min-w-[70px]"
                                required> <option value="00" {{ isset($schedule['start_minute']) &&
                                    $schedule['start_minute']=='00' ? 'selected' : '' }}>00</option>
                                <option value="15" {{ isset($schedule['start_minute']) &&
                                    $schedule['start_minute']=='15' ? 'selected' : '' }}>15</option>
                                <option value="30" {{ isset($schedule['start_minute']) &&
                                    $schedule['start_minute']=='30' ? 'selected' : '' }}>30</option>
                                <option value="45" {{ isset($schedule['start_minute']) &&
                                    $schedule['start_minute']=='45' ? 'selected' : '' }}>45</option>
                            </select>
                            <select id="start_am_pm" name="start_am_pm"
                                class="form-select bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1 min-w-[70px]"
                                required> <option value="AM" {{ isset($schedule['start_am_pm']) &&
                                    $schedule['start_am_pm']=='AM' ? 'selected' : '' }}>AM</option>
                                <option value="PM" {{ isset($schedule['start_am_pm']) &&
                                    $schedule['start_am_pm']=='PM' ? 'selected' : '' }}>PM</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="end_hour" class="block text-gray-700 text-sm font-bold mb-2">
                            End Time: <span class="text-red-500">*</span>
                        </label>
                        <div class="time-select-group flex flex-wrap gap-2"> <select id="end_hour" name="end_hour"
                                class="form-select bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1 min-w-[70px]"
                                required> <option value="">Hour</option>
                                @foreach(range(1, 12) as $hour)
                                <option value="{{ sprintf('%02d', $hour) }}" {{ isset($schedule['end_hour']) &&
                                    $schedule['end_hour']==$hour ? 'selected' : '' }}>{{ $hour }}</option>
                                @endforeach
                            </select>
                            <select id="end_minute" name="end_minute"
                                class="form-select bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1 min-w-[70px]"
                                required> <option value="00" {{ isset($schedule['end_minute']) &&
                                    $schedule['end_minute']=='00' ? 'selected' : '' }}>00</option>
                                <option value="15" {{ isset($schedule['end_minute']) &&
                                    $schedule['end_minute']=='15' ? 'selected' : '' }}>15</option>
                                <option value="30" {{ isset($schedule['end_minute']) &&
                                    $schedule['end_minute']=='30' ? 'selected' : '' }}>30</option>
                                <option value="45" {{ isset($schedule['end_minute']) &&
                                    $schedule['end_minute']=='45' ? 'selected' : '' }}>45</option>
                            </select>
                            <select id="end_am_pm" name="end_am_pm"
                                class="form-select bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1 min-w-[70px]"
                                required> <option value="AM" {{ isset($schedule['end_am_pm']) && $schedule['end_am_pm']=='AM'
                                    ? 'selected' : '' }}>AM</option>
                                <option value="PM" {{ isset($schedule['end_am_pm']) && $schedule['end_am_pm']=='PM'
                                    ? 'selected' : '' }}>PM</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="slot_duration" class="block text-gray-700 text-sm font-bold mb-2">
                            Slot Duration (minutes): <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="slot_duration"
                            name="slot_duration"
                            value="{{ $schedule['slot_duration'] ?? 30 }}"
                            min="5"
                            step="5"
                            class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-blue-400"
                            required
                        >
                    </div>
                </div>

                <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Preview of Time Slots:</h3>
                    <div id="time-slots-preview"
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 text-sm text-gray-700">
                        <p class="col-span-full text-gray-500">Enter Start Time, End Time, and Slot Duration to see
                            a preview.</p>
                    </div>
                    <div id="validation-messages" class="mt-2 text-red-600 text-sm"></div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-green-600 text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-200 shadow-md">
                        {{ isset($schedule) ? 'Update Schedule' : 'Create Schedule' }}
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>
<script>

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('default-sidebar');
    const sidebarToggle = document.querySelector('header [data-drawer-toggle="default-sidebar"]');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarBackdrop.classList.toggle('hidden');
        });
    }

    sidebarBackdrop.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        sidebarBackdrop.classList.add('hidden');
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 640) {
            sidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        }
    });

    const scheduleForm = document.getElementById('scheduleForm');
    const startHourInput = document.getElementById('start_hour');
    const startMinuteInput = document.getElementById('start_minute');
    const startAmPmInput = document.getElementById('start_am_pm');

    const endHourInput = document.getElementById('end_hour');
    const endMinuteInput = document.getElementById('end_minute');
    const endAmPmInput = document.getElementById('end_am_pm');

    const slotDurationInput = document.getElementById('slot_duration');
    const timeSlotsPreview = document.getElementById('time-slots-preview');
    const validationMessages = document.getElementById('validation-messages');

    function parse12HourToMinutes(hourSelect, minuteSelect, amPmSelect) {
        let hour = parseInt(hourSelect.value);
        const minute = parseInt(minuteSelect.value);
        const ampm = amPmSelect.value;

        if (isNaN(hour) || isNaN(minute) || !ampm) {
            return NaN;
        }

        if (ampm === 'PM' && hour < 12) {
            hour += 12;
        } else if (ampm === 'AM' && hour === 12) {
            hour = 0;
        }
        return hour * 60 + minute;
    }

    function formatMinutesTo24HourTime(totalMinutes) {
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;
    }

    function format24HourTo12HourAmPm(time24h) {
        const [hours24, minutes] = time24h.split(':').map(Number);
        const ampm = hours24 >= 12 ? 'PM' : 'AM';
        const hours12 = hours24 % 12 || 12;
        return `${String(hours12).padStart(2, '0')}:${String(minutes).padStart(2, '0')} ${ampm}`;
    }

    function generateTimeSlots() {
        validationMessages.innerHTML = '';
        timeSlotsPreview.innerHTML = '';

        const startTimeMinutes = parse12HourToMinutes(startHourInput, startMinuteInput, startAmPmInput);
        const endTimeMinutes = parse12HourToMinutes(endHourInput, endMinuteInput, endAmPmInput);
        const slotDuration = parseInt(slotDurationInput.value);

        if (isNaN(startTimeMinutes) || isNaN(endTimeMinutes) || isNaN(slotDuration) || slotDuration <= 0) {
            timeSlotsPreview.innerHTML = '<p class="col-span-full text-gray-500">Please select valid Start Time, End Time, and enter a Slot Duration.</p>';
            return;
        }

        if (startTimeMinutes >= endTimeMinutes) {
            validationMessages.innerHTML = '<p class="text-red-600">End Time must be after Start Time.</p>';
            timeSlotsPreview.innerHTML = '<p class="col-span-full text-gray-500">Enter valid times for preview.</p>';
            return;
        }

        const totalDuration = endTimeMinutes - startTimeMinutes;
        if (slotDuration > totalDuration) {
            validationMessages.innerHTML = '<p class="text-red-600">Slot duration cannot be greater than the total available time.</p>';
            timeSlotsPreview.innerHTML = '<p class="col-span-full text-gray-500">Enter valid duration for preview.</p>';
            return;
        }

        let currentSlotStartMinutes = startTimeMinutes;
        let slotsGenerated = false;

        while (currentSlotStartMinutes < endTimeMinutes) {
            // Calculate remaining time from the current slot start to the end time
            const remainingTime = endTimeMinutes - currentSlotStartMinutes;

            // If remaining time is less than the slot duration,
            // do not create this slot and break the loop.
            if (remainingTime < slotDuration) {
                break;
            }

            // The slot ends exactly after the slotDuration
            const slotEndMinutes = currentSlotStartMinutes + slotDuration;

            const slotStart24h = formatMinutesTo24HourTime(currentSlotStartMinutes);
            const slotEnd24h = formatMinutesTo24HourTime(slotEndMinutes);

            const slotStart12h = format24HourTo12HourAmPm(slotStart24h);
            const slotEnd12h = format24HourTo12HourAmPm(slotEnd24h);

            const slotElement = document.createElement('span');
            slotElement.className = 'bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-center text-sm';
            slotElement.textContent = `${slotStart12h} - ${slotEnd12h}`;
            timeSlotsPreview.appendChild(slotElement);
            slotsGenerated = true;

            currentSlotStartMinutes += slotDuration;
        }

        if (!slotsGenerated) {
            timeSlotsPreview.innerHTML = '<p class="col-span-full text-gray-500">No slots could be generated with the given criteria. Adjust times or duration.</p>';
        }
    }

    [startHourInput, startMinuteInput, startAmPmInput,
     endHourInput, endMinuteInput, endAmPmInput,
     slotDurationInput].forEach(input => {
        if (input) {
            input.addEventListener('change', generateTimeSlots);
            input.addEventListener('input', generateTimeSlots);
        }
    });

    if (startHourInput.value && startMinuteInput.value && startAmPmInput.value &&
        endHourInput.value && endMinuteInput.value && endAmPmInput.value &&
        slotDurationInput.value) {
        generateTimeSlots();
    }

    scheduleForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        validationMessages.innerHTML = '';

        const startTimeMinutes = parse12HourToMinutes(startHourInput, startMinuteInput, startAmPmInput);
        const endTimeMinutes = parse12HourToMinutes(endHourInput, endMinuteInput, endAmPmInput);
        const slotDuration = parseInt(slotDurationInput.value);
        const weekendAvailable = document.getElementById('weekend_available').checked;

        if (isNaN(startTimeMinutes) || isNaN(endTimeMinutes) || isNaN(slotDuration) || slotDuration <= 0) {
            validationMessages.innerHTML = '<p class="text-red-600">Please ensure all required fields are filled and valid.</p>';
            return;
        }

        if (startTimeMinutes >= endTimeMinutes) {
            validationMessages.innerHTML = '<p class="text-red-600">End Time must be after Start Time.</p>';
            return;
        }

        const totalDuration = endTimeMinutes - startTimeMinutes;
        if (slotDuration > totalDuration) {
            validationMessages.innerHTML = '<p class="text-red-600">Slot duration cannot be greater than the total available time.</p>';
            return;
        }

        // Check if any valid slots can be generated at all for submission
        // This prevents submission if the duration is too long for even one slot
        if ((endTimeMinutes - startTimeMinutes) < slotDuration) {
            validationMessages.innerHTML = '<p class="text-red-600">The total time is too short to create at least one slot with the specified duration.</p>';
            return;
        }

        const payload = {
            start_time: formatMinutesTo24HourTime(startTimeMinutes),
            end_time: formatMinutesTo24HourTime(endTimeMinutes),
            slot_duration: slotDuration,
            weekend_available: weekendAvailable,
        };

        const url = '/api/supplier/create_schedule';
        const method = 'POST';

        // Retrieve CSRF token from the meta tag
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenElement ? csrfTokenElement.content : '';


        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Include CSRF token
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (response.ok) {
                window.location.href = '/dashboard';
            } else {
                let errorMessage = 'Failed to save schedule.';
                if (data.message) {
                    errorMessage = data.message;
                } else if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join('\n');
                }
                alert('Error: ' + errorMessage);
            }
        } catch (error) {
            console.error('Network or API error:', error);
            alert('An unexpected error occurred. Please try again.');
        }
    });
});

</script>

</body>

</html>