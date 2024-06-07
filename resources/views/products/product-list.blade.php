<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">

                <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

                    <div id="store" class="w-full z-30 top-0 px-6 py-1">
                        <div class="w-full container mx-auto flex flex-wrap items-start mt-0 px-2 py-3">

                            <p
                                class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl">
                                Store
                            </p>


                        </div>
                    </div>

                    <div
                        class="bg-white w-fit mx-auto grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">

                        @foreach ($products as $product)
                            <div
                                class="w-72 bg-white shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl">
                                <a href="{{ route('product-detail.show', $product->id) }}">
                                    <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                                        alt="Product" class="h-80 w-72 object-cover rounded-t-xl" />
                                </a>
                                <div class="px-4 py-3 w-72">
                                    <a href="{{ route('product-detail.show', $product->id) }}">
                                        <span class="text-gray-400 mr-3 uppercase text-xs">WINGS</span>
                                        <p class="text-lg font-bold text-black truncate block capitalize">
                                            {{ $product->product_name }}</p>
                                    </a>
                                    <div class="flex items-center">
                                        <a href="{{ route('product-detail.show', $product->id) }}"
                                            class="cursor-pointer">
                                            <p class="text-lg font-semibold text-black my-3">
                                                {{ !empty($product->discount) ? 'Rp ' . number_format(intval($product->price) - (intval($product->price) * intval($product->discount)) / 100, 0, ',', '.') : 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                            </p>
                                        </a>

                                        @if (!empty($product->discount))
                                            <del>
                                                <p class="text-sm text-gray-600 cursor-auto ml-2">
                                                    {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}</p>
                                            </del>
                                        @endif
                                        <div class="ml-auto">
                                            <button id="add-cart-button" data-product-id="{{ $product->id }}" data-quantity="1">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z" />
                                                    <path
                                                        d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>
                    <div class="pt-10">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#add-cart-button', function() {
                let productId = $(this).data('product-id');
                let quantity = 1;
                let token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('add-to-cart') }}',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        _token: token
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Product added to cart successfully',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong!',
                            text: 'Failed to add product to cart',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
