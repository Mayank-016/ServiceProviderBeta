<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
        .sidebar-transition {
            transition: transform 0.3s ease-out;
        }

        .sidebar-aware-margin {
            margin-left: 0;
            transition: margin-left 0.3s ease-out, padding-left 0.3s ease-out;
        }
        @media (min-width: 640px) {
            .sidebar-aware-margin {
                margin-left: 256px;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    @include('common.header')
    @include('common.user_sidebar')

    <div class="flex flex-1">
        <main id="main-content-area" class="flex-1 p-6 custom-scrollbar overflow-y-auto sidebar-aware-margin">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">My Bookings</h1>

            <section class="mb-10">
                @php
                    $bookings = $bookings ?? [];

                    function formatTime($dateTimeString) {
                        return (new DateTime($dateTimeString))->format('h:i A');
                    }
                @endphp

                @if (empty($bookings))
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 text-center">
                        <p class="text-gray-600 text-lg">You don't have any bookings yet.</p>
                        <p class="text-gray-500 text-md mt-2">Explore services and make your first booking!</p>
                        <a href="/dashboard" class="mt-5 inline-block bg-blue-600 text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 shadow-md">
                            Explore Services
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($bookings as $booking)
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 flex flex-col">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $booking['service'] }}</h2>
                                <p class="text-gray-700 mb-1"><strong class="font-semibold">Supplier:</strong> {{ $booking['supplier'] }}</p>
                                <p class="text-gray-700 mb-1"><strong class="font-semibold">Date:</strong> {{ $booking['booking_date'] }}</p>
                                <p class="text-gray-700 mb-1"><strong class="font-semibold">Time:</strong> {{ formatTime($booking['start_time']) }} - {{ formatTime($booking['end_time']) }}</p>
                                <p class="text-gray-700 mb-4"><strong class="font-semibold">Price:</strong> ${{ number_format($booking['price'], 2) }}</p>
                                <div class="mt-auto pt-4 border-t border-gray-200 flex justify-end">
                                    <button class="bg-red-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 transition duration-200 shadow-md">
                                        Cancel Booking
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
