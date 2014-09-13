{# app/views/index.volt #}

<!DOCTYPE html>
<html>
	<head>
		{{ get_title() }}

        {{ assets.outputCss() }}
	</head>
	<body>
        <section class="messages">
        </section>

        <section class="page">
            {{ content() }}
        </section>

        <!-- Add jQuery -->
        {{ jQuery }}

        {{ assets.outputJs() }}
	</body>
</html>