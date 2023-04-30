<header class="page-header">
    <div class="header-content">
        <div class="logo">
            <a href="/" class="brand">
                <img src="{{ url('/images/logo.png') }}" alt="Clean Theme">
                Clean Theme
            </a>
        </div>
        @if (Route::has('login'))
            <div class="main-navigation">
                <nav>
                    @auth
                        <a href="{{ url('/home') }}">
                            <i class="fa fa-home" aria-hidden="true"></i>Home
                        </a>

                        <a href="{{ url('/admin/dashboard') }}">
                            <i class="fa fa-tachometer" aria-hidden="true"></i>Dashboard
                        </a>


                        <div
                            x-data="dropdownData"
                            class="dropdown-click"
                            @click.outside="hideDropdown"
                        >
                            <a @click="toggleDropdown">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                {{ Auth::user()->name }}
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <div x-show="openDropdown" class="dropdown-content card padding-0-5">
                                <a
                                    class="dropdown-item"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"
                                >
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    <span>{{ __('Logout') }}</span>

                                </a>

                                <form
                                    id="logout-form"
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    class="d-none"
                                >
                                    @csrf
                                </form>
                            </div>
                        </div>

                    @else
                        <a href="{{ route('login') }}" class="">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="">Register</a>
                        @endif
                    @endauth
                    <span
                        class="pointer darkmode-toggle"
                        rel="button"
                        @click="toggleDarkMode"
                        x-text="isDarkModeOn() ? '🔆' : '🌒'"
                        :title="isDarkModeOn() ? 'Light mode' : 'Dark mode'"
                    >
                    </span>
                </nav>
            </div>
        @endif
    </div>
</header>
