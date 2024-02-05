@php
    $requiredDetail = !empty($item['required']) ? ($item['required'] == 'true' ? 'required="required"' : '') : '';
    $listSelect2 = !empty($item['details'])
        ? collect($item['details'])
            ->map(function ($item) {
                return !empty($item['select2']) ? $item['select2'] : null;
            })
            ->filter()
            // ['key' => '']
            ->map(function ($item) {
                return '';
            })
            ->all()
        : '';
@endphp

@push('body')
    <script>
        function init{{ $formName }}() {
            @if ($requiredDetail)
                rowMustOne();
            @endif
        }

        @if ($requiredDetail)
            function rowMustOne() {
                const trLength = document.getElementById('table-{{ $formName }}')
                    .getElementsByTagName('tbody')[0].getElementsByTagName('tr').length;

                if (trLength < 1) {
                    const addButton = document.getElementById('table-row-button-add-{{ $formName }}');
                    addButton.click();
                }
            }
        @endif
    </script>

    <x-input.js-table-detail :key="$key" :form-name="$formName" :current-count="empty($value) ? 0 : sizeof($value)" globalHook=""
        rowDeleteGlobalHook="init{{ $formName }}();" :listSelect2="$listSelect2" />

    <script>
        @if (empty($value) && empty(old($formName)) && !$isTable && $requiredDetail)
            document.addEventListener('DOMContentLoaded', () => {
                // auto add new row
                const addButton = document.getElementById('table-row-button-add-{{ $formName }}');
                addButton.click();
            });
        @endif
    </script>
@endpush

{{-- x-input.input --}}
<div class="m-0 p-0 w-full inline-block mb-[20px] px-[15px] align-top">

    {{-- x-input.label --}}
    <x-input.label id="$id" :title="!empty($item['title']) ? $item['title'] : ''" :description="!empty($item['description']) ? $item['description'] : ''" :tooltip="!empty($item['tooltip']) ? $item['tooltip'] : ''" :required="$requiredDetail" />

    {{-- field control --}}
    <div class="w-full">

        <div class="w-full">

            {{-- table responsive --}}
            <div
                class="w-full shadow border border-solid border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] overflow-x-auto">

                <table id="table-{{ $formName }}"
                    class="w-full rounded border-collapse text-sm border-none bg-transparent mb-0">

                    <thead>
                        <tr>
                            <x-input.table-th-detail title="ID" class="hidden " />

                            @foreach ($item['details'] as $keyDetail => $detail)
                                @php
                                    $required = !empty($detail['required']) ? ($detail['required'] == 'true' ? 'required="required"' : '') : '';
                                @endphp

                                <x-input.table-th-detail title="{{ $detail['title'] }}"
                                    description="{{ $detail['description'] ?? '' }}" class="" :required="$required" />
                            @endforeach

                            @if (!$isTable)
                                <x-input.table-th-detail title="" class="" />
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @if ((!empty($value) && sizeof($value) > 0) || (!empty(old($formName)) && sizeof(old($formName)) > 0))

                            @php
                                $items = CustomHelper::mergeAndUniqueBy($value, old($formName), 'id');
                            @endphp
                            @foreach ($items as $keyItem => $dataItem)
                                <tr id="table-row-{{ $formName . '_' . $keyItem }}">

                                    @foreach ($item['details'] as $keyDetail => $detail)
                                        <td
                                            class="border-b border-r border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] last:border-r-0 pt-4">

                                            @if ($loop->first)
                                                @php
                                                    $detailId = [];
                                                    $detailId['type'] = 'hidden';
                                                    $detailId['inputonly'] = 'true';
                                                @endphp
                                                <x-input.input :item="$detailId" key="id" :value="!empty($dataItem['id']) ? $dataItem['id'] : ''"
                                                    :is-edit="!empty($dataItem)" :form-name="$formName" :array-lookup="$arrayLookup"
                                                    :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" :rowId="$keyItem" />
                                            @endif

                                            <x-input.input :item="$detail" :key="$keyDetail" :value="!empty($dataItem[$keyDetail]) ? $dataItem[$keyDetail] : ''"
                                                :is-edit="!empty($dataItem)" :form-name="$formName" :array-lookup="$arrayLookup"
                                                :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" :rowId="$keyItem" class="min-w-max" />
                                        </td>
                                    @endforeach

                                    @if (!$isTable)
                                        <td
                                            class="border-b border-r border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] last:border-r-0">
                                            <div class="relative align-middle flex flex-col flex-wrap">
                                                <x-input.button-detail.button-td
                                                    class="row-button-delete-{{ $formName }}"
                                                    id="table-row-button-delete-{{ $formName . '_' . $loop->index }}">
                                                    <i
                                                        class="fa-solid fa-trash text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]"></i>
                                                </x-input.button-detail.button-td>
                                            </div>

                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif

                        <tr id="table-row-{{ $formName . '_999999' }}" class="hidden">
                            @foreach ($item['details'] as $keyDetail => $detail)
                                <td
                                    class="border-b border-r border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] last:border-r-0 pt-4">
                                    <x-input.input :item="$detail" :key="$keyDetail . '_dummy'" rowId="999999"
                                        :value="!empty($data[$keyDetail]) ? $data[$keyDetail] : ''" :is-edit="!empty($data)" :form-name="$formName" :array-lookup="$arrayLookup"
                                        :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" />
                                </td>
                            @endforeach

                            <td
                                class="border-b border-r border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] last:border-r-0">
                                <div class="relative align-middle flex flex-col flex-wrap">
                                    <x-input.button-detail.button-td class="row-button-delete-{{ $formName }}"
                                        id="table-row-button-delete-{{ $formName . '_999999' }}">
                                        <i
                                            class="fa-solid fa-trash text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]"></i>
                                    </x-input.button-detail.button-td>
                                </div>
                            </td>

                        </tr>
                    </tbody>

                </table>

            </div>
            {{-- field footer --}}
            <div class="mt-[10px]">

                <div class="relative align-middle flex flex-wrap">
                    @if (!$isTable)
                        <x-input.button-detail.button-item class="table-row-button-add-{{ $formName }}"
                            id="table-row-button-add-{{ $formName }}">
                            Add Row
                        </x-input.button-detail.button-item>
                    @endif
                </div>

            </div>

        </div>

    </div>

</div>
