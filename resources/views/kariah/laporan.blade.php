@extends ('layouts.app')

@section ('content')

     <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ahli Khairat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .chart-container {
            position: relative;
            height: 20rem;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex justify-center">
        <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="bg-primary-500 text-white p-2 rounded-lg mr-3">
                        <i class="fas fa-mosque text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Ahli Khairat</h1>
                </div>
            </div>
        </div>
    </div>

     <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="stats-card bg-white rounded-2xl shadow-lg border border-gray-100 p-8 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-500 shadow-inner">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Ahli</p>
                            <p class="text-2xl font-bold text-gray-900">1,247</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-2xl shadow-lg border border-gray-100 p-8 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 text-blue-500 shadow-inner">
                            <i class="fas fa-male text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ahli Lelaki</p>
                            <p class="text-2xl font-bold text-gray-900">642</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white rounded-2xl shadow-lg border border-gray-100 p-8 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-xl bg-gradient-to-br from-pink-50 to-pink-100 text-pink-500 shadow-inner">
                            <i class="fas fa-female text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ahli Perempuan</p>
                            <p class="text-2xl font-bold text-gray-900">605</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts + Notifications -->
        <div class="flex justify-center">
            <div class="w-full max-w-4xl">
                <!-- Age Distribution -->
                    <div class="stats-card bg-white rounded-2xl shadow-lg border border-gray-100 p-8 card-hover">
                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Statistik Umur Ahli</h1>
                            </div>
                            <div class="relative">
                                <select class="appearance-none bg-gradient-to-br from-gray-50 to-white border border-gray-200 text-gray-700 py-3 px-6 pr-10 rounded-xl leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all duration-200">
                                    <option>Tahun 2023</option>
                                    <option>Tahun 2022</option>
                                    <option>Tahun 2021</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Age Distribution Chart
            const ageCtx = document.getElementById('ageChart').getContext('2d');
            new Chart(ageCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Kanak-kanak (<18)', 'Belia (18-35)', 'Pertengahan (36-55)', 'Warga Emas (>55)'],
                    datasets: [{
                        data: [320, 450, 280, 197],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.9)',
                            'rgba(59, 130, 246, 0.9)',
                            'rgba(16, 185, 129, 0.9)',
                            'rgba(107, 114, 128, 0.9)'
                        ],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 20,
                        borderRadius: 8,
                        spacing: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { 
                                padding: 25, 
                                usePointStyle: true,
                                font: {
                                    size: 13,
                                    family: 'Inter',
                                    weight: '500'
                                },
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1f2937',
                            bodyColor: '#374151',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 12,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} ahli (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '55%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
</body>
</html>
@endsection