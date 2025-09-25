@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
            'class' => 'flex items-center gap-2 px-4 py-3 rounded-md bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 shadow-md animate-fade-in'
        ]) }} x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.500ms>
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <span class="text-sm font-semibold">{{ $status }}</span>
    </div>
@endif
