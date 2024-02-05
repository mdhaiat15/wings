@props(['title', 'is-active', 'link', 'icon'])

@php
    $classes = 'first:pl-[25px] last:pr-[25px] leading-loose px-[15px] py-[10px]';

    if (!empty($isActive)) {
        if ($isActive == true) {
            $classes = $classes . ' text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]';
        } else {
            $classes = $classes . ' text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)]';
        }
    } else {
        $classes = $classes . ' text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)]';
    }

@endphp

<th {{ $attributes->merge(['class' => $classes]) }}>
    <a href="{{ !empty($link) ? $link : '#' }}" class="">{{ $title }} <i class='fa-solid {{ !empty($icon) ? $icon : "" }} '></i> </a>
</th>
