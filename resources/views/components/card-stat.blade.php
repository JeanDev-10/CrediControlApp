<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 shadow rounded-2xl p-6 flex flex-col items-center text-center']) }}>
      @isset($icon)
        <div class="mb-2 text-4xl">
            {{ $icon }}
        </div>
    @endisset
    <span class="text-sm md:text-lg text-black dark:text-gray-100">
        {{ $title }}
    </span>

    <span class="text-3xl font-bold {{ $color }} dark:text-opacity-80 mt-2">
        {{ $value }}
    </span>

    {{ $slot }}
</div>
