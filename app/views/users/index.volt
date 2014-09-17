{# app/views/users/index.volt #}

<section class="list">
    <table class="dataTable">
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
                    <td>{{ link_to('users/edit/'~user.id, user.name) }}</td>
                    <td>{{ link_to('users/edit/'~user.id, user.email) }}</td>
                    <td>{{ link_to('users/edit/'~user.id, 'edit') }}</td>
                    <td>{{ link_to('users/delete/'~user.id, 'delete') }}</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
</section>