{# app/views/users/registered.volt #}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

{% block new_user_form %}

    {{ form('users/login', 'method': 'post') }}

        <label for="email">Email:</label>
        {{ email_field('email', 'size': 32, 'id': 'email') }}

         <label for="password">Password:</label>
        {{ password_field('password', 'size': 32, 'id': 'password') }}

        {{ submit_button('Login') }}

    {{ end_form() }}

{% endblock %}