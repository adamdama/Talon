{# app/views/users/new.volt #}

{%  block content %}
    {{  content() }}
{% endblock %}

{%  block flash %}
    {{ flashSession.output() }}
{% endblock %}

<section class="form">
    {{ form('method': 'post') }}

    <div>
        <div class="row">
            {{ form.render('name') }}
        </div>
        <div class="row">
            {{ form.render('email') }}
        </div>
        <div class="row">
            {{ form.render('password') }}
        </div>
        <div class="row">
            {{ form.render('Save') }}
        </div>

        {{ form.render('csrf', ['value': security.getToken()]) }}
        {{ form.messages('csrf') }}

        {{ end_form() }}
    </div>
</section>