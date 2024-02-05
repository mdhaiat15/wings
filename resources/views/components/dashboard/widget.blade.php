<div class=" bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border border-solid border-transparent rounded-[6px] px-[20px] py-[12px] shadow-[0_1px_3px_0_rgba(0,0,0,0.1),0_1px_2px_-1px_rgba(0,0,0,0.1)] col-end-[span_2]">
    <div class="flex flex-wrap items-center border-b-[1px] border-solid border-b-[var(--app-gray-10)] dark:border-b-[var(--app-gray-10-dark)] px-[20px] py-[12px] mx-[-20px] mt-[-12px] mb-[10px] relative">
        <h2 class="font-medium text-[1.2em] mr-auto text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]">{{ $title }}</h2>
        <a href="{{ route($routename) }}" class="shadow-[0_1px_2px_0_rgba(0,0,0,0.05)] whitespace-nowrap border border-solid border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] align-middle text-center inline-block text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] font-normal px-[15px] py-[5px] text-[0.8rem] leading-[1.5] rounded-[4px]">View All</a>
    </div>
    {{ $slot }}
</div>
