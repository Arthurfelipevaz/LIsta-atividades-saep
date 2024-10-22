<!-- fornecedor.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Fornecedores</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Fornecedores</h1>
    </header>
    <nav>
        <a href="index.php">Página Inicial</a>
    </nav>
    <div class="container">
        <!-- Incluir Fornecedor -->
        <h2>Incluir Fornecedor</h2>
        <?php
        require 'conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $endereco = $_POST['endereco'];
            $nome = $_POST['nome'];

            try {
                $sql = "INSERT INTO fornecedor (for_numero, for_endereco, for_nome) VALUES (:numero, :endereco, :nome)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':endereco' => $endereco,
                    ':nome' => $nome
                ]);
                echo '<div class="alert">Fornecedor incluído com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir o fornecedor: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="fornecedor.php" method="post">
            <input type="hidden" name="incluir" value="1">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" required>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <button type="submit">Incluir Fornecedor</button>
        </form>

        <!-- Alterar Fornecedor -->
        <h2>Alterar Fornecedor</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $numero = $_POST['numero'];
            $endereco = $_POST['endereco'];
            $nome = $_POST['nome'];

            try {
                $sql = "UPDATE fornecedor SET for_numero = :numero, for_endereco = :endereco, for_nome = :nome WHERE for_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':endereco' => $endereco,
                    ':nome' => $nome,
                    ':id' => $id
                ]);
                echo '<div class="alert">Fornecedor alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o fornecedor: ' . $e->getMessage() . '</div>';
            }
        }

        // Buscar Fornecedor para preencher o formulário de alteração
        $id_alterar = '';
        $numero_alterar = '';
        $endereco_alterar = '';
        $nome_alterar = '';

        if (isset($_GET['alterar_id'])) {
            $id_alterar = $_GET['alterar_id'];
            try {
                $sql = "SELECT * FROM fornecedor WHERE for_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id_alterar]);
                $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($fornecedor) {
                    $numero_alterar = $fornecedor['for_numero'];
                    $endereco_alterar = $fornecedor['for_endereco'];
                    $nome_alterar = $fornecedor['for_nome'];
                } else {
                    echo '<div class="error">Fornecedor não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o fornecedor: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="fornecedor.php" method="post">
            <input type="hidden" name="alterar" value="1">
            <label for="id">ID do Fornecedor:</label>
            <input type="number" name="id" id="id" value="<?php echo htmlspecialchars($id_alterar); ?>" required>

            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($endereco_alterar); ?>" required>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($nome_alterar); ?>" required>

            <button type="submit">Alterar Fornecedor</button>
        </form>

        <!-- Excluir Fornecedor -->
        <h2>Excluir Fornecedor</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $id = $_POST['id'];

            try {
                $sql = "DELETE FROM fornecedor WHERE for_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Fornecedor excluído com sucesso!</div>';
                } else {
                    echo '<div class="error">Fornecedor não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir o fornecedor: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="fornecedor.php" method="post">
            <input type="hidden" name="excluir" value="1">
            <label for="id">ID do Fornecedor:</label>
            <input type="number" name="id" id="id" required>

            <button type="submit">Excluir Fornecedor</button>
        </form>

        <!-- Buscar Fornecedor -->
        <h2>Buscar Fornecedor</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $id = $_GET['buscar'];

            try {
                $sql = "SELECT * FROM fornecedor WHERE for_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($fornecedor) {
                    echo '<h3>Detalhes do Fornecedor</h3>';
                    echo '<p><strong>ID:</strong> ' . htmlspecialchars($fornecedor['for_id']) . '</p>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($fornecedor['for_numero']) . '</p>';
                    echo '<p><strong>Endereço:</strong> ' . htmlspecialchars($fornecedor['for_endereco']) . '</p>';
                    echo '<p><strong>Nome:</strong> ' . htmlspecialchars($fornecedor['for_nome']) . '</p>';
                } else {
                    echo '<div class="error">Fornecedor não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o fornecedor: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="fornecedor.php" method="get">
            <label for="buscar_id">ID do Fornecedor:</label>
            <input type="number" name="buscar" id="buscar_id" required>

            <button type="submit">Buscar Fornecedor</button>
        </form>

        <!-- Listagem de Todos os Fornecedores -->
        <h2>Listagem de Fornecedores</h2>
        <?php
        try {
            $sql = "SELECT * FROM fornecedor";
            $stmt = $pdo->query($sql);
            $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($fornecedores) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>ID</th><th>Número</th><th>Endereço</th><th>Nome</th><th>Ações</th></tr>';
                foreach ($fornecedores as $fornecedor) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($fornecedor['for_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($fornecedor['for_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($fornecedor['for_endereco']) . '</td>';
                    echo '<td>' . htmlspecialchars($fornecedor['for_nome']) . '</td>';
                    echo '<td>';
                    echo '<a href="fornecedor.php?alterar_id=' . htmlspecialchars($fornecedor['for_id']) . '">Alterar</a> | ';
                    echo '<a href="fornecedor.php?buscar=' . htmlspecialchars($fornecedor['for_id']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum fornecedor encontrado.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar os fornecedores: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</body>
</html>
