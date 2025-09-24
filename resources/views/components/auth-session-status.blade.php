@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
        'class' => 'flex items-center gap-2 px-4 py-3 rounded-md bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 shadow-md animate-fade-in'
    ]) }}>
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm font-semibold">{{ $status }}</span>
    </div>
@endif
