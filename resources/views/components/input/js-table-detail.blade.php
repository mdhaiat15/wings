<script>
    document.addEventListener('DOMContentLoaded', () => {
        let globalRowCounter_{{ $formName }} = {{ $currentCount - 1 }};
        let tmpTrDummyElement = document.getElementById('table-row-{{ $formName . '_999999' }}');

        let globalTrDummy_{{ $formName }};

        if (tmpTrDummyElement != null) {
            globalTrDummy_{{ $formName }} = tmpTrDummyElement.innerHTML;
            tmpTrDummyElement.parentNode.removeChild(tmpTrDummyElement);
        }

        const addButton = document.getElementById('table-row-button-add-{{ $formName }}');
        if (addButton != null) {
            addButton.addEventListener('click', function(event) {
                globalRowCounter_{{ $formName }}++;
                let rowCounter = globalRowCounter_{{ $formName }};

                let trDummy = globalTrDummy_{{ $formName }};
                trDummy = trDummy.replace(/_dummy/gi, "");
                trDummy = trDummy.replace(/999999/gi, rowCounter);

                const tmpTable = document.getElementById('table-{{ $formName }}')
                    .getElementsByTagName('tbody')[0];
                const tmpRow = tmpTable.insertRow(-1);
                tmpRow.setAttribute("id", 'table-row-{{ $formName }}_' + rowCounter);
                tmpRow.innerHTML = trDummy;

                const newRowDeleteButton = document.getElementById(
                    'table-row-button-delete-{{ $formName }}_' + rowCounter);
                if (newRowDeleteButton != null) {
                    newRowDeleteButton.addEventListener('click', rowDelete);
                }

                {!! $globalHook !!}
                @foreach ($listSelect2 as $keyList => $value)

                    $('#{{ $formName }}_' + rowCounter + '_{{ $keyList }}').select2();
                @endforeach

                const inserted_index = document.getElementById('inserted_index_{{ $formName }}');
                if (inserted_index != null) {
                    inserted_index.value = rowCounter;
                }
            });
        }

        function rowDelete(event) {
            let deleteid = this.id.replace('table-row-button-delete-{{ $formName }}_', '');
            let element = document.getElementById('table-row-{{ $formName }}_' + deleteid);
            element.parentNode.removeChild(element);

            {!! $rowDeleteGlobalHook !!}
        }

        const deleteButtons = document.getElementsByClassName('row-button-delete-{{ $formName }}');
        for (let index = 0; index < deleteButtons.length; index++) {
            deleteButtons[index].addEventListener('click', rowDelete);
        }

    });
</script>
