<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <script src="{{ mix('js/app.js') }}" defer></script>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div
  id="app"
  data-user='@json(\Auth::user())'
  data-config='@json(\App\Services\ConfigService::basic())'
>
  <main>
    @yield('content')
  </main>
</div>
</body>
</html>
