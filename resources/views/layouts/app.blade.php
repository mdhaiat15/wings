<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="text-[15px] h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">

    @vite('resources/css/app.css')
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Meta -->
    <!-- MS, fb & Whatsapp -->

    <!-- MS Tile - for Microsoft apps-->
    <meta name="msapplication-TileImage" content="/favicon-32x32.png">

    <!-- fb & Whatsapp -->

    <!-- Site Name, Title, and Description to be displayed -->
    <meta property="og:site_name" content="{{ config('app.name', 'System') }}">
    <meta property="og:title" content=" {{ !empty($title) ? $title : '' }}">
    <meta property="og:description" content="{{ !empty($description) ? $description : '' }}">

    <!-- Image to display -->
    <meta property="og:image" content="/favicon-32x32.png">

    <!-- No need to change anything here -->
    <meta property="og:type" content="website" />
    <meta property="og:image:type" content="image/jpeg">

    <!-- Size of image. Any size up to 300. Anything above 300px will not work in WhatsApp -->
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">

    <!-- Website to visit when clicked in fb or WhatsApp-->
    <meta property="og:url" content="{{ config('app.url') }}">
    <!-- End Meta -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (!empty($alpineActive))
        @if (!empty($alpineMask))
            <!-- Alpine Plugins -->
            <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
        @endif

        <!-- Alpine Core -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    @endif

    @stack('header')
</head>

<body class="h-full bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] leading-[1.6]">
    <div class="overflow-x-hidden lg:overflow-x-visible">
        <div id="togglewrapper1516"
            class="flex min-h-full w-[calc(100%+200px)] lg:w-full translate-x-[-200px] lg:translate-x-0">
            <x-navigation />
            <!-- main -->
            <div class="min-h-full flex-grow shrink-0 basis-auto w-1/2 relative">
                <!-- main-header -->
                <x-top-bar :bread-crumb-list="!empty($breadCrumbList) ? $breadCrumbList : null" />
                <!-- main nav (judul), $title adalah slot -->
                @if (!empty($title))
                    <div class="flex w-full px-[40px]">
                        <div class="flex flex-wrap flex-1 items-center">
                            <div
                                class="relative flex-none md:flex-1 mr-auto pr-0 md:pr-[10px] mb-[10px] md:mb-0 w-full md:w-auto text-center md:text-left">
                                @if (!$hideTitle)
                                    <h1
                                        class="font-medium text-[1.85em] overflow-hidden text-ellipsis whitespace-nowrap text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]">
                                        {{ $title ?? '' }}</h1>
                                @endif
                            </div>

                            @if ($action)
                                <div class="flex gap-1 items-center ">
                                    <x-button-item :link="$action['link']" :text="$action['text']" />
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <!-- main content -->
                <div class="block px-[20px] py-[30px] sm:px-[40px] sm:py-[35px]">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <x-modal />

    @yield('custom-javascript')
    @stack('body')
</body>

</html>
