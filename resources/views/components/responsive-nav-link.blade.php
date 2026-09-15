@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-edessi-600 dark:border-acento-500 text-start text-base font-medium text-edessi-700 dark:text-acento-500 bg-edessi-50 dark:bg-edessi-900/30 focus:outline-none focus:text-edessi-800 focus:bg-edessi-100 dark:focus:bg-edessi-900/40 focus:border-edessi-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-300 hover:text-edessi-700 dark:hover:text-acento-500 hover:bg-gray-50 dark:hover:bg-edessi-900/30 hover:border-gray-300 dark:hover:border-acento-500 focus:outline-none focus:text-gray-800 dark:focus:text-acento-500 focus:bg-gray-50 dark:focus:bg-edessi-900/30 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>