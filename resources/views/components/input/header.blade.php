@php
    $classes = 'leading-relaxed font-medium border-b-[1px] border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] text-sm mx-0 mt-[30px] mb-[20px] p-0 pb-[10px] uppercase tracking-wider';

@endphp

@props(['title'])

<h2 {{ $attributes->merge(['class' => $classes]) }}>{{ $title }}</h2>
