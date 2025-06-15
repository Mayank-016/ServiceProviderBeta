<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Supplier</title>
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

        .main-content-margin {
            margin-left: 0;
            transition: margin-left 0.3s ease-out;
        }

        @media (min-width: 640px) {
            .main-content-margin {
                margin-left: 256px;
            }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .toast.show {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    @include('common.header')
    @include('common.supplier_sidebar')

    <div class="flex flex-1">
        <main class="flex-1 p-6 main-content-margin custom-scrollbar overflow-y-auto">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Manage Your Services</h1>

            <section class="mb-10 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                @php
                $allServicesCategorized = $allServicesCategorized ?? [];
                $supplierServices = $supplierServices ?? [];
                $supplierServicesMap = collect($supplierServices)->keyBy('id');
                @endphp

                <form id="manageServicesForm" action="/api/supplier/manage_services" method="POST">
                    @csrf
                    @forelse ($allServicesCategorized as $category)
                    <div class="mb-8 p-6 bg-gray-50 rounded-xl shadow-md border border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 border-gray-300">{{
                            $category['name'] }} Services</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($category['services'] as $service)
                            @php
                            $isSupplierService = $supplierServicesMap->has($service['id']);
                            $supplierServiceDetails = $isSupplierService ? $supplierServicesMap->get($service['id']) :
                            null;
                            @endphp
                            <div class="bg-white p-5 border border-gray-200 rounded-xl shadow-sm flex flex-col">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $service['name'] }}</h3>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer service-checkbox"
                                            data-service-id="{{ $service['id'] }}" {{ $isSupplierService ? 'checked'
                                            : '' }}>
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ms-3 text-sm font-medium text-gray-900">Offer</span>
                                    </label>
                                </div>
                                <p class="text-gray-600 text-sm mb-4">{{ $service['description'] ?? 'No description
                                    available.' }}</p>

                                <div class="mt-auto pt-4 border-t border-gray-200 service-details">

                                    <div class="mb-3">
                                        <label for="price_{{ $service['id'] }}"
                                            class="block text-sm font-medium text-gray-700 mb-1">Price per Slot
                                            ($)</label>
                                            <input type="number" id="price_{{ $service['id'] }}"
                                            name="services[{{ $service['id'] }}][price]"
                                            class="price-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm {{ $isSupplierService ? '' : 'bg-gray-100' }}"
                                            placeholder="e.g., 50.00" step="0.01"
                                            value="{{ $supplierServiceDetails['pivot']['price'] ?? '' }}"
                                            {{ $isSupplierService ? '' : 'disabled' }}>
                                        
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-600 text-lg mb-4">No services are currently available on the platform.</p>
                    </div>
                    @endforelse

                    <div class="mt-10 text-center">
                        <button type="submit"
                            class="bg-blue-600 text-white font-semibold py-3 px-8 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 shadow-lg">
                            Save Changes
                        </button>
                    </div>

                    <input type="hidden" name="selected_services" id="selectedServicesInput">
                    <input type="hidden" name="removed_services" id="removedServicesInput">
                </form>
            </section>
        </main>
    </div>

    <div id="toast-container" class="toast-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const manageServicesForm = document.getElementById('manageServicesForm');
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const selectedServicesInput = document.getElementById('selectedServicesInput');
            const removedServicesInput = document.getElementById('removedServicesInput');
            const toastContainer = document.getElementById('toast-container');

            const initialSupplierServiceIds = new Set(
                @json(collect($supplierServices)->pluck('id')->toArray())
            );

            serviceCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', (event) => {
                    const serviceDetailsDiv = event.target.closest('.flex-col').querySelector('.service-details');
                    const priceInput = serviceDetailsDiv.querySelector('.price-input');

                    if (event.target.checked) {
                        priceInput.removeAttribute('disabled');
                        if (!priceInput.value) {
                             priceInput.value = '';
                        }
                    } else {
                        priceInput.setAttribute('disabled', 'disabled');
                        priceInput.value = '';
                    }
                });
            });


            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.classList.add('toast');
                if (type === 'success') {
                    toast.style.backgroundColor = '#4CAF50';
                } else if (type === 'error') {
                    toast.style.backgroundColor = '#f44336';
                }
                toast.textContent = message;
                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('show');
                    toast.addEventListener('transitionend', () => toast.remove());
                }, 3000);
            }

            manageServicesForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const selectedServices = [];
                const removedServices = [];
                const currentSelectedServiceIds = new Set();

                let validationFailed = false;

                serviceCheckboxes.forEach(checkbox => {
                    const serviceId = parseInt(checkbox.dataset.serviceId);
                    const serviceDetailsDiv = checkbox.closest('.flex-col').querySelector('.service-details');
                    const priceInput = serviceDetailsDiv.querySelector('.price-input');

                    if (checkbox.checked) {
                        const price = parseFloat(priceInput.value);

                        if (isNaN(price) || price <= 0) {
                            showToast(`Please enter a valid positive price for ${checkbox.closest('.flex-col').querySelector('h3').textContent}.`, 'error');
                            priceInput.focus();
                            validationFailed = true;
                            return;
                        }

                        selectedServices.push({
                            service_id: serviceId,
                            price: price
                        });
                        currentSelectedServiceIds.add(serviceId);
                    }
                });

                if (validationFailed) {
                    return;
                }

                initialSupplierServiceIds.forEach(id => {
                    if (!currentSelectedServiceIds.has(id)) {
                        removedServices.push({service_id: id});
                    }
                });

                selectedServicesInput.value = JSON.stringify(selectedServices);
                removedServicesInput.value = JSON.stringify(removedServices);

                try {
                    const response = await fetch(manageServicesForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            selected_services: selectedServices,
                            removed_services: removedServices
                        })
                    });

                    const result = await response.json();
                    console.log(result);
                    if (response.ok && result.success) {
                        showToast(result.message || "Services Updated Successfully", 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(result.message || "An error occurred while updating services.", 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast("Network error or server unavailable.", 'error');
                }
            });
        });
    </script>
</body>

</html>