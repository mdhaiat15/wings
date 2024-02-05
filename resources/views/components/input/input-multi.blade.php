@push('body')
    <script>
        function init{{ $formName }}() {

        }
    </script>
@endpush

{{-- x-input.input --}}
<div class="flex">

    @foreach ($item['details'] as $keyDetail => $detail)
        @php
            $isHidden = !empty($detail['hidden']) ? ($detail['hidden'] == 'true' ? true : false) : false;
        @endphp

        <div class="flex-[0_1_100%] {{ $isHidden ? 'hidden' : '' }}">
            <x-input.input :item="$detail" :key="$keyDetail" :value="isset($value[$keyDetail]) ? $value[$keyDetail] : ''" :is-edit="!empty($value)" :form-name="$formName"
                :array-lookup="$arrayLookup" :array-attachment="!empty($arrayAttachment) ? $arrayAttachment : []" />
        </div>
    @endforeach

</div>
