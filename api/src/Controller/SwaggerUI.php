<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SwaggerUI
{
    /**
     * @Route( "/docs", methods={ "GET" } )
     */
    public function docs()
    {
        return new Response(
            '<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.18.3/swagger-ui.css" >
    <link rel="icon" type="image/png" href="https://wowvendor.com/app/themes/wowvendor.ecommerce/favicon.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="https://wowvendor.com/app/themes/wowvendor.ecommerce/favicon.png" sizes="16x16" />
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }

      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }

      body
      {
        margin:0;
        background: #fafafa;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.18.3/swagger-ui-bundle.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/3.18.3/swagger-ui-standalone-preset.js"> </script>
    <script>
    window.onload = function() {
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        url: "/docs.json",
        dom_id: "#swagger-ui",
        deepLinking: true,
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
        ],
            plugins: [
            SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout",
        operationsSorter: (a, b) => {
            var methodsOrder = ["get", "post", "put", "delete", "patch", "options", "trace"];
            var result = methodsOrder.indexOf( a.get("method") ) - methodsOrder.indexOf( b.get("method") );

            if (result === 0) {
                result = a.get("path").localeCompare(b.get("path"));
            }

            return result;
        }
      })
    // End Swagger UI call region

window.ui = ui
}
</script>
  </body>
</html>'
        );
    }
}
