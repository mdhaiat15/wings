@php
    $classes = 'flex items-center mb-[10px] mt-5 mx-1 pb-[10px] uppercase border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] text-sm tracking-widest';

@endphp

<!-- secondary sidebar header - 1 -->
<h2 {{ $attributes->merge(['class' => $classes]) }}>
    {{ $title }}
    {{ $slot }}
</h2>
