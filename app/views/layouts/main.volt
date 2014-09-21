{# app/views/layouts/main.volt #}

{# TODO work out a way of getting routed urls into the links easily, probably use db and routing component. #}
{# TODO work out how to get ustom functions to work in volt globally, or include macros via partials. #}

<div id="main">
    <nav class="menu">
        {{ partial('partials/logo') }}
        <ul>
            <li class="users">
                <a href="{{ url('users') }}" title="Users">
                    {{ svg_icon(url('img/svg/navigation.svg#nav-user-icon')) }}
                    <div class="menu-text">Users</div>
                </a>
                <ul>
                    <li class="new">
                        <a href="{{ url('users/new') }}" title="New User">
                            {{ svg_icon(url('img/svg/navigation.svg#nav-plus-icon')) }}
                            <div class="menu-text">New</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="logout">
                <a href="{{ url('session/logout') }}" title="Sign Out">
                    {{ svg_icon(url('img/svg/navigation.svg#nav-cross-icon')) }}
                    <div class="menu-text">Sign Out</div>
                </a>
            </li>
        </ul>
        <div class="layer one"></div>
        <div class="layer two"></div>
    </nav>
    <section class="content">
        <div>
            {{  content() }}
        </div>
    </section>
</div>