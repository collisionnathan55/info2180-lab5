<?php

function queryMain(PDO $conn) {
  $alpha = '/^[A-Za-z_ ]+$/';

  // country query
  if (isset($_GET["country"]) && preg_match($alpha, $_GET["country"])) {
    return queryCountry_byName($conn, $_GET["country"]);
  }

  // default query
  return $conn->query("SELECT * FROM countries")->fetchAll(PDO::FETCH_ASSOC);
}

function queryCountry_byName(PDO $db, string $country) {
  $stmt = $db->prepare("SELECT * FROM countries WHERE countries.name LIKE :countryName");
  $param = $country . "%";
  $stmt->bindParam(":countryName", $param, PDO::PARAM_STR);
  $stmt->execute(); 

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function tableCountry ($results) {
  $columns = ["name", "continent", "independence_year", "head_of_state"];

  $e = "<table><tr>";
  foreach ($columns as $c) {
    $e .= "<th>" . ucwords(str_replace("_", " ", $c)) . "</th>";
  }
  $e .= "</tr>";

  foreach ($results as $country) {
    $e .= "<tr>";
    foreach ($columns as $col) {
      $e .= "<td>" . $country[$col] . "</td>";
    }
    $e .= "</tr>";
  }

  $e .= "</table>";
  return $e;
}

?>

<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

$results = queryMain($conn);

if (isset($_GET["country"])) {
  echo tableCountry($results);
  return;
}
?>
