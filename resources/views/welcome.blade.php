<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{env('APP_NAME')}}</title>

    <link rel="stylesheet" href="/css/main.css">
    <script src="https://rawgithub.com/darkskyapp/skycons/master/skycons.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1" defer></script>
    <script src="/js/app.js" defer></script>
</head>
<body class="bg-gray-200">
    <div id="app" class="flex justify-center pt-16">

        <weather-app></weather-app>
    </div>
</body>
</html>