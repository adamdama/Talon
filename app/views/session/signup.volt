{# app/views/session/signup.volt #}

<div class="sinup">
    {{ partial('partials/logo') }}
    <div class="messages">
        {%  block flash %}
            {{ flashSession.output() }}
        {% endblock %}
    </div>
    <div class="form">

        {{ form('method': 'post') }}

        {{ form.render('name') }}

        {{ form.render('email') }}

        {{ form.render('confirmEmail') }}

        {{ form.render('password') }}

        {{ form.render('confirmPassword') }}

        {#{{ form.render('terms') }}#}
        {#{{ form.label('terms') }}#}
        {#{{ form.messages('terms') }}#}

        {{ form.render('Sign Up') }}

        {{ form.render('csrf', ['value': security.getToken()]) }}

        {{ end_form() }}
    </div>
</div>