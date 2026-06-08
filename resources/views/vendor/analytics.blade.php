<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Analytics - NaturaWed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        .font-sans {
            font-family: 'Inter', sans-serif;
        }

        .bg-brand-cream {
            background-color: #fdfcf7;
        }
    </style>
</head>

<body class="bg-[#f8f9fa] font-sans text-gray-900">
    <div class="min-h-screen flex w-full">

        @include('layouts.vendor_sidebar')

        <main class="flex-1 overflow-y-auto p-12 bg-brand-cream">
            <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs font-bold tracking-[0.2em] text-[#c5a059] uppercase block mb-1">Studio
                        Performance</span>
                    <h1 class="text-4xl font-serif font-bold text-[#2d4a22]">Real-Time Analytics</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('vendor.analytics.pdf') }}"
                        class="px-5 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider flex items-center gap-2 shadow-sm transition-all no-underline cursor-pointer">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Download PDF
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div
                    class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-serif font-bold text-[#2d4a22] mb-1">Revenue Trend</h3>
                        <p class="text-xs text-gray-400 mb-6">Monthly gross revenue mapping over the current year.</p>
                    </div>
                    <div class="h-72 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div
                    class="bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-serif font-bold text-[#2d4a22] mb-1">Booking Statuses</h3>
                        <p class="text-xs text-gray-400 mb-6">Proportion of incoming transaction metrics.</p>
                    </div>
                    <div class="h-64 w-full relative flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <div
                    class="lg:col-span-3 bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-serif font-bold text-[#2d4a22] mb-1">Most Popular Packages</h3>
                        <p class="text-xs text-gray-400 mb-6">Direct volume comparison of booked wedding plans.</p>
                    </div>
                    <div class="h-80 w-full">
                        <canvas id="packageChart"></canvas>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            lucide.createIcons();

            // --- 1. CONFIG LINE CHART (REVENUE TREND) ---
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueLabels) !!}, // Diambil dinamis dari Controller
                    datasets: [{
                        label: 'Gross Income (IDR)',
                        data: {!! json_encode($revenueData) !!}, // Diambil dinamis dari Controller
                        borderColor: '#2d4a22',
                        backgroundColor: 'rgba(45, 74, 34, 0.05)',
                        borderWidth: 3,
                        tension: 0.38,
                        fill: true,
                        pointBackgroundColor: '#c5a059'
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // --- 2. CONFIG BAR CHART (BEST SELLING PACKAGES) ---
            const pkgCtx = document.getElementById('packageChart').getContext('2d');
            new Chart(pkgCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($packageLabels) !!},
                    datasets: [{
                        label: 'Times Booked',
                        data: {!! json_encode($packageData) !!},
                        backgroundColor: '#2d4a22',
                        borderRadius: 12,
                        hoverBackgroundColor: '#c5a059'
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // --- 3. CONFIG DOUGHNUT CHART (BOOKING STATUS) ---
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statusLabels) !!},
                    datasets: [{
                        data: {!! json_encode($statusData) !!},
                        backgroundColor: [
                        '#c5a059',
                        '#ef4444',
                        '#f97316',
                        '#2d4a22',], // Emas, Hijau, Merah
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '70%' }
            });
        });
    </script>
</body>

</html>