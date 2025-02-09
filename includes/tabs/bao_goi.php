<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>

<div class="w-full mb-4 bg-white rounded-lg shadow p-4">
    <!-- Main Packaging System Status -->

</div>

<style>
/* Status colors */
.status-running {
    @apply bg-green-500;
}

.status-stopping {
    @apply bg-yellow-500;
}

.status-stopped {
    @apply bg-red-500;
}

.status-warning {
    @apply bg-orange-500;
}

/* Alert styles */
.alert-item {
    @apply flex items-center p-3 rounded-lg border mb-2 transition-all duration-300;
}

.alert-critical {
    @apply bg-red-50 border-red-200 text-red-700;
}

.alert-warning {
    @apply bg-yellow-50 border-yellow-200 text-yellow-700;
}

.alert-info {
    @apply bg-blue-50 border-blue-200 text-blue-700;
}

/* Chart tooltips */
.chart-tooltip {
    @apply bg-white shadow-lg rounded-lg p-2 border border-gray-200;
}

/* Animation for status changes */
.status-transition {
    transition: background-color 0.3s ease;
}

/* Hover effects for interactive elements */
.interactive-element:hover {
    @apply cursor-pointer transform scale-105 transition-transform duration-200;
}

/* Scrollbar styling */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E0 #EDF2F7;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    @apply bg-gray-100;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    @apply bg-gray-400 rounded;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chart-container {
        height: 200px;
    }

    .status-label {
        font-size: 0.875rem;
    }

    .value-display {
        font-size: 1.25rem;
    }
}
</style>

<script>
// Function to initialize the weight distribution chart
function initWeightChart(lineId) {
    const ctx = document.getElementById(lineId + '-pkg-weight-chart').getContext('2d');
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Trọng lượng',
                data: [],
                borderColor: '#3B82F6',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Function to initialize the production timeline chart
function initTimelineChart(lineId) {
    const ctx = document.getElementById(lineId + '-pkg-timeline-chart').getContext('2d');
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Sản lượng',
                data: [],
                backgroundColor: '#60A5FA'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// Function to update status indicators
function updateStatus(elementId, status) {
    const statusElement = document.getElementById(elementId);
    const textElement = document.getElementById(elementId + '-text');
    
    if (statusElement && textElement) {
        // Remove all existing status classes
        statusElement.classList.remove('status-running', 'status-stopping', 'status-stopped', 'status-warning');
        
        // Add appropriate status class
        statusElement.classList.add('status-' + status.toLowerCase());
        
        // Update text content
        const statusTexts = {
            running: 'Đang chạy',
            stopping: 'Đang dừng',
            stopped: 'Đã dừng',
            warning: 'Cảnh báo'
        };
        
        textElement.textContent = statusTexts[status.toLowerCase()] || status;
    }
}

// Function to add alert
function addAlert(containerId, alert) {
    const alertsContainer = document.getElementById(containerId);
    if (alertsContainer) {
        const alertElement = document.createElement('div');
        alertElement.className = `alert-item alert-${alert.type}`;
        alertElement.innerHTML = `
            <div class="flex-1">
                <div class="font-semibold">${alert.title}</div>
                <div class="text-sm">${alert.message}</div>
            </div>
            <div class="text-xs text-gray-500">${alert.time}</div>
        `;
        
        alertsContainer.insertBefore(alertElement, alertsContainer.firstChild);
        
        // Remove old alerts if there are too many
        while (alertsContainer.children.length > 10) {
            alertsContainer.removeChild(alertsContainer.lastChild);
        }
    }
}
</script>