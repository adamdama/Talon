{# app/views/users/new.volt #}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

{% block new_user_form %}

    {{ form('users/create', 'method': 'post') }}

        <label for="name">Name:</label>
        {{ text_field('name', 'size': 32, 'id': 'name') }}

        <label for="email">Email:</label>
        {{ email_field('email', 'size': 32, 'id': 'email') }}

        <label for="confirm_email">Confirm Email:</label>
        {{ email_field('confirm_email', 'size': 32, 'id': 'confirm_email') }}

         <label for="password">Password:</label>
        {{ password_field('password', 'size': 32, 'id': 'password') }}

        <label for="confirm_password">Confirm Password:</label>
        {{ password_field('confirm_password', 'size': 32, 'id': 'confirm_password') }}

        {{ hidden_field(security.getTokenKey(), 'value': security.getToken()) }}

        {{ submit_button('submit') }}

    {{ end_form() }}

{% endblock %}