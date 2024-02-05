@props(['title', 'action', 'form-name', 'idx'])

@php
    $classes = 'px-[25px] py-[20px] dark:border-[var(--app-gray-10-dark)]';
@endphp


<div {{ $attributes->merge(['class' => $classes]) }}>

    {{-- title-bar --}}
    <div class="flex flex-col sm:flex-row items-center justify-between flex-wrap">

        <h3
            class="flex-initial sm:flex-1 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] text-left text-lg font-medium pr-[10px] overflow-hidden sm:whitespace-nowrap overflow-ellipsis">
            {{ $title }}
        </h3>

        <div
            class="w-full mt-[10px] mb-[20px] border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] block sm:hidden">
        </div>

        <div class="flex flex-auto w-full sm:max-w-max flex-col-reverse sm:flex-row-reverse text-center gap-3 sm:gap-0">
            @if (!empty($action))

                @foreach ($action as $key => $item)
                    @if ($item['action'] == 'save')
                        <x-input-button-item key="-button-save" :form-name="$formName" text="{{ $item['text'] }}"
                            override-color="true"
                            class="text-[var(--app-bg-0)] dark:text-[var(--app-text-primary-dark)] w-full sm:w-auto" />
                    @elseif ($item['action'] == 'delete')
                        <form action="{{ !empty($item) ? $item['link'] : '' }}" method="POST"
                            enctype="multipart/form-data">
                            @method('DELETE')
                            @csrf

                            <x-input-button-item text="{{ $item['text'] }}" :form-name="$formName" key="-button-delete"
                                override-color="true" override-background="true"
                                class="mr-3 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] text-[var(--app-danger)] dark:text-[var(--app-danger-dark)] border border-[var(--app-danger)] dark:border-[var(--app-danger-dark)] w-full sm:w-auto" />

                        </form>
                        <x-input.js-button-delete :id="$idx" :form-name="$formName" />
                    @elseif ($item['action'] == 'link')
                        <x-button-item text="{{ $item['text'] }}" :link="$item['link']" override-color="true"
                            override-background="true"
                            class="mr-3 bg-[var(--app-accent)] dark:bg-[var(--app-accent-dark)] text-[var(--app-primary)] dark:text-[var(--app-primary-dark)] border border-[var(--app-accent)] dark:border-[var(--app-accent-dark)] w-full sm:w-auto" />
                    @elseif ($item['action'] == 'action')
                        @if (!empty($item['lists']))
                            <x-input-button-dropdown-item text="{{ $item['text'] }}" :lists="$item['lists'] ?? []"
                                bulk-id="{{ $idx }}" :form-name="$formName" />
                        @endif
                    @else
                        <x-input-button-item text="{{ $item['text'] }}"
                            confirm="{{ !empty($item['confirm']) ? $item['confirm'] : '' }}" override-color="true"
                            override-background="true"
                            class="mr-3 bg-[var(--app-accent)] dark:bg-[var(--app-accent-dark)] text-[var(--app-primary)] dark:text-[var(--app-primary-dark)] border border-[var(--app-accent)] dark:border-[var(--app-accent-dark)] w-full sm:w-auto" />
                    @endif
                @endforeach

            @endif
        </div>

    </div>

</div>
