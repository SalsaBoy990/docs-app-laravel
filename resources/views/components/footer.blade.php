<footer class="page-footer">
    <div class="footer-content">
        <nav>
            <?php ?>
            @auth
                <a href="{{ url('/home') }}" class="">Home</a>
            @else
                <a href="{{ route('login') }}" class="">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="">Register</a>
                @endif
            @endauth
            <?php ?>
        </nav>
        <small>&copy; 2023 Gulácsi András. All rights reserved!</small>
    </div>
</footer>
