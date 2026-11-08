<?php

function queryMain(PDO $conn) {
  $alpha = '/^[A-Za-z_ ]+$/';

  // country query
  if (isset($_GET["country"]) && preg_match($alpha, $_GET["country"])) {
    return countryQuery_byName($conn, $_GET["country"]);
  }

  // default query
  return $conn->query("SELECT * FROM countries")->fetchAll(PDO::FETCH_ASSOC);
}

function countryQuery_byName(PDO $db, string $country) {
  $stmt = $db->prepare("SELECT * FROM countries WHERE countries.name LIKE :countryName");
  $param = $country . "%";
  $stmt->bindParam(":countryName", $param, PDO::PARAM_STR);
  $stmt->execute(); 

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<?php

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$results = queryMain($conn);
?>

<ul>
<?php foreach ($results as $row): ?>
  <li><?= $row['name'] . ' is ruled by ' . $row['head_of_state']; ?></li>
<?php endforeach; ?>
</ul>
