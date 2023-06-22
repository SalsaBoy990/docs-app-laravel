<div x-data="dropdownData" class="language-switcher-component dropdown" @click.outside="hideDropdown">
    <button @click="toggleDropdown" class="language-switcher-button">
        <img class="h-4"
             src="{{ asset('storage/images/flags/' . $current_locale . '-flag.jpg' ) }}"
             alt="flag"
        >
        <svg aria-hidden="true"
             focusable="false"
             data-prefix="fas"
             data-icon="caret-down"
             class=""
             role="img"
             xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 320 512"
        >
            <path fill="currentColor"
                  d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z">
            </path>
        </svg>
    </button>
    <div x-show="openDropdown" class="dropdown-content bar-block card card-4">
        @foreach ($available_locales as $locale_name => $available_locale)
            @if ($available_locale !== $current_locale)

                <a class="language-switcher-button button"
                    title="{{ $locale_name }}"
                   href="{{ route('lang.index', $available_locale) }}">
                    <img class=""
                         src="{{ asset('storage/images/flags/' . $available_locale . '-flag.jpg' ) }}"
                         alt="{{ $locale_name }}">
                </a>
            @endif
        @endforeach
    </div>
</div>
