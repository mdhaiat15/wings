@php
    $classes = 'm-0 p-0 w-full align-top';
    $addedClass = '';

    if ($isFilter) {
        $item['type'] = 'dropdown';
        $item['inputonly'] = 'true';
        $item['placeholder'] = $item['title'];
        $item['null-placeholder'] = 'true';

        $classes .= ' p-1';
        $addedClass = ' px-[10px] py-[3px] font-normal text-sm leading-normal overflow-ellipsis';
    }

    if ($isSearch) {
        if ($key === 'searchValue') {
            $item['type'] = 'search';
        } else {
            $item['type'] = 'dropdown';
        }
        $item['inputonly'] = 'true';

        $classes .= ' p-1';
        $addedClass = ' px-[10px] py-[3px] font-normal text-sm leading-normal overflow-ellipsis';
    }

    if (!($item['type'] == 'hidden') && !$isFilter && !$isSearch) {
        $classes .= ' mb-[20px] px-[15px] inline-block ';
    }

    $id = $getId();
    $name = $getName();
    $fieldName = $getFormFieldName();

    $required = !empty($item['required']) ? ($item['required'] == 'true' ? 'required="required"' : '') : '';
    $readonly = !empty($item['readonly']) ? ($item['readonly'] == 'true' ? 'readonly="readonly"' : '') : '';
    $inputonly = !empty($item['inputonly']) ? ($item['inputonly'] == 'true' ? true : false) : false;
    $select2 = !empty($item['select2']) ? ($item['select2'] == 'true' ? true : false) : false;

    $alpineMask = null;
    if (!empty($item['x-mask'])) {
        $alpineMask = 'x-mask="' . $item['x-mask'] . '"';
    } elseif (!empty($item['x-mask-dynamic'])) {
        if ($item['x-mask-dynamic'] == 'money') {
            $alpineMask = 'x-mask:dynamic="$money($input, \'.\', \',\', 8)"';
        } else {
            $alpineMask = 'x-mask:dynamic="' . $item['x-mask-dynamic'] . '"';
        }
    }

    $value = !CustomHelper::isEmptyButNotZero($value) ? $value : (array_key_exists('default-value', $item) && $item['default-value'] != '' ? $item['default-value'] : '');
@endphp


{{-- input field --}}
<div {{ $attributes->merge(['class' => $classes]) }}>

    @if (!$inputonly)
        <x-input.label 
            :id="$id" 
            :title="!empty($item['title']) ? $item['title'] : ''" 
            :description="!empty($item['description']) ? $item['description'] : ''"
            :tooltip="!empty($item['tooltip']) ? $item['tooltip'] : ''"
            :required="$required"
        />
    @endif

    <x-input.input-field 
        :key="$key" 
        :id="$id" 
        :name="$name" 
        :type="!empty($item['type']) ? $item['type'] : ''" 
        :value="$value"
        :field-name="$fieldName" 
        :required="$required" 
        :readonly="$readonly" 
        :placeholder="!empty($item['placeholder']) ? $item['placeholder'] : ''" 
        :required-placeholder="!empty($item['required-placeholder']) ? $item['required-placeholder'] : ''"
        :null-placeholder="!empty($item['null-placeholder']) ? $item['null-placeholder'] : ''" 
        :array-lookup="!empty($arrayLookup) ? $arrayLookup : []" 
        :array-lookup-key="!empty($item['array-lookup-key']) ? $item['array-lookup-key'] : ''" 
        :accept="!empty($item['accept']) ? $item['accept'] : ''" 
        :size="!empty($item['size']) ? $item['size'] : ''"
        :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" 
        :select2="$select2" 
        :is-filter="$isFilter" 
        :is-search="$isSearch" 
        :on-change="!empty($onChange) ? $onChange : ''"
        :alpine-mask="$alpineMask" class="{{ $addedClass }}" />

        @if (!empty($item['append-button']))
        @php
            $buttonClass = "row-".Str::slug($item['append-button']['title'])."-".$formName;
            $buttonId = "table-row-".Str::slug($item['append-button']['title'])."-".$id;
            $buttonTitle = !empty($item['append-button']['title']) ? $item['append-button']['title'] : '';
            $buttonIcon = !empty($item['append-button']['icon']) ? $item['append-button']['icon'] : '';
            $buttonOnClick = !empty($item['append-button']['onclick']) ? $item['append-button']['onclick'] : '';
        @endphp

            <div class="relative align-middle flex flex-col flex-wrap mt-2 ">
                <x-input.button-detail.button-td 
                    class="{{ $buttonClass }}"
                    id="{{ $buttonId }}" 
                    onclick="{{ $buttonOnClick }}"
                    >
                    <i
                        class="fa-solid {{ $buttonIcon }} text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]"></i>
                    {{ $buttonTitle }}
                </x-input.button-detail.button-td>
            </div>

        @endif
</div>
