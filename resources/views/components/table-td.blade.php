@props(['text', 'link', 'image-id', 'image-link'])

@php
    $classes = 'first:pl-[25px] last:pr-[25px] px-[15px] py-[10px] text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]';

@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    @if (!empty($link))
        <a href="{{ $link ?? '#' }}"
            class="transition text-[var(--app-primary)] dark:text-[var(--app-primary-dark)]">{{ $text }}</a>
    @elseif (!empty($imageLink))
        @foreach ($imageLink as $item)
            @if ($loop->first)
                <a data-type="image" data-fslightbox="{{ 1 }}" data-href="{{ $item }}">
                    <img src="{{ $item }}" alt="Image" class="h-20 w-auto cursor-pointer">
                </a>
            @else
                <a data-type="image" data-fslightbox="{{ $imageId }}" data-href="{{ $item }}">
                    <img src="{{ $item }}" alt="Image" class="h-20 w-auto hidden">
                </a>
            @endif
        @endforeach
    @else
        <span>{{ $text }}</span>
    @endif
</td>
