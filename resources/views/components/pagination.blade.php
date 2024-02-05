@props(['link', 'removeUrlQuery'])

@php
    $classes = 'block list-none pt-[20px] px-[25px] pb-[20px]';

    $removeUrlQuery = (array) $removeUrlQuery ?? [];
@endphp

<ul {{ $attributes->merge(['class' => $classes]) }}>

    @foreach ($link as $item)
        <li class="inline-block text-sm mb-4 ">
            @if ($item['url'])
                <a href="{{ CustomHelper::parseUrlQuery($item['url'] ?? '', $removeUrlQuery ?? []) }}"
                    class="border border-solid {{ $item['active'] ? 'border-[var(--app-primary)] dark:border-[var(--app-primary-dark)]' : 'border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]' }} shadow bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] px-[15px] py-[5px] rounded text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] hover:text-[var(--app-primary)] dark:hover:text-[var(--app-primary-dark)]">{{ html_entity_decode($item['label']) }}</a>
            @else
                <span
                    class="border border-solid {{ $item['active'] ? 'border-[var(--app-primary)] dark:border-[var(--app-primary-dark)]' : 'border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]' }} shadow bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] px-[15px] py-[5px] rounded text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] hover:text-[var(--app-primary)] dark:hover:text-[var(--app-primary-dark)]">{{ html_entity_decode($item['label']) }}</span>
            @endif
        </li>
    @endforeach
</ul>
