<?php


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pongo</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
    <script defer src="script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
<h1>Test</h1>
<div class="container">
    <div class="row">
        <div class="col">
            <canvas id="canvas" width="600" height="600"></canvas>
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <button id="start">Start Game</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button id="stop">Stop Game</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button id="rageQuit">Rage quit</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="player">Join</label>
                    <input id="player" type="text" value="player"/>
                    <button id="join">Join</button>
                </div>
            </div>
            <div class="row">
                <div class="col myBack">
                    <div class="player">
                        <div class="rect green"></div>
                        <span>LEFT: </span>
                        <div class="name">Free</div>
                    </div>
                    <div class="player">
                        <div class="rect yellow"></div>
                        <span>RIGHT: </span>
                        <div class="name">Free</div>
                    </div>
                    <div class="player">
                        <div class="rect red"></div>
                        <span>TOP: </span>
                        <div class="name">Free</div>
                    </div>
                    <div class="player">
                        <div class="rect blue"></div>
                        <span>BOTTOM: </span>
                        <div class="name">Free</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div id="score">Score: 0</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div id="number">&nbsp;</div>
    <div id="log"></div>
    <input id="msg" type="text"/>
    <button id="send">Send</button>
    <button id="quit">Quit</button>
</div>

</body>

</html>


