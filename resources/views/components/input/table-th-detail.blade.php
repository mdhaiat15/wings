@props(['id', 'title', 'description', 'required'])

@php
    $classes = 'px-[15px] py-[10px] whitespace-nowrap text-left border-b border-r last:border-r-0 border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] text-sm font-medium';

    if (!empty($required)) {
        $titleClass = " after:content-['*'] after:top-[3px] after:text-[1em] after:relative after:text-[var(--app-text-secondary)] dark:after:text-[var(--app-text-secondary-dark)]";
    }

@endphp

<th {{ $attributes->merge(['class' => $classes]) }}>
    <p class="{{ $titleClass ?? '' }}">
        {{ $title }}
    </p>
    <span class="block text-xs text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] font-normal">
        {{ $description ?? '' }}
    </span>
</th>
