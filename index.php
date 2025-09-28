<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Jeu de la Vie - Configuration</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #222;
        color: #0f0;
        display: flex;
        justify-content: center;
        padding-top: 50px;
    }
    form {
        background-color: #111;
        padding: 20px;
        border-radius: 10px;
        width: 400px;
    }
    label {
        display: block;
        margin: 10px 0 5px;
    }
    input, select {
        width: 97%;
        padding: 5px;
        margin-bottom: 10px;
        background-color: #333;
        border: 1px solid #0f0;
        color: white
    }
    button {
        background-color: #0f0;
        color: #000;
        padding: 10px;
        border: none;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
    }
</style>
</head>
<body>

<form method="POST" action="start_game.php">
    <h2>Configurer le Jeu de la Vie</h2>

    <label for="rows">Nombre de lignes :</label>
    <input type="number" name="rows" id="rows" value="15" min="5" max="100" required>

    <label for="cols">Nombre de colonnes :</label>
    <input type="number" name="cols" id="cols" value="30" min="5" max="100" required>

    <label for="fill">Probabilité de cellule vivante au départ :</label>
    <input type="number" name="fill" id="fill" value="0.25" min="0" max="1" step="0.01" required>

    <label for="delay">Délai entre chaque tour (ms) :</label>
    <input type="number" name="delay" id="delay" value="200" min="10" max="5000" required>

    <label for="maxAge">Âge maximum d'une cellule :</label>
    <input type="number" name="maxAge" id="maxAge" value="6" min="1" max="100" required>

    <button type="submit">Lancer le jeu</button>
</form>

</body>
</html>
