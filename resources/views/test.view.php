<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemplo de Código Formatado</title>
    <!-- Inclua a folha de estilo do Prism -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism.min.css">
</head>

<body>

    <h1>Exemplo de Código Formatado</h1>

    <!-- Código HTML -->
    <h2>HTML:</h2>
    <pre><code class="language-html">
&lt;!DOCTYPE html&gt;
&lt;html lang=&quot;en&quot;&gt;
&lt;head&gt;
    &lt;meta charset=&quot;UTF-8&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1.0&quot;&gt;
    &lt;title&gt;Página de Exemplo&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;

&lt;h1&gt;Olá, Mundo!&lt;/h1&gt;

&lt;/body&gt;
&lt;/html&gt;
</code></pre>

    <!-- Código JavaScript -->
    <h2>JavaScript:</h2>
    <pre><code class="language-javascript">
console.log("Olá, Mundo!");
</code></pre>
    <pre>
        @php
            $formatted_json = json_encode(json_decode(file_get_contents(directoryRoot('storage/cache/jsons/views.json'))), JSON_PRETTY_PRINT);   
            $formatted_json = str_replace('\/', '/',$formatted_json);
       @endphp

        <code class="language-json">{{ $formatted_json }}</code>
    </pre>

    <!-- Inclua o script do Prism -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/components/prism-markup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/components/prism-clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/components/prism-json.min.js"></script>
</body>

</html>