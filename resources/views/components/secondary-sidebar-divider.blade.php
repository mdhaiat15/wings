@php
    $classes = 'border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] my-[10px]';

@endphp

<div {{ $attributes->merge(['class' => $classes]) }}></div>
