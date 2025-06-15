<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Apply Inter font family globally */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar for main content area */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e5e7eb; /* bg-gray-100 */
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #9ca3af; /* bg-gray-400 */
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280; /* bg-gray-500 */
        }
        /* For sidebar toggle animation */
        .sidebar-transition {
            transition: transform 0.3s ease-out;
        }
        /* New: Dynamic margin for main content */
        .sidebar-aware-margin {
            margin-left: 0; /* Default for mobile, no margin */
            transition: margin-left 0.3s ease-out, padding-left 0.3s ease-out;
        }
        @media (min-width: 640px) { /* Tailwind's 'sm' breakpoint */
            .sidebar-aware-margin {
                margin-left: 256px; /* 64 units = 256px, matching sidebar width */
            }
        }
        /* Styles for active category button */
        .category-button.active {
            background-color: #3b82f6; /* Tailwind blue-600 */
            color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    @include('common.header')
    @include('common.user_sidebar')
    <div class="flex flex-1">
        <main id="main-content-area" class="flex-1 p-6 custom-scrollbar overflow-y-auto sidebar-aware-margin">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Dashboard Overview</h1>
            <section class="mb-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl shadow-lg p-5 flex flex-col justify-between items-start">
                    <h2 class="text-base font-semibold mb-1 opacity-90">Total Spend</h2>
                    <p id="total-spend" class="text-3xl font-extrabold">$0</p>
                    <span class="text-xs opacity-80 mt-1">as of {{ \Carbon\Carbon::now()->format('F Y') }}</span>
                    <div id="spend-chart-container" class="w-full h-12 bg-blue-600 rounded-lg mt-2 flex items-end justify-around p-1">
                        </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-lg p-5 flex flex-col justify-between items-start">
                    <h2 class="text-base font-semibold mb-1 opacity-90">Total Services Booked</h2>
                    <p id="total-bookings" class="text-3xl font-extrabold">0</p>
                    <span class="text-xs opacity-80 mt-1">in last 30 days</span>
                    <div id="bookings-chart-container" class="w-full h-12 bg-green-600 rounded-lg mt-2 flex items-end justify-around p-1">
                        </div>
                </div>
            </section>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Explore Services</h1>
            <section class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 p-6" id="category-selection-section">
                <h2 class="font-bold text-2xl text-gray-800 mb-4">Select a Category</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($categories as $category)
                        <button
                            class="category-button bg-gray-100 p-5 rounded-lg shadow-sm hover:shadow-md hover:bg-blue-500 hover:text-white transition duration-200 flex items-center justify-center text-center font-semibold text-lg text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-300"
                            data-category-id="{{ $category['id'] }}"
                        >
                            <span>{{ $category['name'] }}</span>
                        </button>
                    @endforeach
                </div>
            </section>
            <div id="services-container">
                @foreach ($categories as $category)
                    <section id="services-{{ $category['id'] }}" class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hidden service-grid-section">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-xl flex justify-between items-center">
                            <h2 class="font-bold text-2xl text-gray-800">{{ $category['name'] }} Services</h2>
                            <button class="text-blue-600 hover:text-blue-800 font-semibold flex items-center" onclick="hideServices()">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Back to Categories
                            </button>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($category['services'] as $service)
                                <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service['name'] }}</h3>
                                    <p class="text-gray-600 text-sm mb-4">{{ $service['description'] ?? 'No description available.' }}</p>
                                    {{-- Link to the new service booking page --}}
                                    <a href="/services/{{ $service['id'] }}/book" class="inline-block self-end mt-auto bg-blue-600 text-white font-semibold py-2.5 px-5 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 shadow-md">
                                        Book Now
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryButtons = document.querySelectorAll('.category-button');
            const allServiceSections = document.querySelectorAll('.service-grid-section');
            const categorySelectionSection = document.getElementById('category-selection-section');

            // Initial state: hide all service sections
            allServiceSections.forEach(section => section.classList.add('hidden'));

            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const categoryId = button.dataset.categoryId;
                    const targetServiceSection = document.getElementById(`services-${categoryId}`);

                    // Hide category selection and show relevant service section
                    categorySelectionSection.classList.add('hidden');
                    allServiceSections.forEach(section => section.classList.add('hidden')); // Hide all first
                    if (targetServiceSection) {
                        targetServiceSection.classList.remove('hidden');
                    }

                    // Manage active state for category buttons
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                });
            });

            window.hideServices = () => {
                allServiceSections.forEach(section => section.classList.add('hidden'));
                categorySelectionSection.classList.remove('hidden');
                categoryButtons.forEach(btn => btn.classList.remove('active')); // Remove active state when going back
            };

            // Dynamic Graph Logic
            const userBookings = @json($userBookings); // Pass PHP variable to JavaScript

            const totalSpendElement = document.getElementById('total-spend');
            const totalBookingsElement = document.getElementById('total-bookings');
            const spendChartContainer = document.getElementById('spend-chart-container');
            const bookingsChartContainer = document.getElementById('bookings-chart-container');

            let totalSpend = 0;
            let totalBookings = 0;
            const spendByWeek = {}; // { 'YYYY-MM-DD': amount } - start of the week
            const bookingsByWeek = {}; // { 'YYYY-MM-DD': count } - start of the week

            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);

            userBookings.forEach(booking => {
                const bookingDate = new Date(booking.booking_date);
                const price = parseFloat(booking.price);

                // Calculate total spend
                totalSpend += price;

                // Calculate total bookings in last 30 days
                if (bookingDate >= thirtyDaysAgo && bookingDate <= today) {
                    totalBookings++;
                }

                // Calculate week start for charting (e.g., Monday of the week)
                const dayOfWeek = bookingDate.getDay(); // 0 = Sunday, 1 = Monday, etc.
                const diff = bookingDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust to Monday
                const weekStartDate = new Date(bookingDate.setDate(diff));
                weekStartDate.setHours(0, 0, 0, 0); // Normalize to start of day
                const weekStartString = weekStartDate.toISOString().split('T')[0]; // YYYY-MM-DD

                spendByWeek[weekStartString] = (spendByWeek[weekStartString] || 0) + price;
                bookingsByWeek[weekStartString] = (bookingsByWeek[weekStartString] || 0) + 1;
            });

            totalSpendElement.textContent = `$${totalSpend.toFixed(2)}`;
            totalBookingsElement.textContent = totalBookings;

            // Generate the last 7 weeks data for charts
            const last7WeeksSpend = [];
            const last7WeeksBookings = [];
            let currentWeek = new Date();
            currentWeek.setHours(0, 0, 0, 0);

            for (let i = 0; i < 7; i++) {
                const dayOfWeek = currentWeek.getDay(); // 0 = Sunday, 1 = Monday, etc.
                const diff = currentWeek.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust to Monday
                const weekStartDate = new Date(currentWeek.setDate(diff));
                weekStartDate.setHours(0, 0, 0, 0);
                const weekStartString = weekStartDate.toISOString().split('T')[0];

                last7WeeksSpend.unshift(spendByWeek[weekStartString] || 0); // Add to the beginning to keep chronological
                last7WeeksBookings.unshift(bookingsByWeek[weekStartString] || 0);

                currentWeek.setDate(currentWeek.getDate() - 7); // Go to previous week
            }

            // Render Spend Chart
            const maxSpend = Math.max(...last7WeeksSpend, 1); // Ensure division by zero is avoided
            spendChartContainer.innerHTML = ''; // Clear existing bars
            last7WeeksSpend.forEach(amount => {
                const height = (amount / maxSpend) * 48; // Max height is 48px (h-12)
                const bar = document.createElement('div');
                bar.className = `w-2.5 bg-blue-400 rounded-sm`;
                bar.style.height = `${height}px`;
                bar.title = `$${amount.toFixed(2)}`; // Tooltip for amount
                spendChartContainer.appendChild(bar);
            });

            // Render Bookings Chart
            const maxBookings = Math.max(...last7WeeksBookings, 1); // Ensure division by zero is avoided
            bookingsChartContainer.innerHTML = ''; // Clear existing bars
            last7WeeksBookings.forEach(count => {
                const height = (count / maxBookings) * 48; // Max height is 48px (h-12)
                const bar = document.createElement('div');
                bar.className = `w-2.5 bg-green-400 rounded-sm`;
                bar.style.height = `${height}px`;
                bar.title = `${count} bookings`; // Tooltip for count
                bookingsChartContainer.appendChild(bar);
            });
        });
    </script>
</body>
</html>