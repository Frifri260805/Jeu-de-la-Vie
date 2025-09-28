<?php
    $rows = $_GET['rows'];
    $cols = $_GET['cols'];
    $delay = $_GET['delay'];
    $probability = $_GET['probability'];
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
    <a href='index.php'><button class="btn">Retour au menu</button></a>
    <div class="main-content">
        <h2>Jeu de la Vie</h2>
        <pre id="grid"></pre>
        <p>Touches: P = pause, R = reset</p>
    </div>
    <script>
        const rows = <?= $rows?>;
        const cols = <?= $cols?>;
        let grid = [];
        let age = [];
        const delayMs = <?= $delay?>;
        let round = 0;
        const defaultFill = <?= $probability?>;

        let paused = false;

        const gridContainer = document.getElementById("grid");

        GenerateGrid(defaultFill);
        RenderGrid();
        gameLoop();

        function sleep(ms){
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        async function gameLoop(){
            while(true){
                if(!paused){
                    NextStep();
                    RenderGrid();
                }
                await sleep(delayMs);
            }
        }

        function GenerateGrid(probability){
            for(let r = 0; r < rows; r++){
                grid[r] = [];
                age[r] = [];
                for(let c = 0; c < cols; c++){
                    grid[r][c] = Math.random() < probability;
                    age[r][c] = grid[r][c] ? 1 : 0;
                }
            }
        }

        function RenderGrid(){
            let output = '';
            for(let r = 0; r < rows; r++){
                let line = '|';
                for(let c = 0; c < cols; c++){
                    line += grid[r][c] ? 'X' : ' ';
                    line += '|';
                }
                output += line + '\n';
            }
            output += `Round: ${round}`;
            gridContainer.textContent = output;
        }

        function CountNeighbors(r, c){
            let count = 0;
            for(let dr = -1; dr <= 1; dr++){
                for(let dc = -1; dc <= 1; dc++){
                    if ((dr === 0 && dc === 0) 
                    || (dr === -1 && r === 0) 
                    || (dc === -1 && c === 0)
                    || (dr === 1 && r === rows - 1) 
                    || (dc === 1 && c === cols - 1)) continue;

                    const rr = r + dr;
                    const cc = c + dc;
                    if (grid[rr][cc]) count++;
                }
            }
            return count;
        }

        function NextStep(){
            let next = [];
            round++;
            for(let r = 0; r < rows; r++){
                next[r] = [];
                for(let c = 0; c < cols; c++){
                    const n = CountNeighbors(r, c);
                    if(grid[r][c]){
                        next[r][c] = (n === 2 || n === 3) && age[r][c] < 6;
                        if(next[r][c]){
                            age[r][c]++;
                        } else {
                            age[r][c] = 0;
                        }
                    } else {
                        next[r][c] = (n === 3);
                        age[r][c] = next[r][c] ? 1 : 0;
                    }
                }
            }
            grid = next;
        }

        // Gestion clavier
        document.addEventListener("keydown", (e) => {
            if(e.key.toLowerCase() === 'p'){
                paused = !paused;
            } else if(e.key.toLowerCase() === 'r'){
                round = 0;
                GenerateGrid(defaultFill);
                RenderGrid();
            }
        });
    </script>
</body>
</html>
