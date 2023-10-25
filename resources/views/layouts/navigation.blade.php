<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[70rem] mx-auto px-4">
        <div class="flex justify-between h-64">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('root') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:ml-6 place-items-end">
                @auth
                    <div class="pb-5 pr-5">
                        <p class="font-bold rounded-md text-gray-800 bg-white font-bold text-right pb-5">
                            {{ Auth::user()->name }} さん
                        </p>
                        <div class="flex gap-10">
                            <a href="{{ route('posts.index') }}">WorksMore</a>
                            <a href="{{ route('posts.create') }}">Create</a>
                            <a href="{{ route('profile.edit') }}">Profile</a>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <input type="submit" value="Log Out" class="cursor-pointer">
                            </form>
                        </div>
                    </div>
                @else
                    <div class="pb-5 pr-5">
                        <a href="{{ route('posts.index') }}" class="pr-10">WorksMore</a>
                        <a href="{{ route('register') }}" class="pr-10">Sign Up</a>
                        <a href="{{ route('login') }}">Log In</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }} さん</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('posts.index')">
                        WorksMore
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <x-dropdown-link :href="route('posts.create')">
                        Create Post
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <x-responsive-nav-link :href="route('posts.index')">
                    WorksMore
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    Sign Up
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')">
                    Log In
                </x-responsive-nav-link>
            @endauth
        </div>
    </div>
</nav>
