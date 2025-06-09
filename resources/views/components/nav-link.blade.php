@props(['active'])

@php
$classes = ($active ?? false)
    ? 'flex items-center w-full text-2xl font-extrabold px-4 py-3 text-blue dark:text-white'
    : 'flex items-center w-full text-2xl font-extrabold px-4 py-3 text-blue dark:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>