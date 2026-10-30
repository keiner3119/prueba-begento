<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>API Productos - Documentación</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui-bundle.js"></script>
    <script>
        // Carga la especificación desde /swagger/openapi.json
        SwaggerUIBundle({
            url: '{{ url('swagger/openapi.json') }}',
            dom_id: '#swagger-ui',
            persistAuthorization: true
        });
    </script>
</body>
</html>
