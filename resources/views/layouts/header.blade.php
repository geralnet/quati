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
                <form id="signout-form" action="{{ url('/@auth/signout') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="submit" value="sign out" />
                </form>
            </li>
        </ul>
    @endif
</div>
