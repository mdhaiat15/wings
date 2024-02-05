@php
    $classes = 'block height-[35px] leading-[35px] px-5 mx-[7px] mb-[2px] rounded-[5px] whitespace-nowrap overflow-hidden text-ellipsis hover:bg-[var(--app-bg-0)] dark:hover:bg-[var(--app-bg-0-dark)] hover:text-[var(--app-primary)] dark:hover:text-[var(--app-primary-dark)]';
    $iconClasses = '';

    if (request()->route()->named($routename)) {
        $classes = $classes . ' bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] text-[var(--app-primary)] dark:text-[var(--app-primary-dark)]';
        $iconClasses = $iconClasses . ' bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] text-[var(--app-primary)] dark:text-[var(--app-primary-dark)]';
    } else {
        $classes = $classes . ' text-[var(--app-text-sidebar)] dark:text-[var(--app-text-sidebar-dark)]';
        $iconClasses = $iconClasses . ' text-[var(--app-text-sidebar)] dark:text-[var(--app-text-sidebar-dark)]';
    }
@endphp

<a href="{{ route($routename) }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if (!empty($icon))
        <i class="fa-solid {{ $icon }} fa-md mr-[5px] {{ $iconClasses }}"></i>
    @endif
    {{ $title }}
</a>
