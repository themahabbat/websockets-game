<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>WS Game</title>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
            }

            canvas {
                margin: 0;
                padding: 0;
            }
        </style>

    </head>

    <body>

        <div id="react-game"></div>
        <canvas id="game-canvas"></canvas>



        <script src="http://192.168.0.222:3000/browser-sync/browser-sync-client.js?v=2.26.7"></script>
        <script src="{{ asset('public/js/app.js') }}"></script>







    </body>

</html>
