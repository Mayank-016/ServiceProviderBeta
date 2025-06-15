<header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800 sticky top-0 z-50 shadow-md">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
        <div class="flex items-center">
            <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                aria-controls="default-sidebar" type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 mr-3">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                    </path>
                </svg>
            </button>
            <a href="#" class="flex items-center">
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{ config('app.name')
                    ?? 'Your App Name' }}</span>
            </a>
        </div>


        <div class="flex items-center space-x-4">
            <span class="text-sm hidden sm:block text-gray-800 dark:text-white">Welcome, {{ $user->name ?? 'Guest'
                }}!</span>
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <!-- CSRF token for security -->
                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-1 px-3 rounded-md transition duration-200">Logout</button>
            </form>

        </div>
    </div>
</header>
<script>
    @auth
        const user = @json(auth()->user());

        // Determine channel name based on role
        let channelName = '';
        const ROLE_USER = 1;
        const ROLE_SUPPLIER = 2;
        if (user.role == ROLE_USER) {
            channelName = `private-user${user.id}`;
        } else if (user.role == ROLE_SUPPLIER) {
            channelName = `private-supplier${user.id}`;
        }
        console.log(channelName);
        // Connect with Laravel Echo
        window.Echo?.private(channelName)
            .notification((notification) => {
                console.log(notification.message);
                showBlueToast(notification.message);
            });

        // Simple blue toast function
        function showBlueToast(message) {
            const toast = document.createElement('div');
            toast.innerText = message;
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.padding = '12px 20px';
            toast.style.backgroundColor = '#007BFF'; // Bootstrap blue
            toast.style.color = 'white';
            toast.style.borderRadius = '4px';
            toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            toast.style.zIndex = 9999;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 4000);
        }
    @endauth
</script>

