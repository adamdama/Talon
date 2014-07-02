{# app/views/index.volt #}

<!DOCTYPE html>
<html>
	<head>
		<title>Phalcon PHP Framework</title>
	</head>
	<body>

        {%  block flash %}
            {{ flashSession.output() }}
        {% endblock %}

		{{ content() }}
	</body>
</html>