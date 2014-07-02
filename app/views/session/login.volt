{# app/views/session/login.volt #}

{%  block content %}
    {{  content() }}
{% endblock %}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

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

    {{ form.render('Login') }}

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}

{{ end_form() }}

{% if resendConfirmation is defined %}
    {{ link_to('resend-confirmation/' ~ resendConfirmation, 'Resend confirmation?') }}
{% endif %}

{{ link_to('session/forgot-password', 'Forgot Password?') }}

{{ link_to('session/sign-up', 'Not registered?') }}