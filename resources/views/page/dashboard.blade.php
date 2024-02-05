<x-app-layout>
    <x-main-container>

        {{-- panel notice --}}
        <div class="px-[25px] py-0 border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
            @include('components.alert-notification')
        </div>

        <div class="grid grid-cols-[repeat(2,1fr)] md:grid-cols-[repeat(4,1fr)] gap-[30px]">

        </div>
    </x-main-container>
</x-app-layout>
