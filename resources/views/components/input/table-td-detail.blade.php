@props(['id', 'title', 'description'])

@php
    $classes = 'border-b border-r border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] last:border-r-0 pt-4 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]';

@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>
