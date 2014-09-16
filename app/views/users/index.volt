{# app/views/users/index.volt #}

<header>
    Users
</header>

<nav class="tools">
    <ul>
        <li class="new">{{ link_to('users/new', 'new') }}</li>
    </ul>
</nav>

<section class="list">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            {% for user in users %}
                <tr>
                    <td></td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ link_to('users/edit/'~user.id, 'edit') }}</td>
                    <td>{{ link_to('users/delete/'~user.id, 'delete') }}</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
</section>