<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MLGB Clan</title>

    <meta name="viewport" content="initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>

<body>
<div id="app"></div>

<footer>
    <div class="container text-xs-center">
        <div class="copyright">
            ©COPYRIGHT 2016-{{ \Carbon\Carbon::today()->year }} veoveo.me
        </div>
        <div class="text-uppercase">
            <a href="https://github.com/sudomabider/react-coc" target="_blank">
                <i class="fa fa-github"></i> Project on Github
            </a>
        </div>
    </div>
</footer>

<script>
    {{--@if(config('app.debug'))
    window.onerror = function myErrorHandler(errorMsg, url, lineNumber) {
      alert("Error occured: " + errorMsg);//or any message
      return false;
    };
    @endif--}}
</script>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>