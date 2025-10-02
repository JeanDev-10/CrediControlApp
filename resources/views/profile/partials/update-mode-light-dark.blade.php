<div class="relative">
    <!-- Botón principal -->
    <div class="flex space-between gap-3">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Modo de color') }}
    </h2>
    <button id="theme-button"
        class="flex items-center gap-2 px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-xl shadow-md transition hover:scale-105">
        <span id="theme-label" class="text-sm text-gray-800 dark:text-gray-200">Sistema</span>
        <span id="theme-icon"></span>
    </button>
    </div>

    <!-- Opciones -->
    <div id="theme-options"
        class="absolute right-56 mt-2 w-40 bg-white dark:bg-gray-800 shadow-lg rounded-xl py-2 hidden transition">
        <button data-theme="light"
            class="flex items-center gap-2 px-3 py-2 w-full hover:bg-gray-100 dark:hover:bg-white-700 transition dark:text-white">
            ☀️ Claro
        </button>
        <button data-theme="dark"
            class="flex items-center gap-2 px-3 py-2 w-full hover:bg-gray-100 dark:hover:bg-white-700 transition dark:text-white">
            🌙 Oscuro
        </button>
        <button data-theme="system"
            class="flex items-center gap-2 px-3 py-2 w-full hover:bg-gray-100 dark:hover:bg-white-700 transition dark:text-white">
            💻 Sistema
        </button>
    </div>
</div>

