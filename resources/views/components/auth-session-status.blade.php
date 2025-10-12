@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
            'class' => 'flex items-center gap-2 px-4 py-3 rounded-md bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 shadow-md '
        ]) }} x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm font-semibold">{{ $status }}</span>
    </div>
@endif
