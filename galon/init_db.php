<?php
include 'config.php';

$sql = file_get_contents('path/to/project/sql/schema.sql');

if ($conn->multi_query($sql) === TRUE) {
    echo "Database and tables created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
