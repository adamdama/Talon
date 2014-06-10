{# app/views/session/login.volt #}

{%  block content %}
    {{  content() }}
{% endblock %}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

{% block login_form %}

    {{ form('method': 'post') }}

    {{ form.label('email') }}
    {{ form.render('email') }}
    {{ form.messages('email') }}

    {{ form.label('password') }}
    {{ form.render('password') }}
    {{ form.messages('password') }}

    {{ link_to('session/forgot-password') }}

    {{ form.render('remember') }}
    {{ form.label('remember') }}
    {{ form.messages('remember') }}

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}

    {{ form.render('Login') }}

    {{ end_form() }}

{% endblock %}