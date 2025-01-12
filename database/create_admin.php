<<<<<<< HEAD
<?php
// Password to hash
$password = '123456';
$username = 'admin';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Generate the SQL query
$sql = "INSERT INTO users (username, password, status) VALUES ('$username', '$hashed_password', 'active');";

echo "Use this SQL query to insert the admin user:\n\n";
echo $sql;
=======
<?php
// Password to hash
$password = '123456';
$username = 'admin';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Generate the SQL query
$sql = "INSERT INTO users (username, password, status) VALUES ('$username', '$hashed_password', 'active');";

echo "Use this SQL query to insert the admin user:\n\n";
echo $sql;
>>>>>>> 228c558 (updated)
?>