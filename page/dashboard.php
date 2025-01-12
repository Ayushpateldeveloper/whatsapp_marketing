<<<<<<< HEAD
<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

ob_start();
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Total Messages</h3>
            <span class="bg-green-400/10 text-green-400 text-xs px-2 py-1 rounded-full">+12.5%</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">24,580</p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Active Campaigns</h3>
            <span class="bg-blue-400/10 text-blue-400 text-xs px-2 py-1 rounded-full">Active</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">12</p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Total Contacts</h3>
            <span class="bg-purple-400/10 text-purple-400 text-xs px-2 py-1 rounded-full">Updated</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">8,549</p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Success Rate</h3>
            <span class="bg-yellow-400/10 text-yellow-400 text-xs px-2 py-1 rounded-full">98.5%</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">98.5%</p>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg">
    <h3 class="text-lg font-semibold mb-4">Message Statistics</h3>
    <div class="h-[300px] md:h-[400px]">
        <canvas id="messageStats"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize Chart
    const ctx = document.getElementById('messageStats').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Messages Sent',
                data: [3000, 4500, 3800, 5200, 4800, 6000],
                borderColor: '#60A5FA',
                tension: 0.4,
                borderWidth: 2,
                fill: true,
                backgroundColor: 'rgba(96, 165, 250, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF',
                        maxTicksLimit: 5
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF',
                        maxTicksLimit: window.innerWidth < 768 ? 4 : 6
                    }
                }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
require_once '../layout/layout.php';
?>
=======
<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

ob_start();
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Total Messages</h3>
            <span class="bg-green-400/10 text-green-400 text-xs px-2 py-1 rounded-full">+12.5%</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">24,580</p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Active Campaigns</h3>
            <span class="bg-blue-400/10 text-blue-400 text-xs px-2 py-1 rounded-full">Active</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">12</p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Total Contacts</h3>
            <span class="bg-purple-400/10 text-purple-400 text-xs px-2 py-1 rounded-full">Updated</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">8,549</p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-gray-400 text-sm font-medium">Success Rate</h3>
            <span class="bg-yellow-400/10 text-yellow-400 text-xs px-2 py-1 rounded-full">98.5%</span>
        </div>
        <p class="text-2xl md:text-3xl font-bold mt-2">98.5%</p>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-gray-800 rounded-xl p-4 md:p-6 shadow-lg">
    <h3 class="text-lg font-semibold mb-4">Message Statistics</h3>
    <div class="h-[300px] md:h-[400px]">
        <canvas id="messageStats"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize Chart
    const ctx = document.getElementById('messageStats').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Messages Sent',
                data: [3000, 4500, 3800, 5200, 4800, 6000],
                borderColor: '#60A5FA',
                tension: 0.4,
                borderWidth: 2,
                fill: true,
                backgroundColor: 'rgba(96, 165, 250, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF',
                        maxTicksLimit: 5
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#9CA3AF',
                        maxTicksLimit: window.innerWidth < 768 ? 4 : 6
                    }
                }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
require_once '../layout/layout.php';
?>
>>>>>>> 228c558 (updated)
