<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Management API — documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" crossorigin="anonymous">
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js" crossorigin="anonymous"></script>
<script>
    window.onload = () => {
        window.ui = SwaggerUIBundle({
            url: @json(url('/api/openapi.json')),
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
            plugins: [SwaggerUIBundle.plugins.DownloadUrl],
            layout: 'StandaloneLayout',
            // 'none' = all tag sections collapsed on load (refresh). 'list' expands tags only; 'full' expands everything.
            docExpansion: 'none',
            tryItOutEnabled: true,
            supportedSubmitMethods: ['get', 'post', 'put', 'patch', 'delete', 'options', 'head', 'trace'],
            validatorUrl: null,
            displayRequestDuration: true,
            filter: true,
            requestInterceptor: (request) => {
                if (!request.headers['Accept']) {
                    request.headers['Accept'] = 'application/json';
                }
                return request;
            },
        });
    };
</script>
</body>
</html>
