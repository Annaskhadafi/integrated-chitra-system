<?php
include 'koneksi.php';
$result = $koneksi->query("DESCRIBE user");
while($row = $result->fetch_assoc()) {
    if ($row['Field'] == 'password') {
        echo "Password column type: " . $row['Type'] . "\n";
    }
}
?>