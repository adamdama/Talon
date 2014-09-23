{# app/views/users/new.volt #}

<section class="form">
    {{ form('method': 'post') }}

    <div>
        <div class="row">
            {{ form.render('id') }}
        </div>
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
            {{ form.label('validated') }}
            {{ form.render('validated') }}
        </div>
        <div class="row">
            {{ form.label('active') }}
            {{ form.render('active') }}
        </div>
        <div class="row">
            {{ form.render('Save') }}
            <a href="{{ url('users/delete/' ~ user.id ~ '/yes') }}" title="Delete user">
                {{ svg_icon(url('img/svg/navigation.svg#nav-trash-icon')) }}
                <span>Delete User</span>
            </a>
        </div>

        {{ form.render('csrf', ['value': security.getToken()]) }}
        {{ form.messages('csrf') }}

        {{ end_form() }}
    </div>
</section>