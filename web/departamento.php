<!-- departamento.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Departamentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Departamentos</h1>
    </header>
    <nav>
        <a href="index.php">Página Inicial</a>
    </nav>
    <div class="container">
        <!-- Incluir Departamento -->
        <h2>Incluir Departamento</h2>
        <?php
        require 'conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $setor = $_POST['setor'];

            try {
                $sql = "INSERT INTO departamento (dep_numero, dep_setor) VALUES (:numero, :setor)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':setor' => $setor
                ]);
                echo '<div class="alert">Departamento incluído com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir o departamento: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="departamento.php" method="post">
            <input type="hidden" name="incluir" value="1">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" required>

            <label for="setor">Setor:</label>
            <input type="number" name="setor" id="setor" required>

            <button type="submit">Incluir Departamento</button>
        </form>

        <!-- Alterar Departamento -->
        <h2>Alterar Departamento</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $numero = $_POST['numero'];
            $setor = $_POST['setor'];

            try {
                $sql = "UPDATE departamento SET dep_numero = :numero, dep_setor = :setor WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':setor' => $setor,
                    ':id' => $id
                ]);
                echo '<div class="alert">Departamento alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o departamento: ' . $e->getMessage() . '</div>';
            }
        }

        // Buscar Departamento para preencher o formulário de alteração
        $id_alterar = '';
        $numero_alterar = '';
        $setor_alterar = '';

        if (isset($_GET['alterar_id'])) {
            $id_alterar = $_GET['alterar_id'];
            try {
                $sql = "SELECT * FROM departamento WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id_alterar]);
                $departamento = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($departamento) {
                    $numero_alterar = $departamento['dep_numero'];
                    $setor_alterar = $departamento['dep_setor'];
                } else {
                    echo '<div class="error">Departamento não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o departamento: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="departamento.php" method="post">
            <input type="hidden" name="alterar" value="1">
            <label for="id">ID do Departamento:</label>
            <input type="number" name="id" id="id" value="<?php echo htmlspecialchars($id_alterar); ?>" required>

            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>

            <label for="setor">Setor:</label>
            <input type="number" name="setor" id="setor" value="<?php echo htmlspecialchars($setor_alterar); ?>" required>

            <button type="submit">Alterar Departamento</button>
        </form>

        <!-- Excluir Departamento -->
        <h2>Excluir Departamento</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $id = $_POST['id'];

            try {
                $sql = "DELETE FROM departamento WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Departamento excluído com sucesso!</div>';
                } else {
                    echo '<div class="error">Departamento não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir o departamento: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="departamento.php" method="post">
            <input type="hidden" name="excluir" value="1">
            <label for="id">ID do Departamento:</label>
            <input type="number" name="id" id="id" required>

            <button type="submit">Excluir Departamento</button>
        </form>

        <!-- Buscar Departamento -->
        <h2>Buscar Departamento</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $id = $_GET['buscar'];

            try {
                $sql = "SELECT * FROM departamento WHERE dep_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $departamento = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($departamento) {
                    echo '<h3>Detalhes do Departamento</h3>';
                    echo '<p><strong>ID:</strong> ' . htmlspecialchars($departamento['dep_id']) . '</p>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($departamento['dep_numero']) . '</p>';
                    echo '<p><strong>Setor:</strong> ' . htmlspecialchars($departamento['dep_setor']) . '</p>';
                } else {
                    echo '<div class="error">Departamento não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o departamento: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="departamento.php" method="get">
            <label for="buscar_id">ID do Departamento:</label>
            <input type="number" name="buscar" id="buscar_id" required>

            <button type="submit">Buscar Departamento</button>
        </form>

        <!-- Listagem de Todos os Departamentos -->
        <h2>Listagem de Departamentos</h2>
        <?php
        try {
            $sql = "SELECT * FROM departamento";
            $stmt = $pdo->query($sql);
            $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($departamentos) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>ID</th><th>Número</th><th>Setor</th><th>Ações</th></tr>';
                foreach ($departamentos as $departamento) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($departamento['dep_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($departamento['dep_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($departamento['dep_setor']) . '</td>';
                    echo '<td>';
                    echo '<a href="departamento.php?alterar_id=' . htmlspecialchars($departamento['dep_id']) . '">Alterar</a> | ';
                    echo '<a href="departamento.php?buscar=' . htmlspecialchars($departamento['dep_id']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum departamento encontrado.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar os departamentos: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</body>
</html>
