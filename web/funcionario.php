<!-- funcionario.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Funcionários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Funcionários</h1>
    </header>
    <nav>
        <a href="index.php">Página Inicial</a>
    </nav>
    <div class="container">
        <!-- Incluir Funcionário -->
        <h2>Incluir Funcionário</h2>
        <?php
        require 'conexao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $salario = $_POST['salario'];
            $telefone = $_POST['telefone'];
            $nome = $_POST['nome'];
            $dep_id = $_POST['dep_id'];

            try {
                $sql = "INSERT INTO funcionario (fun_numero, fun_salario, fun_telefone, fun_nome, dep_id) 
                        VALUES (:numero, :salario, :telefone, :nome, :dep_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':salario' => $salario,
                    ':telefone' => $telefone,
                    ':nome' => $nome,
                    ':dep_id' => $dep_id
                ]);
                echo '<div class="alert">Funcionário incluído com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir o funcionário: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="funcionario.php" method="post">
            <input type="hidden" name="incluir" value="1">
            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" required>

            <label for="salario">Salário (R$):</label>
            <input type="number" name="salario" id="salario" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" maxlength="12" placeholder="Ex: 99999-9999" required>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="dep_id">Departamento:</label>
            <select name="dep_id" id="dep_id" required>
                <option value="">Selecione</option>
                <?php
                // Buscar todos os departamentos para preencher o select
                try {
                    $sql = "SELECT dep_id, dep_numero FROM departamento";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($row['dep_id']) . '">' . htmlspecialchars($row['dep_numero']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Erro ao carregar departamentos</option>';
                }
                ?>
            </select>

            <button type="submit">Incluir Funcionário</button>
        </form>

        <!-- Alterar Funcionário -->
        <h2>Alterar Funcionário</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $id = $_POST['id'];
            $numero = $_POST['numero'];
            $salario = $_POST['salario'];
            $telefone = $_POST['telefone'];
            $nome = $_POST['nome'];
            $dep_id = $_POST['dep_id'];

            try {
                $sql = "UPDATE funcionario 
                        SET fun_numero = :numero, fun_salario = :salario, fun_telefone = :telefone, 
                            fun_nome = :nome, dep_id = :dep_id 
                        WHERE fun_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':salario' => $salario,
                    ':telefone' => $telefone,
                    ':nome' => $nome,
                    ':dep_id' => $dep_id,
                    ':id' => $id
                ]);
                echo '<div class="alert">Funcionário alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o funcionário: ' . $e->getMessage() . '</div>';
            }
        }

        // Buscar Funcionário para preencher o formulário de alteração
        $id_alterar = '';
        $numero_alterar = '';
        $salario_alterar = '';
        $telefone_alterar = '';
        $nome_alterar = '';
        $dep_id_alterar = '';

        if (isset($_GET['alterar_id'])) {
            $id_alterar = $_GET['alterar_id'];
            try {
                $sql = "SELECT * FROM funcionario WHERE fun_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id_alterar]);
                $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($funcionario) {
                    $numero_alterar = $funcionario['fun_numero'];
                    $salario_alterar = $funcionario['fun_salario'];
                    $telefone_alterar = $funcionario['fun_telefone'];
                    $nome_alterar = $funcionario['fun_nome'];
                    $dep_id_alterar = $funcionario['dep_id'];
                } else {
                    echo '<div class="error">Funcionário não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o funcionário: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="funcionario.php" method="post">
            <input type="hidden" name="alterar" value="1">
            <label for="id">ID do Funcionário:</label>
            <input type="number" name="id" id="id" value="<?php echo htmlspecialchars($id_alterar); ?>" required>

            <label for="numero">Número:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>

            <label for="salario">Salário (R$):</label>
            <input type="number" name="salario" id="salario" value="<?php echo htmlspecialchars($salario_alterar); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" maxlength="12" placeholder="Ex: 99999-9999" value="<?php echo htmlspecialchars($telefone_alterar); ?>" required>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($nome_alterar); ?>" required>

            <label for="dep_id">Departamento:</label>
            <select name="dep_id" id="dep_id" required>
                <option value="">Selecione</option>
                <?php
                // Buscar todos os departamentos para preencher o select
                try {
                    $sql = "SELECT dep_id, dep_numero FROM departamento";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($dep_id_alterar == $row['dep_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['dep_id']) . '" ' . $selected . '>' . htmlspecialchars($row['dep_numero']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Erro ao carregar departamentos</option>';
                }
                ?>
            </select>

            <button type="submit">Alterar Funcionário</button>
        </form>

        <!-- Excluir Funcionário -->
        <h2>Excluir Funcionário</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $id = $_POST['id'];

            try {
                $sql = "DELETE FROM funcionario WHERE fun_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Funcionário excluído com sucesso!</div>';
                } else {
                    echo '<div class="error">Funcionário não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir o funcionário: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="funcionario.php" method="post">
            <input type="hidden" name="excluir" value="1">
            <label for="id">ID do Funcionário:</label>
            <input type="number" name="id" id="id" required>

            <button type="submit">Excluir Funcionário</button>
        </form>

        <!-- Buscar Funcionário -->
        <h2>Buscar Funcionário</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $id = $_GET['buscar'];

            try {
                $sql = "SELECT f.fun_id, f.fun_numero, f.fun_salario, f.fun_telefone, f.fun_nome, d.dep_numero 
                        FROM funcionario f 
                        JOIN departamento d ON f.dep_id = d.dep_id 
                        WHERE f.fun_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($funcionario) {
                    echo '<h3>Detalhes do Funcionário</h3>';
                    echo '<p><strong>ID:</strong> ' . htmlspecialchars($funcionario['fun_id']) . '</p>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($funcionario['fun_numero']) . '</p>';
                    echo '<p><strong>Salário:</strong> R$ ' . htmlspecialchars($funcionario['fun_salario']) . '</p>';
                    echo '<p><strong>Telefone:</strong> ' . htmlspecialchars($funcionario['fun_telefone']) . '</p>';
                    echo '<p><strong>Nome:</strong> ' . htmlspecialchars($funcionario['fun_nome']) . '</p>';
                    echo '<p><strong>Departamento Número:</strong> ' . htmlspecialchars($funcionario['dep_numero']) . '</p>';
                } else {
                    echo '<div class="error">Funcionário não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o funcionário: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        <form action="funcionario.php" method="get">
            <label for="buscar_id">ID do Funcionário:</label>
            <input type="number" name="buscar" id="buscar_id" required>

            <button type="submit">Buscar Funcionário</button>
        </form>

        <!-- Listagem de Todos os Funcionários -->
        <h2>Listagem de Funcionários</h2>
        <?php
        try {
            $sql = "SELECT f.fun_id, f.fun_numero, f.fun_salario, f.fun_telefone, f.fun_nome, d.dep_numero 
                    FROM funcionario f 
                    JOIN departamento d ON f.dep_id = d.dep_id";
            $stmt = $pdo->query($sql);
            $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($funcionarios) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>ID</th><th>Número</th><th>Salário (R$)</th><th>Telefone</th><th>Nome</th><th>Departamento Número</th><th>Ações</th></tr>';
                foreach ($funcionarios as $funcionario) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($funcionario['fun_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($funcionario['fun_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($funcionario['fun_salario']) . '</td>';
                    echo '<td>' . htmlspecialchars($funcionario['fun_telefone']) . '</td>';
                    echo '<td>' . htmlspecialchars($funcionario['fun_nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($funcionario['dep_numero']) . '</td>';
                    echo '<td>';
                    echo '<a href="funcionario.php?alterar_id=' . htmlspecialchars($funcionario['fun_id']) . '">Alterar</a> | ';
                    echo '<a href="funcionario.php?buscar=' . htmlspecialchars($funcionario['fun_id']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum funcionário encontrado.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar os funcionários: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</body>
</html>
