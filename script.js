let canvas = document.getElementById("canvas");
let c = canvas.getContext("2d");


let ball;
let players = [];
let walls = [];
let goals = [];

let playerIndex;


function Wall(x, y, width, height, color) {
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
    this.color = color;

    this.draw = function () {
        c.beginPath();
        c.rect(this.x, this.y, this.width, this.height);
        c.fillStyle = this.color;
        c.fill();
        c.closePath();
    }

    this.draw();

}

function Ball(x, y, radius, color) {
    this.x = x;
    this.y = y;
    this.radius = radius;
    this.color = color;

    this.draw = function () {
        c.beginPath();
        c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
        c.fillStyle = this.color;
        c.fill();
        c.closePath();
    }

    this.update = function (x, y) {
        this.x = x;
        this.y = y;
        console.log("Ball: " + this.x + " " + this.y);
    }

    this.draw();

}

function Player(x, y, width, height, color) {
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
    this.color = color;

    this.draw = function () {
        c.beginPath();
        c.rect(this.x, this.y, this.width, this.height);
        c.fillStyle = this.color;
        c.fill();
        c.closePath();
    }

    this.update = function (x, y) {
        this.x = x;
        this.y = y;
    }

    this.draw();

}

function Goal(x, y, width, height, color) {
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
    this.color = color;

    this.draw = function () {
        c.beginPath();
        c.rect(this.x, this.y, this.width, this.height);
        c.fillStyle = this.color;
        c.fill();
        c.closePath();
    }

    this.draw();

}

function init(ballProperties, playersProperties, goalsProperties, wallsProperties) {
    for (let i = 0; i < playersProperties.length; i++) {
        players.push(new Player(playersProperties[i].x, playersProperties[i].y, playersProperties[i].width, playersProperties[i].height, playersProperties[i].color));
    }
    for (let i = 0; i < goalsProperties.length; i++) {
        goals.push(new Goal(goalsProperties[i].x, goalsProperties[i].y, goalsProperties[i].width, goalsProperties[i].height, "black"));
    }
    for (let i = 0; i < wallsProperties.length; i++) {
        walls.push(new Wall(wallsProperties[i].x, wallsProperties[i].y, wallsProperties[i].width, wallsProperties[i].height, wallsProperties[i].color));
    }
    ball = new Ball(ballProperties.x, ballProperties.y, ballProperties.radius, "red");
}

function updateScene() {
    c.clearRect(0, 0, canvas.width, canvas.height);
    ball.draw();
    players.forEach(player => player.draw());
    walls.forEach(wall => wall.draw());
    goals.forEach(goal => goal.draw());
}

$(document).ready(function () {
    var ws = new WebSocket("ws://localhost:9000");
    ws.onopen = function (e) {
        log("Connection established");
    };
    ws.onerror = function (error) {
        log("Unknown WebSocket Error " + JSON.stringify(error));
    };
    ws.onmessage = function (e) {
        let data = JSON.parse(e.data);
        log(playerIndex);
        if (data.players.length !== players.length) {
            if (playerIndex === undefined) {
                playerIndex = data.players.length - 1;
                // log("Player index: " + playerIndex);
            }
            init(data.ball, data.players, data.goals, data.walls);
        }
        // log('< Received from server: ');
        ball.update(data.ball.x, data.ball.y);
        for (let i = 0; i < data.players.length; i++) {
            players[i].update(data.players[i].x, data.players[i].y);
        }
        updateScene();
        // document.getElementById("showGame").innerHTML = data.game;
    };
    ws.onclose = function () {
        log("Connection closed - Either the host or the client has lost connection");
    }

    function log(m) {
        $("#log").append(m + "<br />");
    }


    function send() {
        $Msg = $("#msg");
        if ($Msg.val() == "") return alert("Textarea is empty");

        try {
            ws.send($Msg.val());
            log('> Sent to server:' + $Msg.val());
        } catch (exception) {
            log(exception);
        }
        $Msg.val("");
    }

    $(document).keydown(function (event) {
        let data;
        event.preventDefault();
        switch (event.key) {
            case "ArrowUp":
                data = {
                    playerIndex: playerIndex,
                    x: players[playerIndex].x,
                    y: players[playerIndex].y - 10,
                }
                ws.send(JSON.stringify(data));
                log("ArrowUp");
                break;
            case "ArrowDown":
                data = {
                    playerIndex: playerIndex,
                    x: players[playerIndex].x,
                    y: players[playerIndex].y + 10,
                }
                ws.send(JSON.stringify(data));
                log("ArrowDown");
                break;
            case "ArrowLeft":
                data = {
                    playerIndex: playerIndex,
                    x: players[playerIndex].x - 10,
                    y: players[playerIndex].y,
                }
                ws.send(JSON.stringify(data));
                log("ArrowLeft");
                break;
            case "ArrowRight":
                data = {
                    playerIndex: playerIndex,
                    x: players[playerIndex].x + 10,
                    y: players[playerIndex].y,
                }
                ws.send(JSON.stringify(data));
                log("ArrowRight");
                break;
            default:
                log("Other key");
                break;
        }
    });
    $("#send").click(send);
    $("#quit").click(function () {
        log("Connection closed");
        ws.close();
        ws = null;
    });
});