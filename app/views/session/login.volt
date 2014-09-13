{# app/views/session/login.volt #}

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

        {{ form.render('password') }}

        {{ form.render('Login') }}

        {{ form.render('remember') }}
        {{ form.label('remember') }}

        {{ form.render('csrf', ['value': security.getToken()]) }}

        {{ end_form() }}
    </div>
    <div class="links">
        {% if resendConfirmation is defined %}
            {{ link_to(url(['for':'users_control-resendConfirmation', 'email': resendConfirmation]), 'Resend confirmation?') }}
        {% endif %}

        {{ link_to(url(['for': 'session-forgotPassword']), 'Forgot Password?', false) }}

        {{ link_to(url(['for': 'session-signup']), 'Not registered?', false) }}
    </div>
</div>