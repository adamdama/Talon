{# app/views/users/index.volt #}

<section class="list">
    <table class="dataTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            {% for user in users %}
                <tr>
                    <td>{{ link_to('users/edit/'~user.id, user.name) }}</td>
                    <td>{{ link_to('users/edit/'~user.id, user.email) }}</td>
                    <td class="icon">
                        <a href="{{ url('users/edit/' ~ user.id) }}" title="Edit User">
                            {{ svg_icon(url('img/svg/navigation.svg#nav-edit-icon')) }}
                        </a>
                    </td>
                    <td class="icon">
                        <a href="{{ url('users/delete/' ~ user.id) }}" title="Delete User">
                            {{ svg_icon(url('img/svg/navigation.svg#nav-trash-icon')) }}
                        </a>
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
</section>