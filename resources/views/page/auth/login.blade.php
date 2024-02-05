<x-guest-layout>
    <div class="flex items-center justify-center h-full">
        <div class="w-full max-w-[450px] p-[20px]">
            <div
                class="shadow-[0_10px_30px_-5px_rgba(15,19,57,0.1)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border border-solid border-[var(--app-gray-10)] dark:border-[var(--app-gray-10-dark)] rounded-[6px] p-[20px] w-full">
                <h1
                    class="text-[1.4em] font-medium mb-[20px] text-center text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)]">
                    Login</h1>
                <form method="POST" action="" class="block">
                    @csrf
                    <fieldset class="mb-[20px]">
                        <div class="mb-[5px]">
                            <label
                                class="mb-[10px] block text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] font-medium max-w-full">Username</label>
                        </div>
                        <input type="text" name="email"
                            class="block w-full px-[15px] py-[8px] text-[1rem] leading-[1.6] text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border border-solid border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] rounded-[5px] shadow-[0_1px_2px_0px_rgba(0,0,0,0.05)]" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </fieldset>
                    <fieldset class="mb-[20px]">
                        <div class="mb-[5px]">
                            <label
                                class="mb-[10px] block text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] font-medium max-w-full">Password</label>
                        </div>
                        <input type="password" name="password"
                            class="block w-full px-[15px] py-[8px] text-[1rem] leading-[1.6] text-[var(--app-text-primary)] dark:text-[var(--app-text-primary-dark)] bg-[var(--app-white)] dark:bg-[var(--app-white-dark)] border border-solid border-[var(--app-gray-20)] dark:border-[var(--app-gray-20-dark)] rounded-[5px] shadow-[0_1px_2px_0px_rgba(0,0,0,0.05)]" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </fieldset>
                    <fieldset>
                        <input type="submit" value="Log In"
                            class="font-medium text-center align-middle cursor-pointer border border-solid whitespace-nowrap transition-[background-color] duration-[150ms] ease-in-out shadow-[0_1px_2px_0px_rgba(0,0,0,0.05)] text-[var(--app-white)] dark:text-[var(--app-white)] bg-[var(--app-primary)] dark:bg-[var(--app-primary-dark)] border-[var(--app-primary)] dark:border-[var(--app-primary-dark)] hover:bg-[var(--app-primary-hover)] dark:hover:bg-[var(--app-primary-hover-dark)] hover:border-[var(--app-primary-hover)] dark:hover:border-[var(--app-primary-hover-dark)] px-[25px] py-[10px] text-[1.2rem] leading-[1.7] rounded-[6px] block w-full">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
