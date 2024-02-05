@props(['title', 'action'])

@php
    $classes = 'px-[25px] py-[20px] border-b  dark:border-[var(--app-gray-10-dark)]';
@endphp


<div {{ $attributes->merge(['class' => $classes]) }}>

    {{-- title-bar --}}
    <div class="flex items-center flex-wrap">

        <h3
            class="flex-1 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] text-left text-lg font-medium pr-[10px] overflow-hidden whitespace-nowrap overflow-ellipsis ">
            {{ $title }}
        </h3>

        <div class="flex justify-end">
            @if (!empty($action))
                <x-button-item :link="$action['link']" :text="$action['text']" class="ml-auto" />
            @endif
        </div>

    </div>

</div>
