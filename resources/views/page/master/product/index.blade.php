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

                    <form action="{{ route($routeName) }}" method="GET">
                        @csrf

                        {{-- panel heading --}}
                        <x-panel-heading title="{{ !empty($secondaryTitle) ? $secondaryTitle : $title }}"
                            :action="$secondaryAction" />

                        {{-- panel notice --}}
                        <div
                            class="px-[25px] py-0 border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
                            @include('components.alert-notification')
                        </div>

                        {{-- filter search bar --}}
                        <x-filter-search :table-key-list="$tableKeyList" :data-filter="$dataFilter" :form-name="$formName" :array-lookup="$arrayLookup"
                            array-attachment="" />

                        {{-- table --}}
                        <div class="w-full relative overflow-x-auto">

                            <table class="w-full text-left text-[14px] border-collapse">
                                @php
                                    $urlParamId = !empty($overrideRouteUrlParam) ? $overrideRouteUrlParam : 'id';
                                    $keyId = !empty($overrideKeyId) ? $overrideKeyId : 'id';
                                    $isActive = false;
                                @endphp

                                <thead class="capitalize ">
                                    <tr
                                        class="border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
                                        @foreach ($tableKeyList as $key => $item)
                                            @if (array_key_exists('hide-list', $item) && $item['hide-list'] == 1)
                                                @continue
                                            @endif

                                            @if (array_key_exists('show-sort', $item) && $item['show-sort'] == 1)
                                                @php
                                                    $isKey = $key == $keyId;
                                                    $icon = 'fa-caret-down';
                                                    $orderBy = 'desc';

                                                    if ($key == $dataFilter['sortBy']) {
                                                        $icon = $dataFilter['orderBy'] === 'desc' ? 'fa-caret-down' : 'fa-caret-up';
                                                        $orderBy = $dataFilter['orderBy'] === 'desc' ? 'asc' : 'desc';
                                                        $isActive = true;
                                                    } else {
                                                        $isActive = false;
                                                    }

                                                    $newFilterRequest = CustomHelper::modifyQueryString($filterRequest, [
                                                        'sortBy' => $key,
                                                        'orderBy' => $orderBy,
                                                    ]);
                                                @endphp

                                                <x-table-th title="{{ $item['title'] }}"
                                                    is-active="{{ $isActive ?? false }}" :link="route($routeName, $newFilterRequest)"
                                                    icon="{{ $icon }}" />
                                            @else
                                                <x-table-th title="{{ $item['title'] }}" :link="route($routeName, $filterRequest)" />
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (!empty($data))
                                        @foreach ($data['results'] as $row)
                                            <tr
                                                class="border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
                                                @foreach ($tableKeyList as $key => $value)
                                                    @if (array_key_exists('hide-list', $item) && $item['hide-list'] == 1)
                                                        @continue
                                                    @endif

                                                    @if ($key == $keyId)
                                                        @if (!empty($filterRequest))
                                                            <x-table-td :text="$row[$key]" :link="route($routeEdit, [
                                                                $urlParamId => $row[$key],
                                                                $filterRequest,
                                                            ])" />
                                                        @else
                                                            <x-table-td :text="$row[$key]" :link="route($routeEdit, [$urlParamId => $row[$key]])" />
                                                        @endif
                                                    @elseif (array_key_exists('edit-link', $value) && $value['edit-link'] == 'true')
                                                        <x-table-td :text="$row[$key]" :link="route($routeEdit, [$urlParamId => $row[$keyId]])" />
                                                    @elseif (array_key_exists('image-preview', $value) && $value['image-preview'] == 'true')
                                                        <x-table-td :text="''" :image-id="$row[$keyId]"
                                                            :image-link="$row[$key]" />
                                                    @else
                                                        <x-table-td :text="$row[$key]" />
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach

                                        @if (empty($data['results']))
                                            <tr
                                                class="border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
                                                <x-table-td text="No Data" colspan="{{ sizeof($tableKeyList) ?? '1' }}"
                                                    class="text-center" />
                                            </tr>
                                        @endif
                                    @else
                                        <tr
                                            class="border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
                                            <x-table-td text="No Data" colspan="{{ sizeof($tableKeyList) ?? '1' }}"
                                                class="text-center" />
                                        </tr>
                                    @endif

                                <tfoot class="capitalize ">
                                    <tr
                                        class="border-b border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)]">
                                        @foreach ($tableKeyList as $key => $item)
                                            @if (array_key_exists('hide-list', $item) && $item['hide-list'] == 1)
                                                @continue
                                            @endif

                                            @if (array_key_exists('show-sort', $item) && $item['show-sort'] == 1)
                                                @php
                                                    $isKey = $key == $keyId;
                                                    $icon = 'fa-caret-down';
                                                    $orderBy = 'desc';

                                                    if ($key == $dataFilter['sortBy']) {
                                                        $icon = $dataFilter['orderBy'] === 'desc' ? 'fa-caret-down' : 'fa-caret-up';
                                                        $orderBy = $dataFilter['orderBy'] === 'desc' ? 'asc' : 'desc';
                                                        $isActive = true;
                                                    } else {
                                                        $isActive = false;
                                                    }

                                                    $newFilterRequest = CustomHelper::modifyQueryString($filterRequest, [
                                                        'sortBy' => $key,
                                                        'orderBy' => $orderBy,
                                                    ]);
                                                @endphp

                                                <x-table-th title="{{ $item['title'] }}"
                                                    is-active="{{ $isActive ?? false }}" :link="route($routeName, $newFilterRequest)"
                                                    icon="{{ $icon }}" />
                                            @else
                                                <x-table-th title="{{ $item['title'] }}" :link="route($routeName, $filterRequest)" />
                                            @endif
                                        @endforeach
                                    </tr>
                                </tfoot>

                                </tbody>

                            </table>

                        </div>

                        @if (!empty($data['link']))
                            {{-- pagination --}}
                            <x-pagination :link="$data['link']" :removeUrlQuery="[]" />
                        @endif
                    </form>

                </div>
                {{-- end panel --}}

            </div>
            {{-- end container --}}

        </div>

    </x-main-container>
</x-app-layout>
