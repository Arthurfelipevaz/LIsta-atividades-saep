// conexao.php
<?php
$host = 'localhost';
$dbname = 'industria';
$username = 'root'; // ajuste para o usuário do seu banco de dados
$password = '';     // ajuste para a senha do seu banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
