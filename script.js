let canvas = document.getElementById("canvas");
let c = canvas.getContext("2d");


let ball;
let players = [];
let walls = [];
let goals = [];
let admin = false;
let playerSide;
$("#start").prop("disabled", true);

const playerArray = document.querySelectorAll('.player');

function assignName(side, name) {
    playerArray.forEach(player => {
        const nameElement = player.querySelector('.name');
        const sideElement = player.querySelector('span');
        const regex = new RegExp(`^${side}:`);
        if (regex.test(sideElement.textContent)) {
            nameElement.textContent = name;
        }
    });
}

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
    }

    this.draw();

}

function Player(x, y, width, height, color, lives, side, alive) {
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
    this.color = color;
    this.lives = lives;

    this.draw = function () {
        if (!alive) {
            return;
        }
        c.beginPath();
        c.rect(this.x, this.y, this.width, this.height);
        c.fillStyle = this.color;
        c.fill();
        c.closePath();

        c.font = "50px Arial";
        c.fillStyle = color;
        switch (side) {
            case "LEFT":
                c.fillText(this.lives, this.x + this.width / 2 - 60, this.y + this.height / 2 + 15);
                break;
            case "RIGHT":
                c.fillText(this.lives, this.x + this.width / 2 + 35, this.y + this.height / 2 + 15);
                break;
            case "TOP":
                c.fillText(this.lives, this.x + this.width / 2 - 15, this.y + this.height / 2 - 35);
                break;
            case "BOTTOM":
                c.fillText(this.lives, this.x + this.width / 2 - 15, this.y + this.height / 2 + 75);
                break;
        }

    }

    this.update = function (x, y) {
        this.x = x;
        this.y = y;
    }

    this.draw();

}

function Goal(x, y, width, height, color, player) {
    this.x = x;
    this.y = y;
    this.width = width;
    this.height = height;
    this.color = color;

    this.draw = function () {
        if (!player) {
            return;
        }
        c.beginPath();
        c.rect(this.x, this.y, this.width, this.height);
        c.fillStyle = this.color;
        c.fill();
        c.closePath();
    }

    this.draw();
}


function init(ballProperties, playersProperties, goalsProperties, wallsProperties) {
    players = [];
    walls = [];
    goals = [];
    let alive = 0;
    for (let i = 0; i < playersProperties.length; i++) {
        players.push(new Player(playersProperties[i].x, playersProperties[i].y, playersProperties[i].width,
            playersProperties[i].height, playersProperties[i].color, playersProperties[i].lives, playersProperties[i].side,
            playersProperties[i].alive));
        assignName(playersProperties[i].side, playersProperties[i].nickName);
        if (playersProperties[i].alive) {
            alive++;
        }
    }
    if (alive === 0) {
        admin = false;
        playerSide = undefined;
        $('#admin').text('No');
        $("#start").prop("disabled", true);
    }
    for (let i = 0; i < goalsProperties.length; i++) {
        goals.push(new Goal(goalsProperties[i].x, goalsProperties[i].y, goalsProperties[i].width, goalsProperties[i].height, "black", goalsProperties[i].player));
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
    let ws = new WebSocket("ws://localhost:9000");
    ws.onopen = function (e) {
        console.log("Connection established");
    };
    ws.onerror = function (error) {
        console.log("Unknown WebSocket Error " + JSON.stringify(error));
    };
    ws.onmessage = function (e) {
        let data = JSON.parse(e.data);
        if (data.message === "reset") {
            playerSide = undefined;
            $("#join").prop("disabled", false);
            $('#admin').text('No');
            playerArray.forEach(player => {
                const nameElement = player.querySelector('.name');
                nameElement.textContent = 'Free';
            });
            return;
        }
        if (data.message === "player") {
            playerSide = data.side;
            admin = data.isAdmin;
            if (admin) {
                $('#admin').text('Yes');
                $("#start").prop("disabled", false);
            } else {
                $('#admin').text('No');
                $("#start").prop("disabled", true);
            }
            return;
        }
        if (data.message === "join") {
            $("#join").prop("disabled", false);
            $('#admin').text('No');
            return;
        }
        init(data.ball, data.players, data.goals, data.walls);
        $('#score').text('Score: ' + data.score);
        if (data.gameStarted) {
            ball.update(data.ball.x, data.ball.y);
            for (let i = 0; i < data.players.length; i++) {
                players[i].update(data.players[i].x, data.players[i].y);
            }
        }
        updateScene();
    };
    ws.onclose = function () {
    }





    $('#start').click(function () {
        ws.send(JSON.stringify({message: "start", admin: admin}));
    });

    $('#stop').click(function () {
        ws.send(JSON.stringify({message: "stop"}));
    });

    $('#rageQuit').click(function () {
        ws.send(JSON.stringify({message: "rageQuit", playerSide: playerSide, admin: admin}));
    });

    $('#join').click(function () {
        $("#join").prop("disabled", true);
        let data = {
            message: "join",
            name: $("#player").val()
        }
        ws.send(JSON.stringify(data));
    });

    $(document).keydown(function (event) {
        if (playerSide !== undefined) {
            let data;
            event.preventDefault();
            switch (event.key) {
                case "ArrowUp":
                    data = {
                        message: "update",
                        playerSide: playerSide,
                        x: 0,
                        y: -10,
                    }
                    if (playerSide === "LEFT" || playerSide === "RIGHT") {
                        ws.send(JSON.stringify(data));
                    }
                    break;
                case "ArrowDown":
                    data = {
                        message: "update",
                        playerSide: playerSide,
                        x: 0,
                        y: 10,
                    }
                    if (playerSide === "LEFT" || playerSide === "RIGHT") {
                        ws.send(JSON.stringify(data));
                    }
                    break;
                case "ArrowLeft":
                    data = {
                        message: "update",
                        playerSide: playerSide,
                        x: -10,
                        y: 0,
                    }
                    if (playerSide === "TOP" || playerSide === "BOTTOM") {
                        ws.send(JSON.stringify(data));
                    }
                    break;
                case "ArrowRight":
                    data = {
                        message: "update",
                        playerSide: playerSide,
                        x: +10,
                        y: 0,
                    }
                    if (playerSide === "TOP" || playerSide === "BOTTOM") {
                        ws.send(JSON.stringify(data));
                    }
                    break;
            }
        }
    });
    $("#quit").click(function () {
        console.log("Connection closed");
        ws.close();
        ws = null;
    });
});