<x-app-layout :bread-crumb-list="$breadCrumbList" :title="$title" :action="$action" :hide-title="$hideTitle ?? false">
    <x-main-container>

        <div class="flex flex-col-reverse md:flex-row">

            @if (!empty($sidebars))
                {{-- secondary sidebar --}}
                <x-secondary-sidebar :sidebars="$sidebars" />
            @endif

            {{-- container --}}
            <div class="flex-auto">

                {{-- panel --}}
                <div
                    class="mb-5 bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] rounded-md shadow border border-transparent">

                    {{-- information --}}
                    @include('components.alert-notification')

                    <div class="sticky top-0 left-0 right-0 z-30 dark:bg-[var(--app-white-dark)]">

                        {{-- panel heading --}}
                        <x-panel-heading-editor title="{{ !empty($secondaryTitle) ? $secondaryTitle : $title }}"
                            :action="$secondaryAction" :form-name="$formName" :idx="$data['id'] ?? null" />
                    </div>
                    <div class="h-full bg-gray-100 pt-5">
                        @if (!empty($data) && $data->count() > 0)
                            <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">
                                <div class="rounded-lg md:w-2/3">

                                    @foreach ($data as $item)
                                        <form action="{{ route('cart.update', [$formName => $item['id']]) }}"
                                            method="POST">
                                            @method('PATCH')
                                            @csrf

                                            <div>
                                                <div
                                                    class="justify-between mb-2 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start">
                                                    <img src="{{ asset($item->product->uploads?->file_path) }}"
                                                        alt="product-image"
                                                        class="w-full h-[150px] bg-contain rounded-lg sm:w-40" />
                                                    <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                                                        <div class="mt-5 sm:mt-0">
                                                            <h2 class="text-lg font-bold text-gray-900">
                                                                {{ $item->product?->name }}
                                                            </h2>
                                                            @php
                                                                if (!empty($item->product?->discount) && $item->product?->discount != 0) {
                                                                    $productPrice = $item->product?->price - ($item->product?->price * $item->product?->discount) / 100;
                                                                } else {
                                                                    $productPrice = $item->product?->price * $item->quantity;
                                                                }
                                                            @endphp

                                                            @if (!empty($item->product?->discount) && $item->product?->discount != 0)
                                                                <p class="text-gray-800">
                                                                    <span
                                                                        class="line-through">{{ 'Rp. ' . number_format($item->product?->price, 0, ',', '.') }}</span>
                                                                    <span
                                                                        class="text-red-500">{{ 'Disc ' . $item->product?->discount . '%' }}</span>
                                                                </p>
                                                                <p class="text-gray-800">
                                                                    {{ 'Rp. ' . number_format($productPrice, 0, ',', '.') }}
                                                                </p>
                                                            @else
                                                                <p class="text-gray-800">
                                                                    {{ 'Rp. ' . number_format($item->product?->price, 0, ',', '.') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
                                                            <div class="flex items-center border-gray-100">
                                                                <input type="hidden" name="price"
                                                                    value="{{ $item->product?->price }}">
                                                                <input
                                                                    class="h-8 w-20 border bg-white text-center text-xs outline-none"
                                                                    type="number"
                                                                    value="{{ !empty($item->quantity) ? $item->quantity : 1 }}"
                                                                    min="1" name="quantity" />
                                                                <span
                                                                    class="rounded-r bg-gray-100 py-1 px-3 duration-100">
                                                                    PCS </span>
                                                            </div>
                                                            <div class="flex items-center space-x-4">
                                                                <input type="hidden" name="sub_total"
                                                                    value="{{ $productPrice }}">
                                                                <p class="text-sm">Sub Total Rp.
                                                                    {{ number_format($productPrice, 0, ',', '.') }}
                                                                </p>
                                                                <a href="#"
                                                                    onclick="actionRemove({{ $item->id }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke-width="1.5" stroke="currentColor"
                                                                        class=" h-5 w-5 cursor-pointer duration-150 hover:text-red-500">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <x-input-button-item key="-button-save" :form-name="$formName" text="Save"
                                                override-color="true"
                                                class="text-white w-full sm:w-auto mb-4 flex justify-end" />
                                        </form>
                                    @endforeach
                                </div>
                                <!-- Sub total -->
                                <div class="mt-6 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3">
                                    <div class="flex justify-between">
                                        <p class="text-lg font-bold">Total</p>
                                        <div class="">
                                            <p class="mb-1 text-lg font-bold">
                                                {{ 'Rp. ' . number_format($subTotalCart, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <button onclick="actionCheckout()"
                                        class="mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600">Check
                                        out</button>
                                </div>
                            </div>

                            {{-- modal delete cart --}}
                            <div class="relative z-10" hidden aria-labelledby="modal-title" role="dialog"
                                aria-modal="true" id="modalDelete">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                                <div class="fixed z-10 inset-0 overflow-y-auto">
                                    <div
                                        class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                                        <div
                                            class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
                                            <div class="sm:flex sm:items-start">
                                                <div
                                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                        id="modal-title">Remove This Cart</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Are you sure you want to delete
                                                            your This Cart?</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                <form action="" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button href="" id="removeAction"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Remove</button>
                                                </form>
                                                <button type="button" onclick="actionRemove(0)"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- modal check out --}}
                            <div class="relative z-10" hidden aria-labelledby="modal-title" role="dialog"
                                aria-modal="true" id="modalCheckout">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                                <div class="fixed z-10 inset-0 overflow-y-auto">
                                    <div
                                        class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                                        <div
                                            class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
                                            <div class="sm:flex sm:items-start">
                                                <div
                                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6 text-green-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                        id="modal-title">Pemberitahuan</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Apakah kamu yakin ingin check
                                                            out?</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                <form action="{{ route('cart.check-out') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="cartProduct"
                                                        value="{{ $cartProduct }}">
                                                    <button href="" id="checkoutAction"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Check
                                                        Out</button>
                                                </form>
                                                <button type="button" onclick="actionCheckout()"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col justify-center items-center max-w-sm mx-auto pb-8">
                                <p>Keranjang Kosong </p>
                                <p>Silahkan Pilh Produk Terlebih Dahulu!</p>
                            </div>
                        @endif
                    </div>


                </div>
                {{-- end panel --}}

            </div>
            {{-- end container --}}

        </div>
        <script>
            function actionRemove(id) {
                var modal = document.getElementById('modalDelete');
                var routeName = "{{ route('cart.destroy', '') }}" + "/" + id;
                var removeLinks = document.getElementById('removeAction');

                if (modal.hasAttribute('hidden')) {
                    removeLinks.parentElement.action = routeName;
                    modal.removeAttribute('hidden');
                } else {
                    modal.setAttribute('hidden', 'true');
                    removeLinks.parentElement.action = '';
                }
            }

            function actionCheckout() {
                var modal = document.getElementById('modalCheckout');
                var routeName = "{{ route('cart.check-out') }}";
                var checkoutLinks = document.getElementById('checkoutAction');

                if (modal.hasAttribute('hidden')) {
                    checkoutLinks.parentElement.action = routeName;
                    modal.removeAttribute('hidden');
                } else {
                    modal.setAttribute('hidden', 'true');
                    checkoutLinks.parentElement.action = '';
                }
            }
        </script>
    </x-main-container>
</x-app-layout>
