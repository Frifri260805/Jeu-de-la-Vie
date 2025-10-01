<?php


    $choix = $_GET['Choix'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Jeu de la Vie</title>
<style>
  html, body {
    height: 100%;
    margin: 0;
    background: #222;
    color: white;
    font-family: monospace;
    font-size: 18px;
    position: relative; /* nécessaire pour position:absolute du bouton */
  }

  /* Bouton en haut à gauche */
  .btn {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #0f0;
    color: #222;
    font-weight: bold;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.2s;
  }

  .btn:hover {
    background-color: #0c0;
  }

  .btn a {
    color: #222;
    text-decoration: none;
  }

  /* Contenu principal centré */
  .main-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
  }

  pre {
    font-family: monospace;
    line-height: 1.2;
    font-size: 1.2em;
    margin: 20px 0;
  }

  h2, p {
    margin: 10px 0;
  }
</style>
</head>
<body>
    <a href='snake.php'><button class="btn">Retour au menu</button></a>
    <div class="main-content">
        <h2>Snake</h2>
        <pre id="grid"></pre>
        <pre id="gridPause"></pre>
        <pre id="gridPoint"></pre>
    </div>
    <script>
        const choix = "<?= $choix?>";
        const gridContainer = document.getElementById("grid");
        const gridPoint = document.getElementById("gridPoint");
        gridPoint.innerHTML = '<p>Points: 0</p>';

        const nbNourriture = 2;

        let nbPoint = 0;
        let row = 0;
        let col = 0;

        let grid = [];

        let snake = [];
        let direction = { r: 0, c: 1 }; // droite par défaut
        let gameInterval;

        GenerateGrid(choix);
        InitSnake();
        RenderGrid();
        StartGame();

        function GenerateGrid(choix){
            switch(choix){
                case "facile":
                    row = 20;
                    col = 50;
                    break;
                case "moyen":
                    row = 20;
                    col = 40;
                    break;
                case "difficile":
                    row = 10;
                    col = 30;
                    break;
            }
            for(var r = 0; r < row; r++){
                grid[r] = [];
                for(var c = 0; c < col; c++){
                    grid[r][c] = 0;
                }
            }

            for(var nb = 0; nb < nbNourriture; nb++){
                const foodRow = Math.floor(Math.random() * row);
                const foodCol = Math.floor(Math.random() * col);
                grid[foodRow][foodCol] = 1;
            }

            const rowSnake = row / 2;
            const colSnake = col / 2;
            grid[rowSnake][colSnake] = 2;
        }


        function InitSnake() {
            const startRow = Math.floor(row / 2);
            const startCol = Math.floor(col / 2);

            snake = [
                { r: startRow, c: startCol },
                { r: startRow, c: startCol - 1 },
                { r: startRow, c: startCol - 2 }
            ];
        }

        function StartGame() {
            gameInterval = setInterval(Update, 200); // toutes les 200 ms
        }

        function Update() {
            // Nouvelle position de la tête
            const head = { 
                r: snake[0].r + direction.r, 
                c: snake[0].c + direction.c 
            };

            // Vérification collision avec mur
            if (head.r < 0 || head.r >= row || head.c < 0 || head.c >= col) {
                GameOver();
                return;
            }

            // Vérification collision avec le corps
            for (let part of snake) {
                if (part.r === head.r && part.c === head.c) {
                    GameOver();
                    return;
                }
            }

            // Ajouter la tête
            snake.unshift(head);

            // Vérifier si on mange
            if (grid[head.r][head.c] === 1) {
                // on laisse la queue → le serpent grandit
                UpdatePoint();
                grid[head.r][head.c] = 0;
                PlaceFood();
            } else {
                // sinon on retire la queue
                snake.pop();
            }

            RenderGrid();
        }

        function PlaceFood() {
            let fr, fc;
            do {
                fr = Math.floor(Math.random() * row);
                fc = Math.floor(Math.random() * col);
            } while (snake.some(p => p.r === fr && p.c === fc));
            grid[fr][fc] = 1;
        }

        function RenderGrid() {
            // reset
            for (let r = 0; r < row; r++) {
                for (let c = 0; c < col; c++) {
                    if (grid[r][c] !== 1) grid[r][c] = 0; // garder la nourriture
                }
            }

            // snake
            for (let part of snake) {
                grid[part.r][part.c] = 2;
            }

            // affichage
            let output = '';
            for (let r = 0; r <= row+1; r++) {
                let line = '';
                for (let c = 0; c <= col+1; c++) {
                    if (r === 0 || r === row+1) {
                        line += '-';
                    } else if (c === 0 || c === col+1) {
                        line += '|';
                    } else {
                        switch (grid[r-1][c-1]) {
                            case 0: line += ' '; break;
                            case 1: line += '.'; break;
                            case 2: line += 'O'; break;
                        }
                    }
                }
                output += line + '<br>';
            }
            gridContainer.innerHTML = output;
        }

        function GameOver() {
            clearInterval(gameInterval);
            alert("Game Over !");
        }

        function UpdatePoint(){
            nbPoint++;
            gridPoint.innerHTML = `<p>Points: ${nbPoint}</p>`;
        }

        document.addEventListener("keydown", (e) => {
        switch (e.key) {
            case "ArrowUp":
                if (direction.r !== 1) direction = { r: -1, c: 0 };
                break;
            case "ArrowDown":
                if (direction.r !== -1) direction = { r: 1, c: 0 };
                break;
            case "ArrowLeft":
                if (direction.c !== 1) direction = { r: 0, c: -1 };
                break;
            case "ArrowRight":
                if (direction.c !== -1) direction = { r: 0, c: 1 };
                break;
        }
    });

    </script>
</body>
</html>
