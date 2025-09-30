<?php
    $rows = $_GET['rows'];
    $cols = $_GET['cols'];
    $delay = $_GET['delay'];
    $probability = $_GET['probability'];
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
    <a href='jeu-de-la-vie.php'><button class="btn">Retour au menu</button></a>
    <div class="main-content">
        <h2>Jeu de la Vie</h2>
        <pre id="grid"></pre>
        <pre id="gridPause"></pre>
        <pre id="gridInfo"></pre>
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

        const choix = "<?= $choix?>";

        let paused = false;

        let nbVie = 0;
        let nbMort = 0;

        const gridContainer = document.getElementById("grid");
        const gridPaused = document.getElementById("gridPause");
        const gridInfo = document.getElementById("gridInfo");

        gridPaused.textContent = " ";
        GenerateGrid(defaultFill);
        gameLoop();

        function sleep(ms){
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        async function gameLoop(){
            while(true){
                if(!paused){
                    RenderGrid(choix);
                    NextStep();
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

        function RenderGrid(choix){
            let output = '';
            let outputInfo = '';
            nbVie = 0;
            nbMort = 0;
            for(let r = 0; r < rows; r++){
                let line = '|';
                for(let c = 0; c < cols; c++){
                    if(grid[r][c]){
                        nbVie++;
                        let color;
                        switch(age[r][c]){
                            case 1: color = "lime"; break;       // vert clair
                            case 2: color = "green"; break;      // vert foncé
                            case 3: color = "blue"; break;
                            case 4: color = "darkblue"; break;
                            case 5: color = "red"; break;
                            case 6: color = "darkred"; break;
                            default: color = "yellow"; break;
                        }

                        if(choix === "croix"){
                            line += `<span style="color:${color}">X</span>`;
                        } else {
                            line += `<span style="color:${color}">${age[r][c]}</span>`;
                        }
                    } else {
                        nbMort++;
                        line += ' ';
                    }
                    line += '|';
                }
                output += line + '<br>';
            }
            output += `Round: ${round}`;
            outputInfo += `Celulle en vie: ${nbVie}, Celulle mort: ${nbMort}`
            gridContainer.innerHTML = output;
            gridInfo.textContent = outputInfo;
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
                if(paused){
                    gridPaused.textContent = "En pause";
                }else{
                    gridPaused.textContent = " ";
                }
            } else if(e.key.toLowerCase() === 'r'){
                round = 0;
                GenerateGrid(defaultFill);
                RenderGrid(choix);
            }
        });
    </script>
</body>
</html>
