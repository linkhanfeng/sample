<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App') - by hf</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    @include('layouts._header')
    <div class="container">
      <div class="col-md-offset-1 col-md-10">
        @include('shared._messages')
        @yield('content')
        @include('layouts._footer')
      </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>