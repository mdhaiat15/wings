<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-5">

                <div class="grid grid-cols-1 gap-12 mt-8 lg:col-gap-12 xl:col-gap-16 lg:mt-12 lg:grid-cols-5 lg:gap-16">
                    <div class="lg:col-span-3 lg:row-end-1">
                        <div class="">
                            <div class="lg:order-2 lg:ml-5 ">
                                <div class="max-w-2xl mx-auto overflow-hidden rounded-lg">
                                    <img class="object-cover w-full h-full max-w-full"
                                        src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-3 lg:row-span-2 lg:row-end-2">
                        <h1 class="font-bold leading-9 text-gray-900 sm:text-3xl">{{ $product->product_name }}</h1>

                        <p class="mt-2 text-sm font-medium text-gray-900 uppercase">by <span
                                class="font-semibold text-blue-500">WINGS</span></p>
                        <p class="mt-4 text-2xl">Discount {{ number_format($product->discount, 0, ',', '.') }}%</p>

                        <div class="flex items-center">
                            <p class="p-4 mr-4 text-4xl font-bold text-blue-900 rounded-lg bg-blue-50"><span
                                    class="text-lg text-blue-600 align-top">Rp </span>{{ !empty($product->discount) ? number_format(intval($product->price) - (intval($product->price) * intval($product->discount) /100), 0, ',', '.') : number_format($product->price, 0, ',', '.') }}</p>
                            <p class="flex flex-col">
                                @if (!empty($product->discount))
                                <del>

                                    <span class="text-xl font-semibold text-red-500">{{ 'Rp '. number_format($product->price, 0, ',','.') }}</span>
                                </del>
                                @endif
                            </p>
                        </div>
                        <p class="mt-8 text-base text-gray-600">
                           Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae omnis maiores provident mollitia iste velit hic cumque, inventore veritatis sequi?
                        </p>
                        <div
                            class="flex flex-col items-center justify-center py-4 mt-10 space-y-4 sm:flex-row sm:space-y-0">

                            <button type="button" id="add-cart-button" data-product-id="{{ $product->id }}" data-quantity="1"
                                class="inline-flex items-center justify-center px-12 py-3 text-base font-bold text-center text-white transition-all duration-200 ease-in-out bg-blue-600 border-2 border-transparent rounded-md bg-none focus:shadow hover:bg-gray-800">
                                Add to cart
                            </button>
                        </div>
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
