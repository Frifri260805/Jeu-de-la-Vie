<?php
$pdo = new PDO("mysql:host=sql211.infinityfree.com;dbname=if0_40050755_pendu", "if0_40050755", "uranie2005");

$party = $_GET['party'] ?? '';
$party = $_POST['party'];
$lettre = strtoupper($_POST['lettre']);

$stmt = $pdo->prepare("SELECT * FROM parties WHERE code = :party");
$stmt->execute([':party' => $party]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) die("Partie introuvable.");

$mot = $game['mot'];
$lettres_trouvees = $game['lettres_trouvees'];
$erreurs = $game['erreurs'];

if (strpos($mot, $lettre) !== false) {
    // bonne lettre
    if (strpos($lettres_trouvees, $lettre) === false) {
        $lettres_trouvees .= $lettre;
    }
} else {
    // mauvaise lettre
    $erreurs++;
}

$sql = "UPDATE parties SET lettres_trouvees = :lettres, erreurs = :erreurs WHERE code = :party";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':lettres' => $lettres_trouvees,
    ':erreurs' => $erreurs,
    ':party'   => $party
]);

header("Location: pendu.php?party=" . $party);
