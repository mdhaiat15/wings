<div class="w-full pr-[475px]">
    <ul class="tracking-[1px] text-[85%] font-normal ml-[-3px] whitespace-nowrap overflow-hidden text-ellipsis">
        <x-breadcrumb-item :last="false" :root="true" />
        @foreach ($breadCrumbList as $item)
            <x-breadcrumb-item :last="$loop->last" :text="$item['text']" :link="$item['link']" />
        @endforeach
    </ul>
</div>
