{# app/views/index.volt #}

<!DOCTYPE html>
<html>
	<head>
		{{ get_title() }}

        {{ assets.outputCss() }}
        <!-- Add Head JS Scripts -->
        {{ assets.outputJs('head') }}

        <meta http-equiv="X-UA-Compatible" content="IE=Edge">

	</head>
	<body>
        <section class="messages">
        </section>

        <section class="page">
            {{ content() }}
        </section>

        <!-- Add jQuery -->
        {{ jQuery }}

        <!-- Add Footer JS Scripts -->
        {{ assets.outputJs('footer') }}
	</body>
</html>