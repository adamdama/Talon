{# app/views/users/new.volt #}

{%  block content %}
    {{  content() }}
{% endblock %}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

{{ form('method': 'post') }}

    {{ form.render('id') }}

    {{ form.label('name') }}
    {{ form.render('name') }}
    {{ form.messages('name') }}

    {{ form.label('email') }}
    {{ form.render('email') }}
    {{ form.messages('email') }}

    {{ form.label('validated') }}
    {{ form.render('validated') }}
    {{ form.messages('validated') }}

    {{ form.label('active') }}
    {{ form.render('active') }}
    {{ form.messages('active') }}

    {{ form.render('Save') }}

    {{ link_to('users/delete/'~user.id~'/yes', 'delete') }}

    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ form.messages('csrf') }}

{{ end_form() }}