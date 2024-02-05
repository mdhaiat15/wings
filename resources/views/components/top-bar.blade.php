<div
    class="h-[70px] min-h-[70px] flex items-center relative border-b border-solid border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] pl-[30px] pr-[20px] mb-[30px] shadow">
    <a id="togglemainsidebar1516"
        class="text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] cursor-pointer flex lg:hidden h-[32px] w-[30px] mr-[10px] p-0 items-center justify-center rounded-[8px]">
        <i class="fa-solid fa-bars fa-lg"></i>
    </a>
    @if (!empty($breadCrumbList))
        <x-breadcrumb :bread-crumb-list="$breadCrumbList" />
    @endif
    <div class="absolute right-[20px] top-[13px]">
        <div class="flex">

            <a href="{{ route('cart.index') }}" class="flex justify-center items-center w-[42px] h-[42px] rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path
                        d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                </svg>
            </a>
            <button id="toggletopprofile1516"
                class="inline-block w-[42px] h-[42px] rounded-full overflow-hidden bg-[var(--app-bg-neutral-light)] dark:bg-[var(--app-bg-neutral-light-dark)]">
                <i class="fa-solid fa-user fa-lg"></i>
            </button>
        </div>
        <div id="profiledropdown1518"
            class="hidden min-w-[215px] shadow-[0_1px_4px_0_rgba(0,0,0,0.1)] border-b border-solid border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] pb-[10px] rounded-[5px] z-[200] max-w-[600px] max-h-[90vh] overflow-y-auto absolute top-0 left-0">
            <div
                class="flex justify-between px-[20px] py-[15px] mb-[10px] bg-[var(--app-bg-0)] dark:bg-[var(--app-bg-0-dark)] border-y border-y-solid border-y-[var(--app-gray-10)] dark:border-y-[var(--app-gray-10-dark)]">
                <div>
                    <h2
                        class="font-medium text-[1.1em] whitespace-nowrap leading-[1] text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]">
                        {{ Auth::user()->email ?? '' }}</h2>
                </div>
            </div>
            <form id="logout1630" action="{{ route('logout') }}" method="post">
                <div class="cursor-pointer min-h-[38px] flex h-auto px-[15px] py-[7px] text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] mx-[7px] mb-[2px] rounded-[5px] items-center hover:bg-[var(--app-bg-0)] dark:hover:bg-[var(--app-bg-0-dark)]"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    @csrf
                    <i
                        class="fa-solid fa-right-from-bracket fa-lg mr-[5px] text-[var(--app-text-secondary)] dark:text-[var(--app-text-secondary-dark)]"></i>Log
                    Out
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        function translateProfileDropDown() {
            const profiledropdown = document.getElementById('profiledropdown1518');
            const tmp = -1 * (profiledropdown.clientWidth - 42);
            profiledropdown.style.transform = "translate3d(" + tmp + "px, 46px, 0px)";
        }

        const togglemainsidebar = document.getElementById('togglemainsidebar1516');
        togglemainsidebar.addEventListener('click', function(event) {
            const togglewrapper = document.getElementById('togglewrapper1516');
            togglewrapper.classList.toggle("translate-x-[-200px]");
        });

        function reportWindowSize() {
            const togglewrapper = document.getElementById('togglewrapper1516');
            if (window.innerWidth > 1024) {
                togglewrapper.classList.add("translate-x-[-200px]");
            };

            translateProfileDropDown(); // geser ketika windows resize
        }

        window.onresize = reportWindowSize;

        const toggletopprofile = document.getElementById('toggletopprofile1516');
        toggletopprofile.addEventListener('click', function(event) {
            const profiledropdown = document.getElementById('profiledropdown1518');
            profiledropdown.classList.toggle("block");
            profiledropdown.classList.toggle("hidden");
            translateProfileDropDown
                (); // kalo hidden pertama kali ,clientWidth = 0, clientWidth baru ada ketika display: block pertama kali
        });
    });
</script>
