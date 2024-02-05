@props(['text', 'lists', 'bulk-id', 'form-name'])

@php
    $classes = 'rounded-[5px] font-medium';

    if (empty($overrideColor)) {
        $classes .= ' text-[var(--app-bg-0)] dark:text-[var(--app-bg-0-dark)]';
    }

    if (empty($overrideBackground)) {
        $classes .= ' bg-[var(--app-primary)] dark:bg-[var(--app-primary-dark)]';
    }

    if (!empty($type)) {
        if ($type == 'small') {
            $classes .= ' text-sm py-1 px-2';
        } else {
            $classes .= ' text-lg py-2 px-5';
        }
    } else {
        $classes .= ' text-lg py-2 px-5';
    }

@endphp

<div class="relative inline-block text-left">
    <button id="dropdown-button"
        class="mr-3 inline-flex items-center justify-center rounded-[5px] font-medium text-gray-700 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] text-lg py-2 px-5 w-full sm:w-auto border border-gray-300 ">
        {{ $text ?? 'Action' }}
        <i class="fa-solid fa-angle-down ml-2"></i>
    </button>
    <div id="dropdown-menu"
        class="origin-top-right absolute right-0 mt-2 w-full sm:w-48 rounded-md shadow-lg bg-[var(--app-white)] ring-1 ring-black ring-opacity-5 hidden">
        @if (!empty($lists))
            <div class="py-2 p-2" role="menu" aria-orientation="vertical" aria-labelledby="dropdown-button">
                @foreach ($lists as $key => $value)
                    <div class="">
                        <form method="POST" action="{{ $value['link'] }}">
                            @if ($value['method'] != 'POST')
                                @method($value['method'])
                            @endif
                            @csrf
                            <input type="hidden" value="{{ $bulkId }}" name="massactionbulkid">
                            @if (array_key_exists('customvariable', $value))
                                @foreach ($value['customvariable'] as $customvariablekey => $customvariablevalue)
                                    <input type="hidden" name="{{ $customvariablekey }}"
                                        value="{{ $customvariablevalue }}">
                                @endforeach
                            @endif
                            <button type="submit"
                                class="flex items-baseline w-full rounded-md px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer"
                                id="action-{{ $key . $formName }}">

                                <i class="fa-solid {{ $value['icon'] ?? '' }} mr-1"></i>
                                {{ $value['text'] }}
                            </button>

                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if (!empty($lists))
                let modalBody = '';
                @foreach ($lists as $keyAction => $valueAction)
                @if ($valueAction['is-popup'])

                    const action{{ $keyAction }} = document.getElementById(
                        'action-{{ $keyAction . $formName }}');
                        modalBody = "{{ __('Are you sure you want to ') . $valueAction['text'] }}?";

                        registerClick(action{{ $keyAction }}, modalBody);
                    @endif
                @endforeach
            @endif
        });

        function registerClick(obj, modalBody, ) {
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
                        '<button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--app-primary)] dark:bg-[var(--app-primary-dark)] text-base font-medium text-[var(--app-bg-0)] dark:text-[var(--app-bg-0-dark)] hover:text-[var(--app-primary)] dark:hover:text-[var(--app-primary-dark)] hover:bg-[var(--app-accent)] dark:hover:hover:bg-[var(--app-accent-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--app-primary)] dark:focus:ring-[var(--app-primary-dark)] sm:ml-3 sm:w-auto sm:text-sm" id="action-btn">OK</button>');

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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownButton = document.getElementById('dropdown-button');
            const dropdownMenu = document.getElementById('dropdown-menu');
            let isDropdownOpen = true; // Set to true to open the dropdown by default, false to close it by default

            // Function to toggle the dropdown
            function toggleDropdown() {
                isDropdownOpen = !isDropdownOpen;
                if (isDropdownOpen) {
                    dropdownMenu.classList.remove('hidden');
                } else {
                    dropdownMenu.classList.add('hidden');
                }
            }

            // Initialize the dropdown state
            toggleDropdown();

            // Toggle the dropdown when the button is clicked
            dropdownButton.addEventListener('click', toggleDropdown);

            // Close the dropdown when clicking outside of it
            window.addEventListener('click', (event) => {
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                    isDropdownOpen = false;
                }
            });
        });
    </script>
</div>
