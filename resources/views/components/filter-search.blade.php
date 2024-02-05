@props(['table-key-list', 'data-filter', 'form-name', 'array-lookup', 'array-attachment'])

@php
    $classes = 'bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] border-solid border-b border-t border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] px-[25px] py-[10px] -mt-[1px]';
@endphp


<div {{ $attributes->merge(['class' => $classes]) }}>

    {{-- filter row --}}
    <div class="flex justify-between -mr-[10px]">

        {{-- item --}}
        <div class="flex flex-row mb-[10px] mr-[10px] flex-1 min-w-0 relative">

            @foreach ($tableKeyList as $key => $item)
                @if (array_key_exists('show-filter', $item) && $item['show-filter'] == 1)
                    @php
                        $key = array_key_exists('key', $item) && !empty($item['key']) ? $item['key'] : $key;
                    @endphp

                    <x-input.input :item="$item" :key="$key" :value="!empty(request()->get($key)) ? request()->get($key) : ''" :is-edit="!empty($dataFilter)"
                        :form-name="$formName" :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" :is-filter="true" :on-change="'searchFilter(this)'" />
                @endif
            @endforeach
        </div>

    </div>

    {{-- filter search --}}
    <div class="flex justify-between -mr-[10px]">

        {{-- item --}}
        <div class="flex flex-row mb-[10px] mr-[10px] flex-1 min-w-0 relative">

            @foreach ($tableKeyList as $key => $item)
                @if (array_key_exists('show-search', $item) && $item['show-search'] == 1)
                    @php
                        $arrayLookup['searchKey'][$key] = $item['title'];
                        $searchKey['title'] = 'Search key';
                    @endphp
                @endif

                @if (array_key_exists('default-search', $item) && $item['default-search'] == 'true')
                    @php
                        $searchKey['default-value'] = $key;
                    @endphp
                @endif
            @endforeach
            @php

                $searchValue['title'] = 'Search value';
                $searchValue['placeholder'] = 'Search';

                $perPage['title'] = 'Per Page';
                $perPage['placeholder'] = 'Show ';
                $arrayLookup['perPage'] = [
                    10 => 10,
                    20 => 20,
                    50 => 50,
                    100 => 100,
                    200 => 200,
                ];
            @endphp

            @if (!empty($searchKey))
                <x-input.input :item="$searchValue" :key="'searchValue'" :value="!empty($dataFilter['searchValue']) ? $dataFilter['searchValue'] : ''" :is-edit="!empty($dataFilter)"
                    :form-name="$formName" :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" :is-search="true" />
                <x-input.input :item="$searchKey" :key="'searchKey'" :value="!empty($dataFilter['searchKey']) ? $dataFilter['searchKey'] : ''" :is-edit="!empty($dataFilter)"
                    :form-name="$formName" :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" :is-search="true" />
            @endif

            <x-input.input :item="$perPage" :key="'perPage'" :value="!empty($dataFilter['perPage']) ? $dataFilter['perPage'] : ''" :is-edit="!empty($dataFilter)"
                :form-name="$formName" :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" :is-search="true" :on-change="'searchFilter(this)'" />

            <button id="search-btn"
                class="inline-flex items-baseline gap-1 px-[10px] py-[5px] font-normal text-sm text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] leading-normal shadow border rounded-[5px] border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] focus:border-[var(--app-primary)] dark:focus:border-[var(--app-primary-dark)]"
                type="submit">
                <i class="fa-solid fa-search"></i> <span>Search</span>
            </button>
        </div>

    </div>
</div>

<script>
    function searchFilter(obj) {
        const searchBtn = document.getElementById('search-btn');
        searchBtn.click();
    }
</script>
