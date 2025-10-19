@props([
    'variant' => 'primary', // valores posibles: primary, secondary, tertiary
    'href' => '#',
])

@php
    $baseClasses = 'inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 "w-full sm:w-auto';

    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 border border-blue-300 dark:border-blue-500 dark:hover:bg-blue-700 dark:focus:ring-offset-blue-800',
        'secondary' => 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700',
        'tertiary' => 'bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300',
    ];

    $class = "$baseClasses " . ($variants[$variant] ?? $variants['primary']);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</a>
