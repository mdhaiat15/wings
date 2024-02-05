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

                    @if (!empty($data) && $data->count() > 0)
                        <div class="sticky top-0 left-0 right-0 z-30 dark:bg-[var(--app-white-dark)]">

                            {{-- panel heading --}}
                            <x-panel-heading-editor title="{{ !empty($secondaryTitle) ? $secondaryTitle : $title }}"
                                :action="$secondaryAction" :form-name="$formName" :idx="$data['id'] ?? null" />
                        </div>

                        <div class="flex flex-row flex-wrap">
                            @foreach ($data as $item)
                                @php
                                    $productDisc = null;
                                    if (!empty($item->discount)) {
                                        $productDisc = $item->price - ($item->price * $item->discount) / 100;
                                    }

                                @endphp
                                <div class="flex flex-col justify-center items-center max-w-sm mx-auto my-8"
                                    onclick="openModal( {{ $item->id }} )">
                                    <img src="{{ asset($item->uploads?->file_path) }}" alt=""
                                        class="w-full h-[300px] bg-contain bg-center">
                                    <div class="w-56 md:w-64 bg-white -mt-10 shadow-lg rounded-lg overflow-hidden">
                                        <div class="py-2 text-center font-bold uppercase tracking-wide text-gray-800">
                                            {{ $item->name }}
                                            @if (!empty($productDisc))
                                                <p class="text-gray-800 font-bold line-through">
                                                    {{ 'Rp. ' . number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex justify-center py-2 px-3 bg-gray-400">
                                            @if (!empty($productDisc))
                                                <p class="text-gray-800 font-bold">
                                                    {{ 'Rp. ' . number_format($productDisc, 0, ',', '.') }}
                                                </p>
                                            @else
                                                <p class="text-gray-800 font-bold">
                                                    {{ 'Rp. ' . number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- modal -->
                                <div id="modal-overlay{{ $item->id }}"
                                    class="hidden absolute lg:top-0 lg:left-0 w-full h-full bg-gray-800 bg-opacity-75 flex justify-center"
                                    onclick="closeModal({{ $item->id }})">
                                    <div class="bg-gray-500 transition-opacity bg-opacity-75"></div>
                                    <span class="hidden sm:inline-block sm:align-middle">​</span>
                                    <div
                                        class="inline-block text-left bg-white rounded-lg overflow-hidden align-bottom transition-all transform shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full absolute">
                                        <div
                                            class="items-center w-full mr-auto ml-auto relative max-w-7xl md:px-12 lg:px-24">
                                            <div class="grid grid-cols-1">
                                                <div class="mt-4 mr-auto mb-4 ml-auto bg-white max-w-lg">
                                                    <div class="flex flex-col items-center pt-6 pr-6 pb-6 pl-6">
                                                        <img src="{{ asset($item->uploads?->file_path) }}"
                                                            class="object-cover object-center flex w-full h-52 mb-4">
                                                        <p
                                                            class="mt-8 text-2xl font-semibold leading-none text-black tracking-tighter lg:text-3xl">
                                                            {{ $item->name }}</p>


                                                        @if (!empty($productDisc))
                                                            <p class="text-gray-800 font-bold line-through">
                                                                {{ 'Rp. ' . number_format($item->price, 0, ',', '.') }}
                                                            </p>
                                                            <p class="text-gray-800 font-bold">
                                                                {{ 'Rp. ' . number_format($productDisc, 0, ',', '.') }}
                                                            </p>
                                                        @else
                                                            <p class="text-gray-800 font-bold">
                                                                {{ 'Rp. ' . number_format($item->price, 0, ',', '.') }}
                                                            </p>
                                                        @endif

                                                        <p class="text-gray-800 font-bold ">
                                                            {{ 'Dimension ' . $item->dimension }}
                                                        </p>
                                                        <form action="{{ route('product-list.store') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $item->id }}">
                                                            <input type="hidden" name="product_discount"
                                                                value="{{ $productDisc }}">
                                                            <input type="hidden" name="price"
                                                                value="{{ $item->price }}">
                                                            <button type="submit"
                                                                class="flex text-center items-center justify-center w-full pt-4 pr-10 pb-4 pl-10 text-base font-medium text-white bg-gray-600 rounded-xl transition duration-500 ease-in-out transform hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Add
                                                                Cart</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col justify-center items-center max-w-sm mx-auto my-8">
                            <p>Harap Membuat Produk Terlebih Dahulu!</p>
                        </div>
                    @endif


                </div>
                {{-- end panel --}}

            </div>
            {{-- end container --}}

        </div>

        <script>
            // Fungsi untuk membuka modal
            function openModal(id) {
                document.getElementById('modal-overlay' + id).classList.remove('hidden');
            }

            // Fungsi untuk menutup modal
            function closeModal(id) {
                document.getElementById('modal-overlay' + id).classList.add('hidden');
            }
        </script>

    </x-main-container>
</x-app-layout>
