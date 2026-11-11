<?php

function queryMain(PDO $conn, int $lookup)
{
  $alpha = '/^[A-Za-z_ ]+$/';
  if (!isset($_GET["country"]) || !preg_match($alpha, $_GET["country"])) {
    $lookup = 0;
  }

  switch ($lookup) {
    case 1:
      return queryCountry_byName($conn, $_GET["country"]);

    case 2:
      return queryCity_byCountry_byName($conn, $_GET["country"]);

    default:
      if (isset($_GET["lookup"]) && $_GET["lookup"] == "city") {
        return $conn->query("SELECT cities.name AS city, countries.name AS country, cities.district, cities.population FROM cities JOIN countries ON cities.country_code = countries.code ORDER BY cities.country_code ASC")->fetchAll(PDO::FETCH_ASSOC);
      }
      else {
        return $conn->query("SELECT * FROM countries")->fetchAll(PDO::FETCH_ASSOC);
      }
  }
}

function queryCountry_byName(PDO $db, string $country)
{
  $stmt = $db->prepare("SELECT * FROM countries WHERE countries.name LIKE :countryName");
  $param = $country . "%";
  $stmt->bindParam(":countryName", $param, PDO::PARAM_STR);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function queryCity_byCountry_byName(PDO $db, string $country)
{
  $stmt = $db->prepare("SELECT cities.name AS city, countries.name AS country, cities.district, cities.population FROM cities JOIN countries ON cities.country_code = countries.code WHERE countries.name LIKE :countryName ORDER BY cities.country_code ASC");
  $param = $country . "%";
  $stmt->bindParam(":countryName", $param, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createTableByColumn(array $columns, array $sqlData)
{
  $e = "<table><tr>";
  foreach ($columns as $c) {
    $e .= "<th>" . ucwords(str_replace("_", " ", $c)) . "</th>";
  }
  $e .= "</tr>";

  foreach ($sqlData as $country) {
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

if (isset($_GET["lookup"]) && $_GET["lookup"] == "city") {
  $tableHeaders = ["city", "district", "population"];
  $result = queryMain($conn, 2);
  
  if (count($result) <= 0 || !isset($result[0]["city"])) {
    echo "<p>No entries found for this query.</p>";
    return;
  }

  // print multiple tables for each country
  $fuzzyCountries = array_unique(array_map(function($v) { return $v["country"]; }, $result));
  foreach ($fuzzyCountries as $country) {
    echo "<h3>" . $country . "</h3>";
    echo createTableByColumn($tableHeaders, array_filter($result, function ($value) use ($country) { return $value["country"] == $country; }));
  }

} else {
  $tableHeaders = ["name", "continent", "independence_year", "head_of_state"];
  $result = queryMain($conn, 1);

  if (count($result) <= 0) {
    echo "<p>No entries found for this query.</p>";
    return;
  }

  echo createTableByColumn($tableHeaders, $result);
}
?>
