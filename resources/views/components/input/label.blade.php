@props(['id', 'title', 'description', 'tooltip', 'required'])

@php
    $classes = 'leading-relaxed max-w-full block text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] font-medium mb-[5px]';

    if (!empty($required)) {
        $classes .= " after:content-['*'] after:top-[3px] after:text-[1em] after:relative after:text-[var(--app-danger)] dark:after:text-[var(--app-danger-dark)]";
    }

@endphp

{{-- field instruct --}}
<div class="w-full mb-[5px]">
    <label for="{{ $id }}" {{ $attributes->merge(['class' => $classes]) }}
        title="{{ !empty($tooltip) ? __($tooltip) : __($title) }}">{{ $title }} </label>
    @if (!empty($description))
        <em class="block not-italic text-xs text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)] mt-[-5px] mb-0"> {{ $description }}</em>
    @endif
</div>
