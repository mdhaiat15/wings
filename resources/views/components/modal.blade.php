<div class="flex items-center justify-center">
    <div class="modal-container">

        <!-- Background overlay -->
        <div id="overlay" class="fixed z-40 inset-0 transition-opacity" aria-hidden="true" style="display: none">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal -->
        <div id="modal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none">
            <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Modal panel -->
                <div class="w-full relative top-[40vh] inline-block align-bottom bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Modal content -->
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[var(--app-danger-secondary)] dark:bg-[var(--app-danger-secondary-dark)] sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-question text-[var(--app-danger)] dark:text-[var(--app-danger-dark)]" id="modal-icon"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]" id="modal-title"> Headline
                                </h3>
                                <div class="mt-2 text-sm text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]" id="modal-body">
                                    <p class="text-sm text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)]"> Body message </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button id="action-btn" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--app-danger)] dark:bg-[var(--app-danger-dark)] text-base font-medium text-[var(--app-white)] dark:text-[var(--app-white-dark)] hover:bg-[var(--app-danger-secondary)] dark:hover:bg-[var(--app-danger-secondary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--app-danger)] dark:focus:ring-[var(--app-danger-dark)] sm:ml-3 sm:w-auto sm:text-sm">
                            Action </button>
                        <button id="cancel-btn" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] shadow-sm px-4 py-2 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] text-base font-medium text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] hover:bg-[var(--app-bg-0)] dark:hover:bg-[var(--app-bg-0-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--app-bg-5)] dark:focus:ring-[var(--app-bg-5-dark)] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        const modalButtonCancel = document.getElementById('cancel-btn');
            modalButtonCancel.innerHTML = 'Cancel';
            modalButtonCancel.addEventListener('click', function(event) {
                document.getElementById('modal').style.display = 'none';
                const tmp = document.getElementById('action-btn');
                if (tmp != null) {
                    tmp.remove();
                }

        document.getElementById('overlay').style.display = 'none';

            });

    });
  </script>
