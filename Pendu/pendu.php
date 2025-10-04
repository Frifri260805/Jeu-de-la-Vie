<?php
$pdo = new PDO("mysql:host=sql211.infinityfree.com;dbname=if0_40050755_pendu", "if0_40050755", "uranie2005");

$party = $_GET['party'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM parties WHERE code = :party");
$stmt->execute([':party' => $party]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) die("Partie introuvable.");

// Afficher le mot avec les lettres trouvées
$mot = $game['mot'];
$lettres = str_split($mot);
$affiche = '';
foreach ($lettres as $l) {
    $affiche .= (strpos($game['lettres_trouvees'], $l) !== false) ? $l : "_";
    $affiche .= " ";
}
?>
<h1>Pendu</h1>
<p>Mot : <?= $affiche ?></p>
<p>Erreurs : <?= $game['erreurs'] ?>/6</p>

<form method="post" action="update.php">
    <input type="hidden" name="party" value="<?= $party ?>">
    <label>Lettre : <input type="text" name="lettre" maxlength="1"></label>
    <button type="submit">Proposer</button>
</form>
