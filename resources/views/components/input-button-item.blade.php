@php
    $classes = 'rounded-[5px] font-medium';

    if (empty($overrideColor)) {
        $classes .= ' text-[var(--app-bg-0)] dark:text-[var(--app-text-primary-dark)]';
    }

    if (empty($overrideBackground)) {
        $classes .= ' bg-[var(--app-primary)] dark:bg-[var(--app-primary-dark)]';
    }

    if (!empty($type)) {
        if ($type == 'small') {
            $classes .= ' text-sm py-1 px-2';
        } else {
            $classes .= ' text-lg py-2 px-5';
        }
    } else {
        $classes .= ' text-lg py-2 px-5';
    }

    $id = $getId();

@endphp

<button type="{{ !empty($buttonType) ? $buttonType : 'submit' }}" id="{{ !empty($id) ? $id : '' }}"
    onclick="{{ !empty($confirm) ? "return confirm('$confirm')" : '' }}" {{ $attributes->merge(['class' => $classes]) }}>

    {{ $text }}
</button>
