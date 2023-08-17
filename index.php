<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$host = 'db';
$db = 'db';
$user = 'root';
$password = 'password';

$connection = new mysqli($host, $user, $password, $db);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$filters = [
    'number' => [null, 'number', '='],
    'floor_from' => [null, 'floor', '>='],
    'floor_to' => [null, 'floor', '<='],
    'price_from' => [null, 'price', '>='],
    'price_to' => [null, 'price', '<=']
];

foreach ($filters as $param => $defaultValue) {
    if (isset($_GET[$param]) && $_GET[$param] !== "") {
        $filters[$param][0] = (int) $_GET[$param];
    }
}

$conditions = [];
foreach ($filters as $param => $value) {
    if ($value[0] !== null) {
        $conditions[] = "$value[1] $value[2] $value[0]";
    }
}

$whereClause = implode(" AND ", $conditions);

$query = "SELECT * FROM apartments";
if (!empty($whereClause)) {
    $query .= " WHERE $whereClause";
}

$statement = $connection->prepare($query);
$statement->execute();

$result = $statement->get_result();
$apartments = $result->fetch_all(MYSQLI_ASSOC);

$connection->close();

echo json_encode($apartments);

?>

