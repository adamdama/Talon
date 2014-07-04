{# app/views/users/index.volt #}

{{ link_to('session/logout', 'logout') }}

{{ link_to('users/new', 'new') }}

{% for user in users %}

    {{ link_to('users/edit/'~user.id, user.name) }}

{% endfor %}