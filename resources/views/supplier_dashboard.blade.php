<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
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

        /* Adjust main content margin based on sidebar state */
        .main-content-margin {
            margin-left: 0; /* Default for mobile */
            transition: margin-left 0.3s ease-out;
        }
        @media (min-width: 640px) { /* Tailwind's 'sm' breakpoint */
            .main-content-margin {
                margin-left: 256px; /* 64 units = 256px, matching sidebar width */
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    @include('common.header')
    {{-- Assuming common.supplier_sidebar now contains correct SVG paths --}}
    @include('common.supplier_sidebar')


    <div class="flex flex-1">
        <main class="flex-1 p-6 main-content-margin custom-scrollbar overflow-y-auto">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Supplier Dashboard</h1>

            {{-- Supplier Specific Stats Section --}}
            <section class="mb-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl shadow-lg p-5 flex flex-col justify-between items-start">
                    <h2 class="text-base font-semibold mb-1 opacity-90">Total Bookings</h2>
                    <p class="text-3xl font-extrabold">25</p>
                    <span class="text-xs opacity-80 mt-1">in last 30 days</span>
                    <div class="w-full h-12 bg-blue-600 rounded-lg mt-2 flex items-end justify-around p-1">
                        <div class="w-2.5 h-6 bg-blue-400 rounded-sm" style="height: 24px;"></div>
                        <div class="w-2.5 h-9 bg-blue-400 rounded-sm" style="height: 36px;"></div>
                        <div class="w-2.5 h-7 bg-blue-400 rounded-sm" style="height: 28px;"></div>
                        <div class="w-2.5 h-8 bg-blue-400 rounded-sm" style="height: 32px;"></div>
                        <div class="w-2.5 h-12 bg-blue-400 rounded-sm" style="height: 48px;"></div>
                        <div class="w-2.5 h-10 bg-blue-400 rounded-sm" style="height: 40px;"></div>
                        <div class="w-2.5 h-7 bg-blue-400 rounded-sm" style="height: 28px;"></div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl shadow-lg p-5 flex flex-col justify-between items-start">
                    <h2 class="text-base font-semibold mb-1 opacity-90">Revenue Earned</h2>
                    <p class="text-3xl font-extrabold">$2,100</p>
                    <span class="text-xs opacity-80 mt-1">this month</span>
                    <div class="w-full h-12 bg-green-600 rounded-lg mt-2 flex items-end justify-around p-1">
                        <div class="w-2.5 h-5 bg-green-400 rounded-sm" style="height: 20px;"></div>
                        <div class="w-2.5 h-9 bg-green-400 rounded-sm" style="height: 36px;"></div>
                        <div class="w-2.5 h-6 bg-green-400 rounded-sm" style="height: 24px;"></div>
                        <div class="w-2.5 h-10 bg-green-400 rounded-sm" style="height: 40px;"></div>
                        <div class="w-2.5 h-8 bg-green-400 rounded-sm" style="height: 32px;"></div>
                        <div class="w-2.5 h-11 bg-green-400 rounded-sm" style="height: 44px;"></div>
                        <div class="w-2.5 h-12 bg-green-400 rounded-sm" style="height: 48px;"></div>
                    </div>
                </div>
            </section>

            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Your Available Services</h1>

            <section class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                @php
                    // Ensure $availableServices is always an array
                    $availableServices = $availableServices ?? [];
                @endphp

                @if (empty($availableServices))
                    <div class="text-center py-10">
                        <p class="text-gray-600 text-lg mb-4">You currently don't offer any services.</p>
                        {{-- Make sure 'supplier.manage.service' route is defined in web.php --}}
                        <a href="{{ route('supplier.manage.service') }}" class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 shadow-md">
                            Add Your First Service
                        </a>
                    </div>
                @else
                    <h2 class="font-bold text-2xl text-gray-800 mb-4">Services You Offer</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($availableServices as $service)
                            <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service['name'] }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ $service['description'] ?? 'No description available.' }}</p>
                                @if(isset($service['category_name']))
                                    <p class="text-blue-600 text-xs font-medium mb-3">Category: {{ $service['category_name'] }}</p>
                                @endif
                                {{-- Display the price from the pivot data --}}
                                @if(isset($service['pivot']['price']))
                                    <p class="text-gray-800 font-semibold mb-3">Price: ${{ number_format($service['pivot']['price'], 2) }}</p>
                                @elseif(isset($service['price'])) {{-- Fallback if price is flattened into service directly --}}
                                     <p class="text-gray-800 font-semibold mb-3">Price: ${{ number_format($service['price'], 2) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('default-sidebar');
            // Ensure sidebarToggle is correctly linked to your header's toggle button
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
                if (window.innerWidth >= 640) { // Tailwind's 'sm' breakpoint
                    sidebar.classList.remove('-translate-x-full');
                    sidebarBackdrop.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>