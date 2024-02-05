@props(['form-name', 'id'])

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const actiondelete = document.getElementById('{{ $formName }}-button-delete');
        modalBody = "{{ __('Are you sure you want to delete #') . $id }} ?";

        registerClickDelete(actiondelete, modalBody);
    });

    function registerClickDelete(obj, modalBody, ) {
        if (obj != null) {
            obj.addEventListener('click', function(event) {
                event.preventDefault();

                const genericModalTitle = document.getElementById('modal-title').innerHTML =
                    "{{ __('Before you proceed') }}";
                const genericModalBody = document.getElementById('modal-body').innerHTML = modalBody;

                const tmp = document.getElementById('action-btn');
                if (tmp != null) {
                    tmp.remove();
                }
                const modalButtonCancel = document.getElementById('cancel-btn');
                modalButtonCancel.insertAdjacentHTML("beforebegin",
                    '<button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--app-danger)] dark:bg-[var(--app-danger-dark)] text-base font-medium text-[var(--app-white)] dark:text-[var(--app-white-dark)] hover:text-[var(--app-danger)] dark:hover:text-[var(--app-danger-dark)] hover:bg-[var(--app-danger-secondary)] dark:hover:bg-[var(--app-danger-secondary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--app-danger)] dark:focus:ring-[var(--app-danger-dark)] sm:ml-3 sm:w-auto sm:text-sm" id="action-btn">OK</button>'
                );

                const modalActionButton = document.getElementById('action-btn');
                modalActionButton.innerHTML = 'Yes';
                modalActionButton.addEventListener('click', function(event) {
                    modalButtonCancel.click();
                    obj.form.submit();
                });

                document.getElementById('modal').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            });
        }
    }
</script>
