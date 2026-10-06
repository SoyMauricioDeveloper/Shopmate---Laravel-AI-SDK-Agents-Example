<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'ShopMate AI' }}</title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])

    @livewireStyles
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    {{ $slot }}

    @livewireScripts
</body>

</html>