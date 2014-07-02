{# app/views/users/changePassword.volt #}

{%  block content %}
    {{  content() }}
{% endblock %}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

{{ form('method': 'post') }}

    {{ form.label('password') }}
    {{ form.render('password') }}
    {{ form.messages('password') }}

    {{ form.label('confirmPassword') }}
    {{ form.render('confirmPassword') }}
    {{ form.messages('confirmPassword') }}

    {{ form.render('Change Password') }}

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}

{{ end_form() }}