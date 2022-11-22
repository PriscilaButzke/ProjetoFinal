<?php
require_once("init.php");
require("bd-methods.php");
// painel do adm com a tabela e logout
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Teste adm</title>
</head>

<body>
    <h1>PAINEL DO ADM</h1>
    <form action="?act=save" method="POST">
        <input type="text" name="id" <?php
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
        <input type="text" placeholder="Nome" name="nome" <?php
       
               if (isset($nome) && $nome != null || $nome != "") {
                   echo "value=\"{$nome}\"";
               }
               ?> />
               <?php var_dump($nome);?>
        <input type="text" placeholder="Email" name="email" <?php
               if (isset($email) && $email != null || $email != "") {
                   echo "value=\"{$email}\"";
               }
    ?> />
        <input type="date" placeholder="Data de nascimento" name="datanascimento" <?php
               if (isset($datanascimento) && $datanascimento != null || $datanascimento != "") {
                   echo "value=\"{$datanascimento}\"";
               }
               ?> />

        <label for="ativo">Ativo</label>
        <input type="radio" name="ativo" id="ativo" value="Ativo" <?php
               if (isset($ativo) && $ativo != null || $ativo != "") {
                   echo "value=\"{$ativo}\"";
               }
               ?>>

        <label for="inativo">Inativo</label>
        <input type="radio" name="ativo" id="inativo" value="Inativo" <?php
               if (isset($ativo) && $ativo != null || $ativo != "") {
                   echo "value=\"{$ativo}\"";
               }
               ?>>

        <input type="submit" name="salvar" value="Salvar"/>
        <hr>
    </form>
    <table width="100%">
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Data de nascimento</th>
            <th>Ativo</th>
        </tr>
        <?php
                try {
                    $stmt = db_connect()->prepare("SELECT * FROM usuarios");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->id."</td>".
                            "<td>".$rs->nome."</td>
                            <td>".$rs->email."</td>
                            <td>".$rs->datanascimento."</td>
                            <td>".$rs->ativo."</td>
                            <td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
    </table>

    <?php
    // Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = db_connect()->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $email = $rs->email;
            $senha = $rs->senha;
            $datanascimento = $rs->datanascimento;
            $ativo = $rs->ativo;
            echo "<input type='text' placeholder='Nome' name='nome' value='{$nome}'>";
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

if(isset($_POST['salvar'])){
    try {
        if ($id != "") {
            $stmt = db_connect()->prepare("UPDATE usuarios SET nome=?, email=?, datanascimento=?, ativo=? WHERE id = ?");
            $stmt->bindParam(1, $nome);
            echo $nome;
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $datanascimento);
            $stmt->bindParam(4, $ativo);
            $stmt->bindParam(5, $id);

            if ($stmt->execute()) {
                echo "Dados editados com sucesso!";
                $id = null;
                $nome = null;
                $email = null;
                $senha = null;
                $datanascimento = null;
                $ativo = false;
            } else {
                throw new PDOException("Erro: Não foi possível executar a declaração sql");
            }
        } else {
            echo "Id não identificado";
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>


    <a href="form-login.php">Voltar pro formulário de login</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
</body>

</html>