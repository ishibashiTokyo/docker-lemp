
<p>$_ENV['DATABASE_HOST'] : <?php echo $_ENV['DATABASE_HOST']; ?></p>
<p>$_ENV['MYSQL_USER'] : <?php echo $_ENV['MYSQL_USER']; ?></p>
<p>$_ENV['MYSQL_PASSWORD'] : <?php echo $_ENV['MYSQL_PASSWORD']; ?></p>
<p>$_ENV['MYSQL_DATABASE'] : <?php echo $_ENV['MYSQL_DATABASE']; ?></p>

<?php
try {
    $pdo = new PDO("mysql:host=db; dbname=docker_db; charset=utf8", $username = 'docker', $password = 'docker');
    $sql = 'SHOW databases;';
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch(PDOException $e) {
    echo $e->getMessage();
    die();
}
$pdo = null;

phpinfo();