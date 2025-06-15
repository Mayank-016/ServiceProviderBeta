
    {{-- Sidebar and its backdrop (Corrected SVG paths are crucial here) --}}
    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen sidebar-transition transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 text-gray-200 flex flex-col shadow-lg">

            <nav class="space-y-2 flex-grow">
                <h2 class="text-lg font-semibold text-gray-500 mb-4 uppercase tracking-wider">Navigation</h2>
                <ul>
                    <li>
                        {{-- Dashboard Link (Corrected SVG) --}}
                        <a href="{{ url('/dashboard') }}"
                           class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group
                               {{ Request::is('dashboard') ? 'text-blue-400 font-semibold' : '' }}">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        {{-- My Bookings Link (Corrected SVG if needed, using a common grid icon) --}}
                        <a href="{{ url('supplier/bookings') }}"
                           class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group
                               {{ Request::is('supplier/bookings') ? 'text-blue-400 font-semibold' : '' }}">
                            <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM11 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">My Bookings</span>
                        </a>
                    </li>
                    <li>
                        {{-- Manage Services Link (Corrected SVG) --}}
                        <a href="{{ url('supplier/manage_services') }}"
                           class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group
                               {{ Request::is('supplier/manage_services') ? 'text-blue-400 font-semibold' : '' }}">
                            <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l1.293 1.293A1 1 0 017 14.586V18a1 1 0 001 1h4a1 1 0 001-1v-3.414a1 1 0 01.293-.707L16 11.586V8a6 6 0 00-6-6zM12 2.909c.35.159.697.351 1.026.574l-1.026 1.026V2.909z"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Manage Services</span>
                        </a>
                    </li>
                    <li>
                        {{-- Settings Link (Corrected SVG) --}}
                        <a href="{{ url('supplier/settings') }}"
                           class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group
                               {{ Request::is('supplier/settings') ? 'text-blue-400 font-semibold' : '' }}">
                            <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.325-.3-.766-.458-1.207-.458-.44 0-.88.158-1.205.458L3.81 8.814A4.5 4.5 0 005.163 15h9.674a4.5 4.5 0 001.353-6.186L11.49 3.17zM14.5 15a.5.5 0 01.5.5v.5a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5v-.5a.5.5 0 01.5-.5h9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
                        </a>
                    </li>
                    <li>
                        {{-- Profile Link (Corrected SVG) --}}
                        <a href="{{ url('supplier/profile') }}"
                           class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition duration-200 group
                               {{ Request::is('supplier/profile') ? 'text-blue-400 font-semibold' : '' }}">
                            <svg class="shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white transition duration-75" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="mt-auto pt-4 border-t border-gray-700">
                <a href="{{ url('/logout') }}"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-2 px-3 rounded-md transition duration-200 flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden sm:hidden"></div>