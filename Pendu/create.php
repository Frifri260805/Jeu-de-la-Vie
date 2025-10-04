<?php
$pdo = new PDO("mysql:host=sql211.infinityfree.com;dbname=if0_40050755_pendu", "if0_40050755", "uranie2005");

$party = $_GET['party'] ?? '';
$mot = strtoupper("CHAT"); // tu pourrais le choisir aléatoirement
$code = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

$sql = "INSERT INTO parties (code, mot, lettres_trouvees) VALUES (:code, :mot, '')";
$stmt = $pdo->prepare($sql);
$stmt->execute([':code' => $code, ':mot' => $mot]);

echo "Partie créée : <a href='pendu.php?party=$code'>Rejoins ta partie</a>";
