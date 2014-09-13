{# app/views/session/forgotPassword.volt #}

<div class="login">
    {{ partial('partials/logo') }}
    <div class="messages">
        {%  block flash %}
            {{ flashSession.output() }}
        {% endblock %}
    </div>
    <div class="form">
        {{ form('method': 'post') }}

        {{ form.render('email') }}

        {{ form.render('Send') }}

        {{ form.render('csrf', ['value': security.getToken()]) }}

        {{ end_form() }}
    </div>
</div>