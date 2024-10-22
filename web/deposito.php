<!-- deposito.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Depósitos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Depósitos</h1>
    </header>
    <nav>
        <a href="index.php">Página Inicial</a>
    </nav>
    <div class="container">
        <!-- Incluir Depósito -->
        <h2>Incluir Depósito</h2>
        <?php
        require 'conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $endereco = $_POST['endereco'];

            try {
                $sql = "INSERT INTO deposito (dep_numero, dep_endereco) VALUES (:numero, :endereco)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':endereco' => $endereco
                ]);
                echo '<div class="alert">Depósito incluído com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir o depósito: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="deposito.php" method="post">
            <input type="hidden" name="incluir" value="1">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" required>

            <button type="submit">Incluir Depósito</button>
        </form>

        <!-- Alterar Depósito -->
        <h2>Alterar Depósito</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $numero = $_POST['numero'];
            $endereco = $_POST['endereco'];

            try {
                $sql = "UPDATE deposito SET dep_numero = :numero, dep_endereco = :endereco WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':endereco' => $endereco,
                    ':id' => $id
                ]);
                echo '<div class="alert">Depósito alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o depósito: ' . $e->getMessage() . '</div>';
            }
        }

        // Buscar Depósito para preencher o formulário de alteração
        $id_alterar = '';
        $numero_alterar = '';
        $endereco_alterar = '';

        if (isset($_GET['alterar_id'])) {
            $id_alterar = $_GET['alterar_id'];
            try {
                $sql = "SELECT * FROM deposito WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id_alterar]);
                $deposito = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($deposito) {
                    $numero_alterar = $deposito['dep_numero'];
                    $endereco_alterar = $deposito['dep_endereco'];
                } else {
                    echo '<div class="error">Depósito não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o depósito: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="deposito.php" method="post">
            <input type="hidden" name="alterar" value="1">
            <label for="id">ID do Depósito:</label>
            <input type="number" name="id" id="id" value="<?php echo htmlspecialchars($id_alterar); ?>" required>

            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($endereco_alterar); ?>" required>

            <button type="submit">Alterar Depósito</button>
        </form>

        <!-- Excluir Depósito -->
        <h2>Excluir Depósito</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $id = $_POST['id'];

            try {
                $sql = "DELETE FROM deposito WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Depósito excluído com sucesso!</div>';
                } else {
                    echo '<div class="error">Depósito não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir o depósito: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="deposito.php" method="post">
            <input type="hidden" name="excluir" value="1">
            <label for="id">ID do Depósito:</label>
            <input type="number" name="id" id="id" required>

            <button type="submit">Excluir Depósito</button>
        </form>

        <!-- Buscar Depósito -->
        <h2>Buscar Depósito</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $id = $_GET['buscar'];

            try {
                $sql = "SELECT * FROM deposito WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $deposito = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($deposito) {
                    echo '<h3>Detalhes do Depósito</h3>';
                    echo '<p><strong>ID:</strong> ' . htmlspecialchars($deposito['dep_id']) . '</p>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($deposito['dep_numero']) . '</p>';
                    echo '<p><strong>Endereço:</strong> ' . htmlspecialchars($deposito['dep_endereco']) . '</p>';
                } else {
                    echo '<div class="error">Depósito não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o depósito: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="deposito.php" method="get">
            <label for="buscar_id">ID do Depósito:</label>
            <input type="number" name="buscar" id="buscar_id" required>

            <button type="submit">Buscar Depósito</button>
        </form>

        <!-- Listagem de Todos os Depósitos -->
        <h2>Listagem de Depósitos</h2>
        <?php
        try {
            $sql = "SELECT * FROM deposito";
            $stmt = $pdo->query($sql);
            $depositos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($depositos) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>ID</th><th>Número</th><th>Endereço</th><th>Ações</th></tr>';
                foreach ($depositos as $deposito) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($deposito['dep_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($deposito['dep_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($deposito['dep_endereco']) . '</td>';
                    echo '<td>';
                    echo '<a href="deposito.php?alterar_id=' . htmlspecialchars($deposito['dep_id']) . '">Alterar</a> | ';
                    echo '<a href="deposito.php?buscar=' . htmlspecialchars($deposito['dep_id']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum depósito encontrado.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar os depósitos: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</body>
</html>
