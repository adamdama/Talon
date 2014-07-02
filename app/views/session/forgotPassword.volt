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

    {{ form.render('Send') }}

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}

{{ end_form() }}