<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth Page</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a202c;
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Adjusted show/hide classes for proper spacing */
        .field-container {
            margin-bottom: 1rem;
            transition: all 0.3s ease-in-out;
        }

        .show-field {
            height: auto;
            opacity: 1;
            overflow: visible;
        }

        .hide-field {
            height: 0;
            opacity: 0;
            overflow: hidden;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        /* Error message specific style */
        .input-error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .input-error-message.active {
            display: block;
        }


        /* Toast styles (Updated for top-right) */
        .toast {
            position: fixed;
            top: 1rem; /* Changed from bottom */
            right: 1rem;
            background-color: #10B981;
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            z-index: 1000;
        }

        .toast.show {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="container mx-auto py-8">
        <div class="flex justify-center">
            <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md">
                <h2 class="mb-6 text-3xl font-bold text-center text-white" id="formTitle">Welcome Back</h2>

                <form id="authForm" method="POST" action="/login">
                    <div id="nameField" class="field-container hide-field">
                        <label for="name" class="block text-gray-300 text-sm font-medium mb-1">Name</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                        <div id="nameError" class="input-error-message"></div>
                    </div>

                    <div id="emailField" class="field-container show-field">
                        <label for="email" class="block text-gray-300 text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                        <div id="emailError" class="input-error-message"></div>
                    </div>

                    <div class="field-container">
                        <label for="password" class="block text-gray-300 text-sm font-medium mb-1">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                        <div id="passwordError" class="input-error-message"></div>
                    </div>

                    <div id="supplierCheck" class="field-container hide-field">
                        <div class="flex items-center">
                            <input type="checkbox" id="isSupplier"
                                class="form-checkbox h-4 w-4 text-blue-600 rounded-md bg-gray-700 border-gray-600 focus:ring-blue-500">
                            <label for="isSupplier" class="ml-2 text-gray-300 text-sm">I am a Supplier</label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200 font-semibold text-lg mt-4">Submit</button>

                    <div class="mt-4 text-center text-gray-400">
                        <span id="toggleText">Don’t have an account?</span>
                        <a href="#" id="toggleMode"
                            class="text-blue-500 hover:text-blue-400 font-medium ml-1">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let isRegistering = false;

        const nameField = document.getElementById('nameField');
        const emailField = document.getElementById('emailField');
        const supplierCheck = document.getElementById('supplierCheck');
        const isSupplierInput = document.getElementById('isSupplier');
        const form = document.getElementById('authForm');
        const toggleLink = document.getElementById('toggleMode');
        const toggleText = document.getElementById('toggleText');
        const formTitle = document.getElementById('formTitle');

        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        function setFieldVisibility(element, show) {
            if (show) {
                element.classList.remove('hide-field');
                element.classList.add('show-field');
            } else {
                element.classList.remove('show-field');
                element.classList.add('hide-field');
            }
        }

        function showToast(message, type = 'success', duration = 3000) {
            const toast = document.createElement('div');
            toast.classList.add('toast');
            if (type === 'error') {
                toast.style.backgroundColor = '#EF4444';
            }
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('show');
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            }, duration);
            return toast; // Return the toast element for potential updates
        }

        function showFieldErrors(errors) {
            clearFieldErrors();

            if (errors.name && nameError) {
                nameError.textContent = errors.name[0];
                nameError.classList.add('active');
            }
            if (errors.email && emailError) {
                emailError.textContent = errors.email[0];
                emailError.classList.add('active');
            }
            if (errors.password && passwordError) {
                passwordError.textContent = errors.password[0];
                passwordError.classList.add('active');
            }
        }

        function clearFieldErrors() {
            nameError.textContent = '';
            nameError.classList.remove('active');
            emailError.textContent = '';
            emailError.classList.remove('active');
            passwordError.textContent = '';
            passwordError.classList.remove('active');
        }

        function updateForm() {
            clearFieldErrors();

            if (isRegistering) {
                formTitle.innerText = 'Create an Account';
                setFieldVisibility(nameField, true);
                setFieldVisibility(emailField, true);
                setFieldVisibility(supplierCheck, true);

                toggleText.innerText = 'Already have an account?';
                toggleLink.innerText = 'Login';
                form.action = '/auth/register';
            } else {
                formTitle.innerText = 'Welcome Back';
                setFieldVisibility(nameField, false);
                setFieldVisibility(emailField, true);
                setFieldVisibility(supplierCheck, false);

                isSupplierInput.checked = false;

                toggleText.innerText = 'Don’t have an account?';
                toggleLink.innerText = 'Register';
                form.action = '/auth/login';
            }
        }

        toggleLink.addEventListener('click', function (e) {
            e.preventDefault();
            isRegistering = !isRegistering;
            updateForm();
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            clearFieldErrors();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const isSupplier = isSupplierInput.checked;

            let endpoint = '';
            let payload = {};

            if (isRegistering) {
                if (!name) { showFieldErrors({ name: ["Name is required."] }); return; }
                if (!email) { showFieldErrors({ email: ["Email is required."] }); return; }
                if (!password) { showFieldErrors({ password: ["Password is required."] }); return; }

                endpoint = 'api/auth/register';
                payload = { name, email, password, is_supplier: isSupplier ? 1 : 0 };
            } else {
                if (!email) { showFieldErrors({ email: ["Email is required."] }); return; }
                if (!password) { showFieldErrors({ password: ["Password is required."] }); return; }

                endpoint = 'api/auth/login';
                payload = { email, password };
            }

            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.textContent = isRegistering ? 'Registering...' : 'Logging In...';
            submitButton.disabled = true;

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const result = await response.json();

                if (response.ok && result.success && result.status === 200 && result.data?.token) {
                    localStorage.setItem('auth_token', result.data.token);
                    // Show success toast for 10 seconds
                    const successToast = showToast(result.message || (isRegistering ? 'Registration successful!' : 'Login successful!'), 'success', 10000);

                    // Add loader to toast and redirect after a short delay
                    setTimeout(() => {
                        successToast.textContent += ' Redirecting...';
                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 1000); // Wait 1 second before redirecting after loader appears
                    }, 500); // Show "Redirecting..." after 0.5 seconds
                } else if (result.status === 422 && result.data) {
                    showFieldErrors(result.data);
                } else {
                    showToast(result.message || 'Authentication failed. Please check your credentials.', 'error');
                }
            } catch (error) {
                console.error('Error during authentication:', error);
                showToast('An unexpected error occurred. Please try again.', 'error');
            } finally {
                submitButton.textContent = 'Submit';
                submitButton.disabled = false;
            }
        });

        updateForm();
    </script>

</body>

</html>