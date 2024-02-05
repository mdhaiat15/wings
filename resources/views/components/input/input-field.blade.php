@props([
    'key',
    'id',
    'name',
    'type',
    'value',
    'field-name',
    'required',
    'readonly',
    'placeholder',
    'required-placeholder',
    'null-placeholder',
    'array-lookup',
    'array-lookup-key',
    'accept',
    'size',
    'array-attachment',
    'select2',
    'is-filter',
    'is-search',
    'on-change',
    'alpine-mask',
])

@php
    $classes = 'w-full leading-relaxed shadow border rounded-[5px] border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] focus:border-[var(--app-primary)] dark:focus:border-[var(--app-primary-dark)] dark:[color-scheme:dark]';

    if ($type == 'label') {
        $classes .= ' block min-h-[40px] text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-gray-20)] dark:bg-[var(--app-gray-20-dark)] py-2 px-3';
    } elseif ($type == 'multipleselect') {
        $classes .= ' text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] h-[50px]';
    } else {
        $classes .= ' text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)]';
    }

    // arrayLookupKey is alternative key
    $theKey = !empty($arrayLookupKey) ? $arrayLookupKey : $key;
    parse_str($theKey, $out);

    if (!empty($out['key'])) {
        eval("\$theKey = " . $out['key'] . ';');
    }

    $value = !empty(old($name)) ? old($name) : $value;
@endphp

@error($fieldName)
    @php
        $classes .= ' is-invalid';
    @endphp
@enderror


{{-- field control --}}
<div class="w-full">
    @if (in_array($type, ['text', 'date', 'datetime-local', 'email', 'time', 'hidden', 'search']))
        {{-- input basic --}}
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
            placeholder="{{ $placeholder }}" {{ $required }} {{ $readonly }} onchange="{{ $onChange }}"
            {!! $alpineMask !!} {{ $attributes->merge(['class' => $classes]) }}>
    @elseif ($type == 'file')
        {{-- file --}}
        <div class="flex items-center justify-center w-full">
            <label for="{{ $id }}"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                            upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $accept }} {{ $size }}</p>
                    <p class="text-sm font-medium mt-4 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]"
                        id="{{ $id . 'imageName' }}">(choose file)</p>
                </div>
                <input type="file" id="{{ $id }}" name="{{ $name }}" {{ $required }}
                    {{ $readonly }} class="hidden" accept="{{ $accept }}" />
            </label>
        </div>

        @if (!empty($arrayAttachment[$theKey]))
            <div class="flex flex-wrap items-center justify-start w-full gap-2 mt-2 "
                id="parent-file-delete-{{ $id }}">
                @foreach ($arrayAttachment[$theKey] as $item)
                    <div class="relative border border-solid rounded p-3 w-auto"
                        id="child-file-delete-{{ $id . $item['id'] }}">

                        @if (preg_match("/\.(jpe?g|png|gif|webp|svg)$/im", $item['file_path']))
                            <a data-type="image" data-fslightbox="{{ $theKey }}"
                                data-href="{{ config('zcustom.api_file_url') . '/' . $item['file_path'] }}">
                                <img class="object-contain w-24 h-24 cursor-pointer"
                                    src="{{ config('zcustom.api_file_url') . '/' . $item['file_path'] }}"
                                    alt="">
                            </a>

                            <button type="button"
                                class="block absolute right-1 top-1 mt-2 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border p-2 text-[var(--app-white)] dark:text-[var(--app-white-dark)] rounded button-file-delete-{{ $id }}"
                                id="button-file-delete-{{ $id . $item['id'] }}">
                                <i class="fa-solid fa-trash fa-lg text-red-500"></i>
                            </button>
                        @else
                            <a href="{{ config('zcustom.api_file_url') . '/' . $item['file_path'] }}">
                                <div class="inline-block">
                                    Download
                                </div>
                            </a>

                            <button type="button"
                                class="inline-block relative right-1 top-1 ml-2 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border p-2 text-[var(--app-white)] dark:text-[var(--app-white-dark)] rounded button-file-delete-{{ $id }}"
                                id="button-file-delete-{{ $id . $item['id'] }}">
                                <i class="fa-solid fa-trash fa-lg text-red-500"></i>
                            </button>
                        @endif

                    </div>
                @endforeach
            </div>

            <input type="hidden" name="file_delete_{{ $name }}" id="input-file-delete-{{ $id }}">
        @endif
    @elseif ($type == 'label')
        {{-- label --}}
        <label id="{{ $id }}" {{ $attributes->merge(['class' => $classes]) }}> {{ $value }}</label>
    @elseif ($type == 'label-plain')
        {{-- label plain --}}
        <label id="{{ $id }}"
            class="text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]">
            {{ $value }}</label>
    @elseif ($type == 'textarea')
        {{-- textarea --}}
        <textarea id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $required }}
            {{ $readonly }} onchange="{{ $onChange }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $value }}</textarea>
    @elseif ($type == 'dropdown')
        {{-- dropdown --}}
        <select id="{{ $id }}" name="{{ $name }}" {{ $required }} {{ $readonly }}
            onchange="{{ $onChange }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if ($requiredPlaceholder == 'true')
                <option value="" @if ((string) $value === '') {{ 'selected' }} @endif>
                    {{ __('-- Required --') }}</option>
            @elseif ($nullPlaceholder == 'true')
                <option value="" @if ((string) $value === '') {{ 'selected' }} @endif>
                    {{ !empty($placeholder) ? $placeholder : '(none)' }}</option>
            @endif

            @if (!empty($arrayLookup[$theKey]))
                @foreach ($arrayLookup[$theKey] as $keyLookup => $valueLookup)
                    <option value="{{ $keyLookup }}" @if ((string) $value === (string) $keyLookup) {{ 'selected' }} @endif>
                        @if ((string) $value === (string) $keyLookup)
                            {{ $isSearch ? (!empty($placeholder) ? $placeholder : '') : '' }}
                        @endif
                        {{ $valueLookup }}
                    </option>
                @endforeach
            @endif
        </select>
    @elseif ($type == 'radio')
        {{-- radio --}}
        @if (!empty($arrayLookup[$theKey]))
            @foreach ($arrayLookup[$theKey] as $keyLookup => $valueLookup)
                <label for="{{ $id . $keyLookup }}" class="w-full block mb-0 relative pointer-events-none">
                    <input type="radio" id="{{ $id . $keyLookup }}" name="{{ $name }}"
                        value="{{ $keyLookup }}" @if ((string) $value === (string) $keyLookup) {{ 'checked' }} @endif
                        {{ $required }} {{ $readonly }} onchange="{{ $onChange }}"
                        class="pointer-events-auto leading-[1.8] w-[15px] h-[15px] m-0 p-0 rounded-[50%] checked:bg-[var(--app-primary)] dark:checked:bg-[var(--app-primary-dark)] absolute top-[9px] left-0 border border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)]">

                    <div
                        class="pointer-events-auto inline-block relative p-[5px] pl-[25px] cursor-pointer text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] rounded-[5px]">
                        {{ $valueLookup }}
                    </div>
                </label>
            @endforeach
        @endif
    @elseif ($type == 'checkbox')
        {{-- checkbox --}}
        <label for="{{ $id }}" class="w-full block mb-0 relative pointer-events-none">
            <input type="checkbox" id="{{ $id }}" name="{{ $name }}[]" value="{{ '1' }}"
                @if ((string) $value === (string) '1') {{ 'checked' }} @endif {{ $required }} {{ $readonly }}
                onchange="{{ $onChange }}"
                class="pointer-events-auto leading-[1.8] w-[15px] h-[15px] m-0 p-0 checked:bg-[var(--app-primary)] dark:checked:bg-[var(--app-primary-dark)] absolute top-[9px] left-0 border border-[var(--app-gray-20)] transition">

            <div
                class="pointer-events-auto inline-block relative p-[5px] pl-[25px] cursor-pointer text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] rounded-[5px]">
                {{ $placeholder }}
            </div>
        </label>
    @elseif ($type == 'switch')
        {{-- switch --}}
        <label for="{{ $id }}" class="inline-block relative w-[60px] h-[34px]">
            <input type="checkbox" name="{{ $name }}" id="{{ $id }}" onchange="{{ $onChange }}"
                class="peer/checkbox opacity-0 w-0 h-0 checked:bg-[var(--app-primary)] dark:checked:bg-[var(--app-primary-dark)]">
            <span
                class="absolute cursor-pointer top-0 left-0 bottom-0 right-0 bg-[var(--app-gray-20)] dark:bg-[var(--app-gray-20-dark)] transition before:absolute before:content-[''] before:h-[26px] before:w-[26px] before:left-[4px] before:bottom-[4px] before:bg-[var(--app-white)] dark:before:bg-[var(--app-white-dark)] before:transition peer-checked/checkbox:bg-[var(--app-primary)] dark:peer-checked/checkbox:bg-[var(--app-primary-dark)] peer-checked/checkbox:before:translate-x-[26px] rounded-[34px] before:rounded-[50%]"></span>
        </label>
    @elseif ($type == 'multiplecheckbox')
        {{-- multiplecheckbox --}}
        @if (!empty($arrayLookup[$theKey]))
            @foreach ($arrayLookup[$theKey] as $keyLookup => $valueLookup)
                <label for="{{ $id . $keyLookup }}" class="w-full block mb-0 relative pointer-events-none">
                    <input type="checkbox" id="{{ $id . $keyLookup }}" name="{{ $name }}[]"
                        value="{{ $keyLookup }}" @if ((string) $value === (string) $keyLookup) {{ 'checked' }} @endif
                        {{ $required }} {{ $readonly }} onchange="{{ $onChange }}"
                        class="pointer-events-auto leading-[1.8] w-[15px] h-[15px] m-0 p-0 absolute top-[9px] left-0 border border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] transition">

                    <div
                        class="pointer-events-auto inline-block relative p-[5px] pl-[25px] cursor-pointer text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] rounded-[5px]">
                        {{ $valueLookup }}
                    </div>
                </label>
            @endforeach
        @endif
    @elseif ($type == 'multipleselect')
        {{-- multipleselect --}}
        <select id="{{ $id }}" name="{{ $name }}[]" {{ $required }} {{ $readonly }}
            onchange="{{ $onChange }}" multiple data-placeholder="{{ $placeholder }}" data-allow-clear="true"
            title="{{ $placeholder }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if ($requiredPlaceholder == 'true')
                <option value="" @if ((string) $value === '') {{ 'selected' }} @endif>
                    {{ __('-- Required --') }}</option>
            @elseif ($nullPlaceholder == 'true')
                <option value="" @if ((string) $value === '') {{ 'selected' }} @endif>(none)</option>
            @endif


            @if (!empty($arrayLookup[$theKey]))
                @foreach ($arrayLookup[$theKey] as $keyLookup => $valueLookup)
                    <option value="{{ $keyLookup }}"
                        @if (is_array($value) && in_array((string) $valueLookup, array_map('strval', $value))) {{ 'selected' }} @endif>
                        {{ $valueLookup }}</option>
                @endforeach
            @endif
        </select>
    @elseif ($type == 'multiplefile')
        {{-- multiplefile --}}
        <div class="flex items-center justify-center w-full">
            <label for="{{ $id }}"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                            upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $accept }} {{ $size }}</p>
                    <p class="text-sm font-medium mt-4 text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]"
                        id="{{ $id . 'imageName' }}">(choose file)</p>
                </div>
                <input type="file" id="{{ $id }}" name="{{ $name }}[]" {{ $required }}
                    {{ $readonly }} class="hidden" multiple accept="{{ $accept }}" />
            </label>
        </div>

        @if (!empty($arrayAttachment[$theKey]))
            <div class="flex flex-wrap items-center justify-start w-full gap-2 mt-2 "
                id="parent-file-delete-{{ $id }}">
                @foreach ($arrayAttachment[$theKey] as $item)
                    <div class="relative border border-solid rounded p-3 w-auto"
                        id="child-file-delete-{{ $id . $item['id'] }}">

                        @if (preg_match("/\.(jpe?g|png|gif|webp|svg)$/im", $item['file_path']))
                            <a data-type="image" data-fslightbox="{{ $theKey }}"
                                data-href="{{ config('zcustom.api_file_url') . '/' . $item['file_path'] }}">
                                <img class="object-contain w-24 h-24 cursor-pointer"
                                    src="{{ config('zcustom.api_file_url') . '/' . $item['file_path'] }}"
                                    alt="">
                            </a>

                            <button type="button"
                                class="block absolute right-1 top-1 mt-2 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border p-2 text-[var(--app-white)] dark:text-[var(--app-white-dark)] rounded button-file-delete-{{ $id }}"
                                id="button-file-delete-{{ $id . $item['id'] }}">
                                <i class="fa-solid fa-trash fa-lg text-red-500"></i>
                            </button>
                        @else
                            <a href="{{ config('zcustom.api_file_url') . '/' . $item['file_path'] }}">
                                <div class="inline-block">
                                    Download
                                </div>
                            </a>

                            <button type="button"
                                class="inline-block relative right-1 top-1 ml-2 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border p-2 text-[var(--app-white)] dark:text-[var(--app-white-dark)] rounded button-file-delete-{{ $id }}"
                                id="button-file-delete-{{ $id . $item['id'] }}">
                                <i class="fa-solid fa-trash fa-lg text-red-500"></i>
                            </button>
                        @endif

                    </div>
                @endforeach
            </div>

            <input type="hidden" name="file_delete_{{ $name }}"
                id="input-file-delete-{{ $id }}">
        @endif
    @endif

    @error($fieldName)
        <span class="leading-relaxed block mt-[5px] text-sm text-[var(--app-danger)] dark:text-[var(--app-danger-dark)]"
            role="alert">
            {{ $message }}
        </span>
    @enderror
</div>

<script>
    @if ($type == 'file')

        let {!! $id !!} = document.getElementById("{{ $id }}");
        let {!! $id !!}imageName = document.getElementById("{{ $id }}imageName");

        {!! $id !!}.addEventListener("change", () => {
            let {!! $id !!}inputImage = document.getElementById("{{ $id }}").files[0];

            {!! $id !!}imageName.innerText = {!! $id !!}inputImage.name;
        })

        document.addEventListener('DOMContentLoaded', () => {
            const deleteButtons = document.getElementsByClassName('button-file-delete-{{ $id }}');

            let index;
            for (index = 0; index < deleteButtons.length; index++) {
                deleteButtons[index].addEventListener('click', function(event) {
                    //event.preventDefault();
                    let deleteid = this.id.replace('button-file-delete-{{ $id }}', '');

                    const inputFileDeleteIds = document.getElementById(
                        'input-file-delete-{{ $id }}');
                    inputFileDeleteIds.value = inputFileDeleteIds.value + deleteid + '|';

                    const parentFileDelete = document.getElementById(
                        'parent-file-delete-{{ $id }}');

                    const childFileDelete = document.getElementById(
                        'child-file-delete-{{ $id }}' + deleteid);
                    parentFileDelete.removeChild(childFileDelete);
                });
            }
        });
    @endif

    @if ($type == 'multiplefile')

        let {!! $id !!} = document.getElementById("{{ $id }}");
        let {!! $id !!}imageName = document.getElementById("{{ $id }}imageName");

        {!! $id !!}.addEventListener("change", () => {
            let {!! $id !!}inputImage = document.getElementById("{{ $id }}").files;

            let name = '';
            for (const file of {!! $id !!}inputImage) {
                name += `${file.name}, `;
            }

            {!! $id !!}imageName.innerText = name;
        })


        // delete file
        document.addEventListener('DOMContentLoaded', () => {
            const deleteButtons = document.getElementsByClassName('button-file-delete-{{ $id }}');

            let index;
            for (index = 0; index < deleteButtons.length; index++) {
                deleteButtons[index].addEventListener('click', function(event) {
                    //event.preventDefault();
                    let deleteid = this.id.replace('button-file-delete-{{ $id }}', '');

                    const inputFileDeleteIds = document.getElementById(
                        'input-file-delete-{{ $id }}');
                    inputFileDeleteIds.value = inputFileDeleteIds.value + deleteid + '|';

                    const parentFileDelete = document.getElementById(
                        'parent-file-delete-{{ $id }}');
                    const childFileDelete = document.getElementById(
                        'child-file-delete-{{ $id }}' + deleteid);
                    parentFileDelete.removeChild(childFileDelete);
                });
            }
        });
    @endif

    @if ($type == 'dropdown')
        document.addEventListener('DOMContentLoaded', () => {

            @if ($select2)
                let obj = $('#{!! $id !!}');

                let idObj = (obj.attr('id'));
                if (!(idObj.search('dummy') > 0)) {
                    $('#{!! $id !!}').select2();
                }
            @endif
        });
    @endif

    @if ($type == 'multipleselect')
        document.addEventListener('DOMContentLoaded', () => {

            @if ($select2)
                let obj = $('#{!! $id !!}');

                let idObj = (obj.attr('id'));
                if (!(idObj.search('dummy') > 0)) {
                    $('#{!! $id !!}').select2();
                }
            @endif
        });
    @endif
</script>
