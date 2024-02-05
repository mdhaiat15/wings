@php
    $classesLi = 'inline-block';

    if (!$last) {
        $classesLi = $classesLi . " after:content-['>'] after:text-[var(--app-text-secondary)] dark:after:text-[var(--app-text-secondary-dark)] after:ml-[5px] after:opacity-40";
    }

    if (!empty($root)) {
        $link = route('dashboard');
    } elseif (!empty($link)) {
        $link = $link;
    }
@endphp

<li {{ $attributes->merge(['class' => $classesLi]) }}>
    @if ($last)
        <span
            class="uppercase text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] px-[14px] py-[14px] rounded-[1000px] inline-block font-normal">{{ !empty($text) ? $text : '' }}</span>
    @else
        <a href="{{ !empty($link) ? $link : '' }}"
            class="uppercase text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] px-[14px] py-[4px] rounded-[1000px] inline-block hover:text-[var(--app-primary)] dark:hover:text-[var(--app-primary-dark)] hover:bg-[var(--app-bg-5)] dark:hover:bg-[var(--app-bg-5-dark)]">
            @if (!empty($root))
                <i class="fa-solid fa-house"></i>
            @else
                {{ !empty($text) ? $text : '' }}
            @endif
        </a>
    @endif
</li>
