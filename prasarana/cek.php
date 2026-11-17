<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "step 1: PHP OK<br>";

require_once __DIR__ . '/config/config.php';
echo "step 2: config OK<br>";

require_once __DIR__ . '/core/Database.php';
echo "step 3: Database class OK<br>";

require_once __DIR__ . '/core/Model.php';
echo "step 4: Model class OK<br>";

require_once __DIR__ . '/core/Controller.php';
echo "step 5: Controller class OK<br>";

$config = require __DIR__ . '/config/database.php';
echo "step 6: db config OK<br>";

try {
    $db = Database::getInstance()->getConnection();
    echo "step 7: DB connection OK<br>";
    
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "step 8: Tables = " . implode(', ', $tables) . "<br>";
} catch (Exception $e) {
    echo "step 7 ERROR: " . $e->getMessage() . "<br>";
}

require_once __DIR__ . '/app/models/Feedback.php';
echo "step 9: Feedback model OK<br>";

$f = new Feedback();
$data = $f->getAll();
echo "step 10: getAll() OK - " . count($data) . " rows<br>";
?>
