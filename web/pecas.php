<!-- pecas.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Peças</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Peças</h1>
    </header>
    <nav>
        <a href="index.php">Página Inicial</a>
    </nav>
    <div class="container">
        <!-- Incluir Peça -->
        <h2>Incluir Peça</h2>
        <?php
        require 'conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $cor = $_POST['cor'];
            $valor = $_POST['valor'];
            $peso = $_POST['peso'];

            try {
                $sql = "INSERT INTO pecas (pec_numero, pec_cor, pec_valor, pec_peso) VALUES (:numero, :cor, :valor, :peso)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':cor' => $cor,
                    ':valor' => $valor,
                    ':peso' => $peso
                ]);
                echo '<div class="alert">Peça incluída com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir a peça: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="pecas.php" method="post">
            <input type="hidden" name="incluir" value="1">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" required>

            <label for="cor">Cor:</label>
            <input type="text" name="cor" id="cor" required>

            <label for="valor">Valor (R$):</label>
            <input type="number" name="valor" id="valor" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" step="0.01" name="peso" id="peso" required>

            <button type="submit">Incluir Peça</button>
        </form>

        <!-- Alterar Peça -->
        <h2>Alterar Peça</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $numero = $_POST['numero'];
            $cor = $_POST['cor'];
            $valor = $_POST['valor'];
            $peso = $_POST['peso'];

            try {
                $sql = "UPDATE pecas SET pec_numero = :numero, pec_cor = :cor, pec_valor = :valor, pec_peso = :peso WHERE pec_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':cor' => $cor,
                    ':valor' => $valor,
                    ':peso' => $peso,
                    ':id' => $id
                ]);
                echo '<div class="alert">Peça alterada com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar a peça: ' . $e->getMessage() . '</div>';
            }
        }

        // Buscar Peça para preencher o formulário de alteração
        $id_alterar = '';
        $numero_alterar = '';
        $cor_alterar = '';
        $valor_alterar = '';
        $peso_alterar = '';

        if (isset($_GET['alterar_id'])) {
            $id_alterar = $_GET['alterar_id'];
            try {
                $sql = "SELECT * FROM pecas WHERE pec_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id_alterar]);
                $peca = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($peca) {
                    $numero_alterar = $peca['pec_numero'];
                    $cor_alterar = $peca['pec_cor'];
                    $valor_alterar = $peca['pec_valor'];
                    $peso_alterar = $peca['pec_peso'];
                } else {
                    echo '<div class="error">Peça não encontrada.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar a peça: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="pecas.php" method="post">
            <input type="hidden" name="alterar" value="1">
            <label for="id">ID da Peça:</label>
            <input type="number" name="id" id="id" value="<?php echo htmlspecialchars($id_alterar); ?>" required>

            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>

            <label for="cor">Cor:</label>
            <input type="text" name="cor" id="cor" value="<?php echo htmlspecialchars($cor_alterar); ?>" required>

            <label for="valor">Valor (R$):</label>
            <input type="number" name="valor" id="valor" value="<?php echo htmlspecialchars($valor_alterar); ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" step="0.01" name="peso" id="peso" value="<?php echo htmlspecialchars($peso_alterar); ?>" required>

            <button type="submit">Alterar Peça</button>
        </form>

        <!-- Excluir Peça -->
        <h2>Excluir Peça</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $id = $_POST['id'];

            try {
                $sql = "DELETE FROM pecas WHERE pec_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Peça excluída com sucesso!</div>';
                } else {
                    echo '<div class="error">Peça não encontrada.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir a peça: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="pecas.php" method="post">
            <input type="hidden" name="excluir" value="1">
            <label for="id">ID da Peça:</label>
            <input type="number" name="id" id="id" required>

            <button type="submit">Excluir Peça</button>
        </form>

        <!-- Buscar Peça -->
        <h2>Buscar Peça</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $id = $_GET['buscar'];

            try {
                $sql = "SELECT * FROM pecas WHERE pec_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $peca = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($peca) {
                    echo '<h3>Detalhes da Peça</h3>';
                    echo '<p><strong>ID:</strong> ' . htmlspecialchars($peca['pec_id']) . '</p>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($peca['pec_numero']) . '</p>';
                    echo '<p><strong>Cor:</strong> ' . htmlspecialchars($peca['pec_cor']) . '</p>';
                    echo '<p><strong>Valor:</strong> R$ ' . htmlspecialchars($peca['pec_valor']) . '</p>';
                    echo '<p><strong>Peso:</strong> ' . htmlspecialchars($peca['pec_peso']) . ' kg</p>';
                } else {
                    echo '<div class="error">Peça não encontrada.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar a peça: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="pecas.php" method="get">
            <label for="buscar_id">ID da Peça:</label>
            <input type="number" name="buscar" id="buscar_id" required>

            <button type="submit">Buscar Peça</button>
        </form>

        <!-- Listagem de Todas as Peças -->
        <h2>Listagem de Peças</h2>
        <?php
        try {
            $sql = "SELECT * FROM pecas";
            $stmt = $pdo->query($sql);
            $pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($pecas) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>ID</th><th>Número</th><th>Cor</th><th>Valor (R$)</th><th>Peso (kg)</th><th>Ações</th></tr>';
                foreach ($pecas as $peca) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($peca['pec_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($peca['pec_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($peca['pec_cor']) . '</td>';
                    echo '<td>' . htmlspecialchars($peca['pec_valor']) . '</td>';
                    echo '<td>' . htmlspecialchars($peca['pec_peso']) . '</td>';
                    echo '<td>';
                    echo '<a href="pecas.php?alterar_id=' . htmlspecialchars($peca['pec_id']) . '">Alterar</a> | ';
                    echo '<a href="pecas.php?buscar=' . htmlspecialchars($peca['pec_id']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhuma peça encontrada.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar as peças: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</body>
</html>
