<?php
$conn = new mysqli('localhost', 'root', '', 'excelreader');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "insert into `data` (`id`, `FromCurrencyId`, `ToCurrencyId`, `FromCurrencyId1`, `ToCurrencyId1`, `EffectiveDate`, `ExchangeRate`) VALUES ('', 'dfg', 'gfsdg', '3', '54', 'tryty', 'gh')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>