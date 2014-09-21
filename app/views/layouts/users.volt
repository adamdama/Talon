{# app/views/layouts/users.volt #}

<header>
    Users
</header>

<nav class="tools">
    <ul>
        <li>
            <a href="{{ url('users/new') }}" title="New user">
                {{ svg_icon(url('img/svg/navigation.svg#nav-plus-icon')) }}
                <span>New</span>
            </a>
        </li>
        <li>
            <a href="{{ url('users/delete') }}" title="New user">
                {{ svg_icon(url('img/svg/navigation.svg#nav-trash-icon')) }}
                <span>Delete</span>
            </a>
        </li>
    </ul>
</nav>

{{ content() }}