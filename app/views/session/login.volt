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
            {{ link_to('resend-confirmation/' ~ resendConfirmation, 'Resend confirmation?') }}
        {% endif %}

        {{ link_to('session/forgot-password', 'Forgot Password?') }}

        {{ link_to('session/sign-up', 'Not registered?') }}
    </div>
</div>