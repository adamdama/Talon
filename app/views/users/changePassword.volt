{# app/views/users/changePassword.volt #}

<section class="form">
    {{ form('method': 'post') }}

    <div>
        <div class="row">
            {{ form.render('password') }}
        </div>
        <div class="row">
            {{ form.render('confirmPassword') }}
        </div>
        <div class="row">
            {{ form.render('Change Password') }}
        </div>

        {{ form.render('csrf', ['value': security.getToken()]) }}
        {{ form.messages('csrf') }}

        {{ end_form() }}
    </div>
</section>