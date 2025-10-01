<?php

    $rows = $_GET['rows'];
    $cols = $_GET['cols'];

?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>2048</title>
<style>
  html, body {
    height: 100%;
    margin: 0;
    background: #222;
    color: white;
    font-family: monospace;
    font-size: 18px;
    position: relative;
  }

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

  .main-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
  }

  table {
    border-collapse: collapse;
    margin-top: 20px;
  }

  td {
    width: 80px;
    height: 80px;
    border: 2px solid white;
    text-align: center;
    vertical-align: middle;
    font-size: 24px;
    color: black;  
    font-weight: bold;
    transition: all 0.2s ease;
  }

  td.new-tile {
    transform: scale(1.3);
    animation: pop 0.2s forwards;
  }

  td.merged-tile {
    transform: scale(1.3);
    animation: merge 0.2s forwards;
  }

  @keyframes pop {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
  }

  @keyframes merge {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
  }
</style>
</head>
<body>
    <a href='../index.html'><button class="btn">Retour au menu</button></a>
    <div class="main-content">
        <h2>2048</h2>
        <p>Score: <span id="score">0</span></p>
        <div id="grid"></div>
        <p id="message"></p>
    </div>

<script>
const gridContainer = document.getElementById("grid");
const message = document.getElementById("message");
const scoreDisplay = document.getElementById("score");
let gridT = [];
let row = <?= $rows ?>;
let col = <?= $cols ?>;
let score = 0;
let mergedPositions = []; // pour animation fusion
let newTilePosition = null; // pour animation nouvelle tuile

generateGrid();
renderGrid();

document.addEventListener("keydown", e => {
    mergedPositions = [];
    newTilePosition = null;
    let moved = false;
    switch(e.key) {
        case "ArrowUp": moved = moveUp(); break;
        case "ArrowDown": moved = moveDown(); break;
        case "ArrowLeft": moved = moveLeft(); break;
        case "ArrowRight": moved = moveRight(); break;
    }
    if(moved){
        addRandomTile();
        renderGrid();
        scoreDisplay.textContent = score;
        if(isGameOver()) message.textContent = "Game Over!";
    }
});

function generateGrid(){
    for(let r = 0; r < row; r++){
        gridT[r] = [];
        for(let c = 0; c < col; c++){
            gridT[r][c] = 0;
        }
    }
    addRandomTile();
    addRandomTile();
}

function addRandomTile(){
    let empty = [];
    for(let r = 0; r < row; r++){
        for(let c = 0; c < col; c++){
            if(gridT[r][c] === 0) empty.push([r,c]);
        }
    }
    if(empty.length === 0) return;
    let [r,c] = empty[Math.floor(Math.random() * empty.length)];
    gridT[r][c] = Math.random() < 0.9 ? 2 : 4;
    newTilePosition = [r,c];
}

function renderGrid(){
    let table = '<table>';
    for(let r = 0; r < row; r++){
        table += '<tr>';
        for(let c = 0; c < col; c++){
            let val = gridT[r][c];
            let classes = '';
            if(newTilePosition && newTilePosition[0]===r && newTilePosition[1]===c) classes = 'new-tile';
            for(const pos of mergedPositions){
                if(pos[0]===r && pos[1]===c) classes = 'merged-tile';
            }
            table += `<td class="${classes}" style="background:${getColor(val)}">${val===0?'':val}</td>`;
        }
        table += '</tr>';
    }
    table += '</table>';
    gridContainer.innerHTML = table;
}

function getColor(val){
    switch(val){
        case 0: return "#333";
        case 2: return "#eee4da";
        case 4: return "#ede0c8";
        case 8: return "#f2b179";
        case 16: return "#f59563";
        case 32: return "#f67c5f";
        case 64: return "#f65e3b";
        case 128: return "#edcf72";
        case 256: return "#edcc61";
        case 512: return "#edc850";
        case 1024: return "#edc53f";
        case 2048: return "#edc22e";
        default: return "#3c3a32";
    }
}

function slide(row){
    let arr = row.filter(v=>v!==0);
    for(let i=0; i<arr.length-1; i++){
        if(arr[i]===arr[i+1]){
            arr[i]*=2;
            score += arr[i];
            arr[i+1]=0;
            mergedPositions.push([i]); // temporaire, sera ajusté
        }
    }
    arr = arr.filter(v=>v!==0);
    while(arr.length<row.length) arr.push(0);
    return arr;
}

function moveLeft(){
    let moved = false;
    for(let r=0;r<row;r++){
        let original = [...gridT[r]];
        let newRow = slide(gridT[r]);
        for(let i=0;i<row;i++){
            if(original[i]!==newRow[i] && original[i]!==0 && newRow[i]===original[i]*2){
                mergedPositions.push([r,i]);
            }
        }
        if(newRow.toString()!==gridT[r].toString()){
            gridT[r]=newRow;
            moved = true;
        }
    }
    return moved;
}

function moveRight(){
    let moved = false;
    for(let r=0;r<row;r++){
        let original = [...gridT[r]];
        let newRow = slide(gridT[r].slice().reverse()).reverse();
        for(let i=0;i<row;i++){
            if(original[i]!==newRow[i] && original[i]!==0 && newRow[i]===original[i]*2){
                mergedPositions.push([r,i]);
            }
        }
        if(newRow.toString()!==gridT[r].toString()){
            gridT[r]=newRow;
            moved = true;
        }
    }
    return moved;
}

function moveUp(){
    let moved=false;
    for(let c=0;c<col;c++){
        let colArr=[];
        for(let r=0;r<row;r++) colArr.push(gridT[r][c]);
        let newCol = slide(colArr);
        for(let r=0;r<row;r++){
            if(gridT[r][c]!==newCol[r] && gridT[r][c]!==0 && newCol[r]===gridT[r][c]*2){
                mergedPositions.push([r,c]);
            }
            if(gridT[r][c]!==newCol[r]){
                gridT[r][c]=newCol[r];
                moved=true;
            }
        }
    }
    return moved;
}

function moveDown(){
    let moved=false;
    for(let c=0;c<col;c++){
        let colArr=[];
        for(let r=0;r<row;r++) colArr.push(gridT[r][c]);
        let newCol = slide(colArr.reverse()).reverse();
        for(let r=0;r<row;r++){
            if(gridT[r][c]!==newCol[r] && gridT[r][c]!==0 && newCol[r]===gridT[r][c]*2){
                mergedPositions.push([r,c]);
            }
            if(gridT[r][c]!==newCol[r]){
                gridT[r][c]=newCol[r];
                moved=true;
            }
        }
    }
    return moved;
}

function isGameOver(){
    for(let r=0;r<row;r++){
        for(let c=0;c<col;c++){
            if(gridT[r][c]===0) return false;
            if(c<col-1 && gridT[r][c]===gridT[r][c+1]) return false;
            if(r<row-1 && gridT[r][c]===gridT[r+1][c]) return false;
        }
    }
    return true;
}
</script>
</body>
</html>
