<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>

<div class="flex flex-wrap gap-2 mb-4">
    <!-- Tab chảo chiên -->
    <button class="tab-button active flex-1 min-w-[120px] px-2 py-2 rounded-lg font-semibold text-sm md:text-base transition-colors" 
            onclick="switchTab('chao_chien')">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-1 text-red-500 md:w-5 md:h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="text-sm md:text-base">Chảo chiên</span>
        </span>
    </button>

    <!-- Tab lô cán -->
    <button class="tab-button flex-1 min-w-[120px] px-2 py-2 rounded-lg font-semibold text-sm md:text-base transition-colors" 
            onclick="switchTab('Can')">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-1 text-blue-500 md:w-5 md:h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="6" width="18" height="12" rx="2" stroke-width="2"/>
                <line x1="7" y1="12" x2="17" y2="12" stroke-width="2"/>
            </svg>
            <span class="text-sm md:text-base">Lô Cán</span>
        </span>
    </button>

    <!-- Tab trộn bột -->
    <button class="tab-button flex-1 min-w-[120px] px-2 py-2 rounded-lg font-semibold text-sm md:text-base transition-colors" 
            onclick="switchTab('tron')">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-1 text-green-500 md:w-5 md:h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="8" stroke-width="2"/>
                <path d="M12 8v8M8 12h8" stroke-width="2"/>
            </svg>
            <span class="text-sm md:text-base">Trộn Bột</span>
        </span>
    </button>

    <!-- Tab kansui -->
    <button class="tab-button flex-1 min-w-[120px] px-2 py-2 rounded-lg font-semibold text-sm md:text-base transition-colors" 
            onclick="switchTab('kansui')">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-1 text-yellow-500 md:w-5 md:h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 8l6-6 6 6M6 16l6 6 6-6" stroke-width="2"/>
                <line x1="12" y1="2" x2="12" y2="22" stroke-width="2"/>
            </svg>
            <span class="text-sm md:text-base">Kansui - Sea</span>
        </span>
    </button>

    <!-- Tab chiller -->
    <button class="tab-button flex-1 min-w-[120px] px-2 py-2 rounded-lg font-semibold text-sm md:text-base transition-colors" 
            onclick="switchTab('Chiller')">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-1 text-blue-500 md:w-5 md:h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M20 9h-5L8.86 2.86a2 2 0 00-2.83 0L2.29 6.7a2 2 0 000 2.83L8.86 15H4" stroke-width="2"/>
                <circle cx="12" cy="12" r="3" stroke-width="2"/>
            </svg>
            <span class="text-sm md:text-base">Chiller</span>
        </span>
    </button>

    <!-- Tab bao gói -->
    <button class="tab-button flex-1 min-w-[120px] px-2 py-2 rounded-lg font-semibold text-sm md:text-base transition-colors" 
            onclick="switchTab('bao_goi')">
        <span class="flex items-center justify-center">
            <svg class="w-4 h-4 mr-1 text-purple-500 md:w-5 md:h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                <path d="M16 8h.01M12 8h.01M8 8h.01M16 12h.01M12 12h.01M8 12h.01M16 16h.01M12 16h.01M8 16h.01" stroke-width="2"/>
            </svg>
            <span class="text-sm md:text-base">Bao gói</span>
        </span>
    </button>
</div>

<style>
/* Tab button styles */
.tab-button {
    background-color: #073480;
    color: white;
    transition: all 0.3s ease;
}

.tab-button:hover {
    background-color: #334155;
    transform: translateY(-1px);
}

.tab-button.active {
    background-color: white;
    color: #073480;
    border: 2px solid #073480;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Icon color inheritance */
.tab-button svg {
    color: currentColor;
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tab-button {
        min-width: 100px;
        padding: 0.5rem 1rem;
    }
    
    .tab-button svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.25rem;
    }
}
</style>