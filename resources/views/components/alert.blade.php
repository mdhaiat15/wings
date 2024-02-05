@php
    $classes = 'flex my-2 border rounded-[5px]';
    $typeClasses = '';
    $icon = !empty($icon) ? $icon : '';

    if (!empty($type)) {
        if ($type == 'error') {
            $classes .= ' bg-[var(--app-danger-secondary)] dark:bg-[var(--app-danger-secondary-dark)] border-[var(--app-danger)] dark:border-[var(--app-danger-dark)]';
            $typeClasses = ' ';
            $icon = !empty($icon) ? $icon : 'fa-exclamation';
        } else {
            $classes .= ' bg-[var(--app-success-secondary)] dark:bg-[var(--app-success-secondary-dark)] border-[var(--app-success)] dark:border-[var(--app-success-dark)]';
            $typeClasses = ' text-[var(--app-success)] dark:text-[var(--app-success-dark)]';
            $icon = !empty($icon) ? $icon : 'fa-check';
        }
    } else {
        $classes .= ' bg-[var(--app-success-secondary)] dark:bg-[var(--app-success-secondary-dark)] border-[var(--app-success)] dark:border-[var(--app-success-dark)]';
        $typeClasses = ' text-[var(--app-success)] dark:text-[var(--app-success-dark)]';
        $icon = !empty($icon) ? $icon : 'fa-check';
    }
@endphp


<div {{ $attributes->merge(['class' => $classes]) }}>

    @if ($icon)
        {{-- tag --}}
        <div class="flex items-center justify-center px-[15px] py-[20px] ">
            <i class="fa-solid {{ $icon }} fa-md {{ $typeClasses }}"></i>
        </div>
    @endif

    {{-- content --}}
    <div class="text-base leading-relaxed text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] flex-1 px-[15px] py-[20px] pl-0 ml-2">
        <p class="p-0 leading-relaxed font-bold m-0 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]">{{ $title ?? '' }}</p>
        <p class="p-0 leading-relaxed m-0 text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)]">
            @if (!empty($link))
                The <a href="#"
                    class="p-0 m-0 leading-relaxed font-bold {{ $typeClasses }}">{{ $linkTitle ?? '' }}</a>
                {{ $message }}
            @else
                {{ $message }}
            @endif
        </p>
    </div>

    @if (!empty($close))
        {{-- close --}}
        <a href="#" class="flex items-center justify-center py-[15px] px-[20px]">
            <i class="fa-solid fa-close fa-md mr-[5px] {{ $typeClasses }}"></i>
        </a>
    @endif
</div>
