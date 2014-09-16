{# app/views/layouts/users.volt #}

<header>
    Users
</header>

<nav class="tools">
    <ul>
        <li class="new">{{ link_to('users/new', 'new') }}</li>
    </ul>
</nav>

{{ content() }}