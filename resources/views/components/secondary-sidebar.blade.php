<div class="flex-[0_2_310px] md:pr-10">

    @foreach ($sidebars as $item)
        @if ($item['type'] === 'item')
            <x-secondary-sidebar-item routename="{{ $item['link'] }}" :title="__($item['title'])" icon="{{ $item['icon'] }}" />
        @elseif ($item['type'] === 'header')
            <x-secondary-sidebar-header class="" title="{{ $item['title'] }}">
                @if (!empty($item['text_link']))
                    <x-button-item link="{{ $item['link'] }}" text="{{ $item['text_link'] }}" type="small"
                        class="ml-auto" />
                @endif
            </x-secondary-sidebar-header>
        @elseif ($item['type'] === 'divider')
            <x-secondary-sidebar-divider />
        @endif
    @endforeach

</div>
