<!-- projeto.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Projetos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Projetos</h1>
    </header>
    <nav>
        <a href="index.php">Página Inicial</a>
    </nav>
    <div class="container">
        <!-- Incluir Projeto -->
        <h2>Incluir Projeto</h2>
        <?php
        require 'conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $orcamento = $_POST['orcamento'];
            $descricao = $_POST['descricao'];

            try {
                $sql = "INSERT INTO projeto (pro_numero, pro_orcamento, pro_descricao) VALUES (:numero, :orcamento, :descricao)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':orcamento' => $orcamento,
                    ':descricao' => $descricao
                ]);
                echo '<div class="alert">Projeto incluído com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir o projeto: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="projeto.php" method="post">
            <input type="hidden" name="incluir" value="1">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" required>

            <label for="orcamento">Orçamento (R$):</label>
            <input type="number" name="orcamento" id="orcamento" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" rows="4" required></textarea>

            <button type="submit">Incluir Projeto</button>
        </form>

        <!-- Alterar Projeto -->
        <h2>Alterar Projeto</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $numero = $_POST['numero'];
            $orcamento = $_POST['orcamento'];
            $descricao = $_POST['descricao'];

            try {
                $sql = "UPDATE projeto SET pro_numero = :numero, pro_orcamento = :orcamento, pro_descricao = :descricao WHERE pro_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':orcamento' => $orcamento,
                    ':descricao' => $descricao,
                    ':id' => $id
                ]);
                echo '<div class="alert">Projeto alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o projeto: ' . $e->getMessage() . '</div>';
            }
        }

        // Buscar Projeto para preencher o formulário de alteração
        $id_alterar = '';
        $numero_alterar = '';
        $orcamento_alterar = '';
        $descricao_alterar = '';

        if (isset($_GET['alterar_id'])) {
            $id_alterar = $_GET['alterar_id'];
            try {
                $sql = "SELECT * FROM projeto WHERE pro_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id_alterar]);
                $projeto = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($projeto) {
                    $numero_alterar = $projeto['pro_numero'];
                    $orcamento_alterar = $projeto['pro_orcamento'];
                    $descricao_alterar = $projeto['pro_descricao'];
                } else {
                    echo '<div class="error">Projeto não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o projeto: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="projeto.php" method="post">
            <input type="hidden" name="alterar" value="1">
            <label for="id">ID do Projeto:</label>
            <input type="number" name="id" id="id" value="<?php echo htmlspecialchars($id_alterar); ?>" required>

            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>

            <label for="orcamento">Orçamento (R$):</label>
            <input type="number" name="orcamento" id="orcamento" value="<?php echo htmlspecialchars($orcamento_alterar); ?>" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" rows="4" required><?php echo htmlspecialchars($descricao_alterar); ?></textarea>

            <button type="submit">Alterar Projeto</button>
        </form>

        <!-- Excluir Projeto -->
        <h2>Excluir Projeto</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $id = $_POST['id'];

            try {
                $sql = "DELETE FROM projeto WHERE pro_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Projeto excluído com sucesso!</div>';
                } else {
                    echo '<div class="error">Projeto não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir o projeto: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="projeto.php" method="post">
            <input type="hidden" name="excluir" value="1">
            <label for="id">ID do Projeto:</label>
            <input type="number" name="id" id="id" required>

            <button type="submit">Excluir Projeto</button>
        </form>

        <!-- Buscar Projeto -->
        <h2>Buscar Projeto</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $id = $_GET['buscar'];

            try {
                $sql = "SELECT * FROM projeto WHERE pro_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $projeto = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($projeto) {
                    echo '<h3>Detalhes do Projeto</h3>';
                    echo '<p><strong>ID:</strong> ' . htmlspecialchars($projeto['pro_id']) . '</p>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($projeto['pro_numero']) . '</p>';
                    echo '<p><strong>Orçamento:</strong> R$ ' . htmlspecialchars($projeto['pro_orcamento']) . '</p>';
                    echo '<p><strong>Descrição:</strong> ' . htmlspecialchars($projeto['pro_descricao']) . '</p>';
                } else {
                    echo '<div class="error">Projeto não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o projeto: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="projeto.php" method="get">
            <label for="buscar_id">ID do Projeto:</label>
            <input type="number" name="buscar" id="buscar_id" required>

            <button type="submit">Buscar Projeto</button>
        </form>

        <!-- Listagem de Todos os Projetos -->
        <h2>Listagem de Projetos</h2>
        <?php
        try {
            $sql = "SELECT * FROM projeto";
            $stmt = $pdo->query($sql);
            $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($projetos) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>ID</th><th>Número</th><th>Orçamento (R$)</th><th>Descrição</th><th>Ações</th></tr>';
                foreach ($projetos as $projeto) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($projeto['pro_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($projeto['pro_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($projeto['pro_orcamento']) . '</td>';
                    echo '<td>' . htmlspecialchars($projeto['pro_descricao']) . '</td>';
                    echo '<td>';
                    echo '<a href="projeto.php?alterar_id=' . htmlspecialchars($projeto['pro_id']) . '">Alterar</a> | ';
                    echo '<a href="projeto.php?buscar=' . htmlspecialchars($projeto['pro_id']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum projeto encontrado.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar os projetos: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</body>
</html>
