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
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    form {
        background-color: #111;
        padding: 20px;
        border-radius: 10px;
        width: 400px;
        margin-top: 20px;
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
        color: white;
        box-sizing: border-box; /* 🔑 pour uniformiser la largeur */
        font: inherit;          /* 🔑 pour hériter de la même police */
        appearance: none;
    }
    button, .menu-btn {
        background-color: #0f0;
        color: #000;
        padding: 10px;
        border: none;
        cursor: pointer;
        width: 97%;
        margin-top: 10px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        border-radius: 5px;
    }
    button:hover, .menu-btn:hover {
        background-color: #0c0;
    }
    #titre {
        text-align: center;
    }
</style>
</head>
<body>
    <div class="container">
        <!-- 🔙 Bouton retour au menu -->
        <a href="../index.html" class="menu-btn">⬅ Retour au menu des jeux</a>

        <form method="POST" action="start_game.php">
            <h2 id="titre">Configurer le Jeu de la Vie</h2>

            <label for="rows">Nombre de lignes :</label>
            <input type="number" name="rows" id="rows" value="15" min="5" max="100" required>

            <label for="cols">Nombre de colonnes :</label>
            <input type="number" name="cols" id="cols" value="30" min="5" max="100" required>

            <label for="fill">Probabilité de cellule vivante au départ (%):</label>
            <input type="number" name="fill" id="fill" value="25" min="0" max="100" step="1" required>

            <label for="delay">Délai entre chaque tour (ms) :</label>
            <input type="number" name="delay" id="delay" value="200" min="10" max="5000" required>

            <label for="choix">Type de Rendu</label>
            <select name="choix" id="choix">
                <option value="croix">Croix</option>
                <option value="age">Age</option>
            </select>

            <button type="submit">Lancer le jeu</button>
        </form>
    </div>
</body>
</html>
