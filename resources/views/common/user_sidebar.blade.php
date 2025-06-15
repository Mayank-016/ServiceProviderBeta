<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen sidebar-transition transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 text-gray-200 flex flex-col shadow-lg">

        <nav class="space-y-2 flex-grow">
            <h2 class="text-lg font-semibold text-gray-500 mb-4 uppercase tracking-wider">Navigation</h2>
            <ul>
                <li>
                    <a href="{{route('dashboard')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group {{ request()->routeIs('dashboard') ? 'text-blue-400 font-semibold bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75 {{ request()->routeIs('dashboard') ? 'text-white' : '' }}" aria-hidden="true" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('my_bookings')}}" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group {{ request()->routeIs('my_bookings') ? 'text-blue-400 font-semibold bg-gray-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75 {{ request()->routeIs('my_bookings') ? 'text-white' : '' }}" aria-hidden="true" fill="currentColor" viewBox="0 0 18 18">
                            <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">My Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group">
                        <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group">
                        <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 18">
                            <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="mt-auto pt-4 border-t border-gray-700">
            <a href="{{ url('/logout') }}" class="w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 flex items-center justify-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </a>
        </div>
    </div>
</aside>

<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden sm:hidden"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('default-sidebar');
        const sidebarToggle = document.querySelector('header [data-drawer-toggle="default-sidebar"]');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const mainContent = document.getElementById('main-content-area'); // Get the main content area by its new ID
        const mainHeader = document.getElementById('main-header'); // Get the header

        // Function to update main content margin and header padding
        function updateLayout() {
            if (window.innerWidth >= 640) { // 'sm' breakpoint and above
                sidebar.classList.remove('-translate-x-full'); // Ensure sidebar is visible
                sidebarBackdrop.classList.add('hidden'); // Hide backdrop
                mainContent.classList.add('sidebar-aware-margin'); // Apply margin to main content
                mainHeader.classList.add('sm:ml-64'); // Apply margin to header for desktop alignment
            } else { // Below 'sm' breakpoint (mobile)
                mainHeader.classList.remove('sm:ml-64'); // Remove desktop margin from header

                if (sidebar.classList.contains('-translate-x-full')) {
                    // Sidebar is closed on mobile
                    mainContent.classList.remove('sidebar-aware-margin');
                    mainHeader.classList.remove('pl-64'); // Ensure mobile header padding is removed
                } else {
                    // Sidebar is open on mobile
                    mainContent.classList.add('sidebar-aware-margin');
                    mainHeader?.classList.add('pl-64'); // Add padding to header on mobile to prevent overlap
                }
            }
        }

        // Initial layout update on page load
        updateLayout();

        // Toggle sidebar and update layout
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebarBackdrop.classList.toggle('hidden');
                updateLayout(); // Update layout after toggling
            });
        }

        // Hide sidebar and update layout when backdrop is clicked
        sidebarBackdrop.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
            updateLayout(); // Update layout after hiding sidebar
        });

        // Update layout on window resize
        window.addEventListener('resize', updateLayout);
    });
</script>