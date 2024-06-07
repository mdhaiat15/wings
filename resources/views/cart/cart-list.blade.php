<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($cart->count() == 0)
                <div class="bg-white flex items-center justify-center">
                    <div class="py-20">
                        <div class="flex flex-col items-center">
                            <h1 class="text-2xl font-bold text-blue-600 md:text-4xl">Wah, Keranjang Anda Kosong</h1>

                            <p class="md:text-md mb-8 text-center text-gray-500">
                                Daripada dianggurin, isi saja dengan produk <span class="text-indigo-700">WINGS</span>.
                                Lihat-lihat dulu, siapa tahu ada yang kamu
                                suka!
                            </p>

                            <a class="bg-blue-100 px-6 py-2 text-sm font-semibold text-blue-800"
                                href="{{ route('product.index') }}">cari product</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white p-5 flex shadow-md ">

                    <div class="w-full bg-white px-10 py-10">
                        <div class="flex justify-between border-b pb-8">
                            <h1 class="font-semibold text-2xl">Shopping Cart</h1>
                            <h2 class="font-semibold text-2xl">{{ $totalCart }} Items</h2>
                        </div>
                        @foreach ($cart as $item)
                            <div class="md:flex items-strech py-8 md:py-10 lg:py-8 border-t border-gray-50">
                                <div class="md:w-4/12 2xl:w-1/4 w-full">
                                    <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                                        alt="Black Leather Purse"
                                        class="h-full object-center object-cover md:block hidden" />
                                    <img src="https://images.unsplash.com/photo-1646753522408-077ef9839300?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8NjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
                                        alt="Black Leather Purse"
                                        class="md:hidden w-full h-full object-center object-cover" />
                                </div>
                                <div class="md:pl-3 md:w-8/12 2xl:w-3/4 flex flex-col justify-center">
                                    <p class="text-xs leading-3 text-gray-800 md:pt-0 pt-4">
                                        {{ $item->product->product_code }}</p>
                                    <div class="flex items-center justify-between w-full">
                                        <p class="text-base font-black leading-none text-gray-800">
                                            {{ $item->product->product_name }}</p>
                                            <div class="flex gap-4">
                                                <input type="number" name="quantity" class="quantity-input" min="1"
                                                    max="9999" value="{{ $item->quantity }}"
                                                    data-cart-id="{{ $item->id }}" />
                                                <p class="uppercase">{{ $item->product->unit }}</p>
                                            </div>
                                    </div>
                                    <p class="text-xs leading-3 text-gray-600 pt-2">Dimension:
                                        {{ $item->product->dimension }}</p>
                                    <div class="flex items-center justify-between pt-5">
                                        <div class="flex itemms-center">
                                            <p class="text-xs leading-3 underline text-red-500 cursor-pointer remove-cart"
                                                data-cart-id="{{ $item->id }}">
                                                Remove
                                            </p>
                                        </div>

                                        <div class="flex flex-col">
                                            @php
                                                $hargaKeseluruhan = !empty($item->product->discount)
                                                    ? intval($item->product->price) -
                                                        (intval($item->product->price) *
                                                            intval($item->product->discount)) /
                                                            100
                                                    : $item->product->price;
                                            @endphp
                                            <p class="text-base text-right mb-2 font-black leading-none text-gray-800">
                                                {{ 'Rp ' . number_format($hargaKeseluruhan, 0, ',', '.') }}
                                            </p>
                                            <p>Sub Total: <span
                                                    class="subtotal">{{ number_format($hargaKeseluruhan * $item->quantity, 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('product.index') }}"
                            class="flex font-semibold text-indigo-600 text-sm mt-10">
                            <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
                                <path
                                    d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
                            </svg>
                            Continue Shopping
                        </a>
                        <div class="w-full px-8 py-10 flex justify-center flex-col">
                            <div class="border-t mt-8">
                                <div class="flex font-semibold justify-between py-6 text-sm uppercase">
                                    <span>Total Pembayaran</span>
                                    <span id="total-payment" class="capitalize">Rp
                                        {{ number_format($totalPayment, 0, ',', '.') }}</span>
                                </div>
                                <button id="checkout-btn"
                                    class="bg-indigo-500 font-semibold hover:bg-indigo-600 py-3 text-sm text-white uppercase w-full">
                                    Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.remove-cart', function() {
                let id = $(this).data('cart-id');
                let token = $('meta[name="csrf-token"]').attr('content');

                Swal.fire({
                    title: 'Apakah Kamu Yakin?',
                    text: "ingin menghapus data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'TIDAK',
                    confirmButtonText: 'YA, HAPUS!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        console.log('test');

                        $.ajax({

                            url: `/remove-cart/${id}`,
                            type: "DELETE",
                            cache: false,
                            data: {
                                "_token": token
                            },
                            success: function(response) {

                                Swal.fire({
                                    icon: 'success',
                                    title: `${response.message}`,
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                location.reload();
                            }
                        });

                    }
                })
            });

            $(document).on('change', '.quantity-input', function() {
                let cartId = $(this).data('cart-id');
                let newQuantity = $(this).val();
                let token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: `/update-cart/${cartId}`,
                    type: 'PUT',
                    data: {
                        _token: token,
                        cart_id: cartId,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        setInterval(function() {
                            location.reload();
                        }, 2000);

                    },
                    error: function(error) {
                        console.error('Error updating cart:', error);
                    }
                });
            });

            $('#checkout-btn').click(function() {
                let token = $('meta[name="csrf-token"]').attr('content');
                let user = "{{ auth()->user()->id }}";
                let total = "{{ $totalPayment }}";
                let cart = {!! json_encode($cart) !!};

                let data = {
                    _token: token,
                    user_id: user,
                    total: total,
                    cart: cart,
                    document_code: 'TRX',
                };

                $.ajax({
                    url: '{{ route('transaction') }}', 
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi Berhasil!',
                            text: 'Terima kasih atas pembelian Anda.',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        $.ajax({
                            url: '{{ route('clear-cart') }}', 
                            type: 'POST',
                            data: {
                                _token: token,
                                user_id: user
                            },
                            success: function(response) {
                                console.log('Keranjang berhasil dihapus');
                                setInterval(function () {location.reload()}, 2000);
                            },
                            error: function(error) {
                                console.error('Gagal menghapus keranjang:', error);
                            }
                        });
                    },
                    error: function(error) {
                        
                        console.log(error.message);
                        console.error('Error:', error.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Transaksi Gagal!',
                            text: error.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
