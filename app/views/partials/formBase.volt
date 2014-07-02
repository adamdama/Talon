{# app/views/session/formBase.volt #}

{%  block content %}
    {{  content() }}
{% endblock %}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

{{ form('method': 'post') }}

    {% block form %}

    {% endblock %}

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}

{{ end_form() }}