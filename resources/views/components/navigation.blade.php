<div
    class="flex flex-col grow-0 shrink-0 basis-[200px] min-h-screen pb-20 overflow-x-hidden z-10 relative bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border-r border-solid border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
    <a href="{{ route('dashboard') }}"
        class="text-[var(--app-text-sidebar)] dark:text-[var(--app-text-sidebar-dark)] h-[70px] min-h-[70px] flex items-center bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] shadow px-5 mb-5 border-b border-solid border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
        <span class="whitespace-nowrap overflow-hidden text-ellipsis mt-[3px]">
            {{ config('app.name', '') }}
        </span>
    </a>
    <div class="flex grow flex-col">
        <div class="mt-[18px]">
            <span
                class="mb-[6px] tracking-[1px] text-[85%] text-[var(--app-text-sidebar)] dark:text-[var(--app-text-sidebar-dark)] opacity-60 font-medium px-5 block uppercase">Order</span>
            <x-navigation-item routename="product-list.index" :title="__('Product List')" icon="" />
            <x-navigation-item routename="transaction.index" :title="__('Transaction')" icon="" />

            <span
                class="mb-[6px] tracking-[1px] text-[85%] text-[var(--app-text-sidebar)] dark:text-[var(--app-text-sidebar-dark)] opacity-60 font-medium px-5 block uppercase">Master</span>
            <x-navigation-item routename="product.index" :title="__('Product')" icon="" />

            {{-- <span
                class="mb-[6px] tracking-[1px] text-[85%] text-[var(--app-text-sidebar)] dark:text-[var(--app-text-sidebar-dark)] opacity-60 font-medium px-5 block uppercase">Master</span>
            <x-navigation-item routename="product.index" :title="__('Product')" icon="" /> --}}

        </div>
    </div>
</div>
