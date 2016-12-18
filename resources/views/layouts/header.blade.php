<div class="site-logo">
    <a href="/">
        <img alt="Logo" />
    </a>
</div>
<div class="user-login">
    @if (Auth::guest())
        <div class="user-login-message">You are not signed in.</div>
        <ul class="user-login-options">
            <li><a href="/@auth/signin">sign in</a></li>
            <li><a href="#">sign up</a></li>
        </ul>
    @else
        <div class="user-login-message">{{ Auth::user()->name }}</div>
        <ul class="user-login-options">
            <li>
                <a href="/@auth/signout" onclick="
                    event.preventDefault();
                    document.getElementById('signout-form').submit();
                ">sign out</a>
            </li>
        </ul>
        <form id="signout-form" action="{{ url('/@auth/signout') }}"
              method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endif
</div>
