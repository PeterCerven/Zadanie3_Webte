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
<div class="container">
    <div class="row">
        <div class="col">
            <canvas id="canvas" width="600" height="600"></canvas>
        </div>
        <div class="col">
            <h2 class="text-center mb-4">Game Menu</h2>
            <div class="d-grid gap-2">
                <button id="start" class="btn btn-primary">Start Game</button>
                <button id="stop" class="btn btn-warning">Restart</button>
                <button id="rageQuit" class="btn btn-danger">Rage quit</button>
                <div class="input-group mb-3">
                    <button id="join" class="btn btn-info">Join</button>
                    <input id="player" type="text" class="form-control" placeholder="Player" aria-label="Player">
                </div>
            </div>
            <h4 class="mt-4"><span id="score">0</span></h4>
            <div class="row">
                <div class="col myBack">
                    <div class="player">
                        <div class="rect green me-2"></div>
                        <span>LEFT: </span>
                        <div class="name">Free</div>
                    </div>
                    <div class="player">
                        <div class="rect yellow me-2"></div>
                        <span>RIGHT: </span>
                        <div class="name">Free</div>
                    </div>
                    <div class="player">
                        <div class="rect red me-2"></div>
                        <span>TOP: </span>
                        <div class="name">Free</div>
                    </div>
                    <div class="player">
                        <div class="rect blue me-2"></div>
                        <span>BOTTOM: </span>
                        <div class="name">Free</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4 class="mt-4">Admin: <span id="admin">No</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <button id="quit">Quit</button>
</div>

</body>

</html>


