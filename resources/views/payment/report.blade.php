@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
        }
        .transaction-table th {
            background-color: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        .transaction-table td {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .transaction-table tr:hover {
            background-color: #f9fafb;
        }
        .amount-positive {
            color: #10b981;
            font-weight: 600;
        }
        .amount-negative {
            color: #ef4444;
            font-weight: 600;
        }
        .filter-active {
            background-color: #3b82f6;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="px-8 py-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Transaksi Khairat</h1>
                <p class="text-gray-600 mt-2">Rekod Terperinci Semua Transaksi Masuk & Keluar</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tarikh</label>
                    <div class="flex gap-2">
                        <input type="date" id="startDate" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <span class="self-center text-gray-500">hingga</span>
                        <input type="date" id="endDate" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Transaction Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                    <select id="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="all">Semua</option>
                        <option value="income">Duit Masuk</option>
                        <option value="expense">Duit Keluar</option>
                    </select>
                </div>

                <!-- Quick Date Filters -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cepat</label>
                    <div class="flex gap-2">
                        <button onclick="setDateRange('today')" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Hari Ini</button>
                        <button onclick="setDateRange('week')" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Minggu Ini</button>
                        <button onclick="setDateRange('month')" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Bulan Ini</button>
                    </div>
                </div>

                <!-- Search -->
                <div class="ml-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Nama / No Rujukan..." 
                               class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Senarai Transaksi</h3>
                <div class="text-sm text-gray-500">
                    <span id="transactionCount">0</span> transaksi ditemui
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Tarikh & Masa</th>
                            <th>Penerangan</th>
                            <th>No Rujukan</th>
                            <th>Nama Pembayar/Penerima</th>
                            <th>Jenis</th>
                            <th class="text-right">Amaun (RM)</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTable">
                        <!-- Transactions will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                <p class="text-gray-500 mt-2">Memuatkan transaksi...</p>
            </div>

            <!-- No Results -->
            <div id="noResults" class="text-center py-8 hidden">
                <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Tiada transaksi ditemui</p>
            </div>
        </div>
    </div>

    <script>
        // Sample transaction data
        const transactionsData = [
            {
                id: 'TRX001',
                date: '2025-01-15',
                time: '14:30:25',
                description: 'Bayaran Yuran Bulanan',
                reference: 'YUR25011501',
                name: 'Ahmad Bin Ismail',
                type: 'income',
                amount: 50.00,
                bank: 'Maybank',
                status: 'completed'
            },
            {
                id: 'TRX002',
                date: '2025-01-15',
                time: '11:15:42',
                description: 'Tuntutan Kematian',
                reference: 'TKM25011501',
                name: 'Waris Ali Bin Hassan',
                type: 'expense',
                amount: 2000.00,
                bank: 'CIMB',
                status: 'completed'
            },
            {
                id: 'TRX003',
                date: '2025-01-14',
                time: '09:45:18',
                description: 'Bayaran Yuran Bulanan',
                reference: 'YUR25011401',
                name: 'Siti Nurhaliza Binti Ahmad',
                type: 'income',
                amount: 50.00,
                bank: 'Bank Islam',
                status: 'completed'
            },
            {
                id: 'TRX004',
                date: '2025-01-13',
                time: '16:20:33',
                description: 'Tuntutan Perubatan',
                reference: 'TPR25011301',
                name: 'Muthu A/L Krishnan',
                type: 'expense',
                amount: 1500.00,
                bank: 'Public Bank',
                status: 'completed'
            },
            {
                id: 'TRX005',
                date: '2025-01-12',
                time: '10:05:57',
                description: 'Derma Khas',
                reference: 'DMK25011201',
                name: 'Tan Sri Dr. Lim',
                type: 'income',
                amount: 5000.00,
                bank: 'Hong Leong',
                status: 'completed'
            },
            {
                id: 'TRX006',
                date: '2025-01-11',
                time: '15:40:12',
                description: 'Tuntutan Pendidikan',
                reference: 'TPD25011101',
                name: 'Sarah Binti Abdullah',
                type: 'expense',
                amount: 800.00,
                bank: 'RHB',
                status: 'completed'
            },
            {
                id: 'TRX007',
                date: '2025-01-10',
                time: '08:55:29',
                description: 'Bayaran Yuran Bulanan',
                reference: 'YUR25011001',
                name: 'Mohd Rizal Bin Kamal',
                type: 'income',
                amount: 50.00,
                bank: 'Maybank',
                status: 'completed'
            },
            {
                id: 'TRX008',
                date: '2025-01-09',
                time: '13:25:44',
                description: 'Tuntutan Kematian',
                reference: 'TKM25010901',
                name: 'Waris Lim Ah Chong',
                type: 'expense',
                amount: 2000.00,
                bank: 'OCBC',
                status: 'completed'
            }
        ];

        function formatCurrency(amount) {
            return 'RM ' + amount.toFixed(2);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('ms-MY', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function formatTime(timeString) {
            return timeString;
        }

        function renderTransactions(transactions) {
            const tableBody = document.getElementById('transactionsTable');
            const loadingState = document.getElementById('loadingState');
            const noResults = document.getElementById('noResults');
            const transactionCount = document.getElementById('transactionCount');
            
            loadingState.classList.add('hidden');
            
            if (transactions.length === 0) {
                noResults.classList.remove('hidden');
                tableBody.innerHTML = '';
                transactionCount.textContent = '0';
                return;
            }
            
            noResults.classList.add('hidden');
            transactionCount.textContent = transactions.length;

            let totalIncome = 0;
            let totalExpense = 0;

            tableBody.innerHTML = transactions.map(transaction => {
                if (transaction.type === 'income') {
                    totalIncome += transaction.amount;
                } else {
                    totalExpense += transaction.amount;
                }

                const amountClass = transaction.type === 'income' ? 'amount-positive' : 'amount-negative';
                const amountPrefix = transaction.type === 'income' ? '+' : '-';
                const typeText = transaction.type === 'income' ? 'Duit Masuk' : 'Duit Keluar';
                const typeColor = transaction.type === 'income' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';

                return `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td>
                            <div class="font-medium text-gray-900">${formatDate(transaction.date)}</div>
                            <div class="text-sm text-gray-500">${formatTime(transaction.time)}</div>
                        </td>
                        <td>
                            <div class="font-medium text-gray-900">${transaction.description}</div>
                            <div class="text-sm text-gray-500">${transaction.bank}</div>
                        </td>
                        <td>
                            <div class="font-mono text-sm text-gray-600">${transaction.reference}</div>
                        </td>
                        <td>
                            <div class="font-medium text-gray-900">${transaction.name}</div>
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${typeColor}">
                                ${typeText}
                            </span>
                        </td>
                        <td class="text-right">
                            <span class="${amountClass} font-mono">
                                ${amountPrefix} ${transaction.amount.toFixed(2)}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');

            // Update summary cards
            document.getElementById('totalIncome').textContent = formatCurrency(totalIncome);
            document.getElementById('totalExpense').textContent = formatCurrency(totalExpense);
            document.getElementById('currentBalance').textContent = formatCurrency(totalIncome - totalExpense);
        }

        function filterTransactions() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            let filtered = transactionsData;

            // Date filter
            if (startDate) {
                filtered = filtered.filter(t => t.date >= startDate);
            }
            if (endDate) {
                filtered = filtered.filter(t => t.date <= endDate);
            }

            // Type filter
            if (typeFilter !== 'all') {
                filtered = filtered.filter(t => t.type === typeFilter);
            }

            // Search filter
            if (searchTerm) {
                filtered = filtered.filter(t => 
                    t.name.toLowerCase().includes(searchTerm) ||
                    t.reference.toLowerCase().includes(searchTerm) ||
                    t.description.toLowerCase().includes(searchTerm)
                );
            }

            // Sort by date (newest first)
            filtered.sort((a, b) => new Date(b.date + ' ' + b.time) - new Date(a.date + ' ' + a.time));

            renderTransactions(filtered);
        }

        function setDateRange(range) {
            const today = new Date();
            let startDate, endDate;

            switch (range) {
                case 'today':
                    startDate = today.toISOString().split('T')[0];
                    endDate = startDate;
                    break;
                case 'week':
                    startDate = new Date(today.setDate(today.getDate() - 7)).toISOString().split('T')[0];
                    endDate = new Date().toISOString().split('T')[0];
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    endDate = new Date().toISOString().split('T')[0];
                    break;
            }

            document.getElementById('startDate').value = startDate;
            document.getElementById('endDate').value = endDate;
            filterTransactions();
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date range to current month
            setDateRange('month');
            
            // Add event listeners
            document.getElementById('startDate').addEventListener('change', filterTransactions);
            document.getElementById('endDate').addEventListener('change', filterTransactions);
            document.getElementById('typeFilter').addEventListener('change', filterTransactions);
            document.getElementById('searchInput').addEventListener('input', filterTransactions);

            // Simulate loading
            setTimeout(() => {
                filterTransactions();
            }, 1000);
        });
    </script>
</body>
</html>
@endsection