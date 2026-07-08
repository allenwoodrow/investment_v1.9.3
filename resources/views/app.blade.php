<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investimento de Capital</title>
    <link rel="icon" href="https://azim.hostlin.com/Counsolve/assets/images/favicon-2.ico" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! \App\Utils\Meta::vite_assets() !!}
</head>
<body>
    <div id="app"></div>
</body>
</html>
