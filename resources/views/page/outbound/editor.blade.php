@push('header')
@endpush

@push('body')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const saveButton = document.getElementById('{{ $formName }}-button-save');
            if (saveButton != null) {
                saveButton.addEventListener('click', function(event) {
                    // if all input is valid, then disable the save button (to prevent double submission)
                    let validCheck = document.getElementById('form-{{ $formName }}').reportValidity();
                    if (validCheck) {
                        this.disabled = true;
                    }

                    const realbutton = document.getElementById('{{ $formName }}-real-submit-button');
                    realbutton.click();
                });
            }

            const saveAndNewButton = document.getElementById('{{ $formName }}-button-save-and-new');
            if (saveAndNewButton != null) {
                saveAndNewButton.addEventListener('click', function(event) {
                    document.getElementById('{{ $formName }}-save-and-new').value = 'yes';
                    const realbutton = document.getElementById('{{ $formName }}-real-submit-button');
                    realbutton.click();
                });
            }

        });
    </script>

    <script>
        async function handlerClickCheckStock(obj) {
            // this is button, not input
            let objId = obj.id;
            let trueObjId = objId.split("-").pop();
            let current = document.getElementById(obj.id);

            let productId = document.getElementById(trueObjId.replace('qty', 'product_id'));
            let expiredDate = document.getElementById(trueObjId.replace('qty', 'expired_date'));
            let sectionId = document.getElementById(trueObjId.replace('qty', 'inv_section_id'));

            if (productId.value == '' || productId.value == null || expiredDate.value == '' || expiredDate.value ==
                null || sectionId.value == '' || sectionId.value == null) {
                alert('Please fill product, exp date, location before check');
            }

            let url =
                `{{ route('stock.check-stock') }}?product_id=${productId.value}&expired_date=${expiredDate.value}&inv_section_id=${sectionId.value}`;
            let response = await fetch(url);
            let json = await response.json();

            current.innerHTML = `Stock ${json.data.data.qty_balance ?? 'NA'} - Check Stock`;

        }
    </script>
@endpush


<x-app-layout :bread-crumb-list="$breadCrumbList" :title="$title ?? ''" :action="$action ?? []" :alpine-active="$alpineActive ?? false" :alpine-mask="$alpineMask ?? false">
    <x-main-container>

        <div class="flex" x-data="">

            @if (!empty($sidebars))
                {{-- secondary sidebar --}}
                <x-secondary-sidebar :sidebars="$sidebars" />
            @endif

            {{-- container --}}
            <div class="flex-auto w-full">

                {{-- panel --}}
                <div
                    class="mb-5 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] rounded-md shadow border border-transparent">

                    <div class="sticky top-0 left-0 right-0 z-30 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)]">

                        {{-- panel heading --}}
                        <x-panel-heading-editor title="{{ !empty($secondaryTitle) ? $secondaryTitle : $title }}"
                            :action="$secondaryAction" :form-name="$formName" :idx="$data['id'] ?? null" />
                    </div>

                    <form action="{{ !empty($secondaryAction) ? $secondaryAction[0]['link'] : '' }}"
                        id="form-{{ $formName }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @if (!empty($data))
                            @method('PATCH')
                        @else
                            <input type="hidden" name="save_and_new" id="{{ $formName }}-save-and-new"
                                value="">
                        @endif

                        {{-- input form --}}
                        <div class="w-full relative overflow-x-auto px-[25px] py-[20px]">

                            {{-- information --}}
                            @include('components.alert-notification')

                            {{-- input fields --}}
                            <div class="block mx-[-15px]">

                                @foreach ($tableKey as $key => $item)
                                    @if (array_key_exists('header', $item) && $item['header'] === 'true')
                                        <x-input.header :title="!empty($item['title']) ? $item['title'] : ''" class="mx-[15px]" />
                                    @elseif (array_key_exists('detail', $item) && $item['detail'] === 'true')
                                        <x-input.input-detail :item="$item" :key="$key" :value="!empty($data[$key]) ? $data[$key] : []"
                                            :is-edit="!empty($data)" :form-name="$formName . CustomHelper::replaceString($key, '-')" :array-lookup="$arrayLookup"
                                            :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" />
                                    @elseif (array_key_exists('detail-table', $item) && $item['detail-table'] === 'true')
                                        <x-input.input-detail :item="$item" :key="$key" :value="!empty($data[$key]) ? $data[$key] : []"
                                            :is-edit="!empty($data)" :form-name="$formName . CustomHelper::replaceString($key, '-')" :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []"
                                            :is-table="true" />
                                    @elseif (array_key_exists('multi', $item) && $item['multi'] === 'true')
                                        <x-input.input-multi :item="$item" :key="$key" :value="isset($data[$key]) ? $data[$key] : []"
                                            :is-edit="!empty($data)" :form-name="$formName . CustomHelper::replaceString($key, '-')" :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []"
                                            :is-table="true" />
                                    @else
                                        <x-input.input :item="$item" :key="$key" :value="isset($data[$key]) ? $data[$key] : ''"
                                            :is-edit="!empty($data)" :form-name="$formName" :array-lookup="$arrayLookup"
                                            :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" />
                                    @endif
                                @endforeach

                            </div>
                        </div>

                        {{-- important, must have hidden submit for error directive to work --}}
                        <button type="submit" id="{{ $formName }}-real-submit-button" class="hidden">Save</button>
                    </form>

                </div>

            </div>

        </div>

    </x-main-container>
</x-app-layout>
