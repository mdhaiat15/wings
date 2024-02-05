@php
    $classes = 'mx-2 mb-1 px-[3px] py-[5px] text-sm rounded relative  leading-relaxed text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border border-solid border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] shadow ';
@endphp

<button type="button" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
