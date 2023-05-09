<?php
echo '<h1>Дата и время:</h1><br>';

date_default_timezone_set('Europe/Moscow');
echo date("d/m/Y - H:i:s");


echo '<br><br><h1>Проверка подключения к БД</h1><br>';
$config = [
    'host' => 'db', #` Имя контейнера хост-БД
    'dbname' => getenv('MYSQL_DATABASE'), #` Имя целевой БД
    'user' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
];

$pdo = new PDO('mysql:'
    . 'host=' . $config['host'] #. ';port=' . '3306'
    . ';dbname=' . $config['dbname']
    ,$config['user']
    ,$config['password']
    // ,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
$query = $pdo->query('SHOW VARIABLES like "version"');
$row = $query->fetch();
echo 'MySQL version:' . $row['Value'];


echo '<br><br><h1>phpinfo</h1><br>';
phpinfo();

?>
