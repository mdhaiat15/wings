@php
    $classes = 'px-[15px] py-[5px] text-sm leading-normal rounded relative float-left ml-0 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border border-solid border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] ';
@endphp

<button type="button" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
