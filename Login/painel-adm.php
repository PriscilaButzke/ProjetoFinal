<?php
require_once("init.php");
require("bd-methods.php");
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Teste adm</title>
</head>

<header>
    <nav class="navbar navbar-expand-lg " id="menu">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="nav justify-content-center">

                    <li class="li-logo">
                        <img id="logo">LOGO AQUI</img>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="form-cadastro-pri.php">Cadastrar</a>
                    </li>

                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


<body>
    <h1 class="d-flex justify-content-center" id="tituloAgenda">Solicitações de novos usuários</h1>
    <hr>
    <table class="table table-striped table-hover" width="70% ">
        <tr class="table">
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
                    echo "<tr>
                        <td>" . $rs->id . "</td>" .
                        "<td>" . $rs->nome . "</td>
                        <td>" . $rs->email . "</td>
                        <td>" . $rs->datanascimento . "</td>
                        <td>" . $rs->ativo . "</td>
                        <td>
                        <center>
                        <a href=\"?act=upd&id=" . $rs->id . "\">[Alterar]</a>"
                        . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                        . "<a href=\"?act=del&id=" . $rs->id . "\">[Excluir]</a>
                        </center>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "Erro: Não foi possível recuperar os dados do banco de dados";
            }
        } catch (PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
        ?>
    </table>

    <?php
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


            echo "<form class='row g-0' action='#' method='POST' name='form1'>
            <div class='d-flex justify-content-start ms-5'>
    
            <input class='form-control' style='width:300px ;' type='hidden' name='id' value='{$id}' />
                <div class='mb-3 row'>
                    <label for='inputEmail4' class='form-label'>Nome</label>
                    <input class='form-control' style='width:300px ;' type='text' name='nome' value='{$nome}' />
                </div>
    
                <div class='mb-3 row'>
                    <label for='inputEmail4' class='form-label'>Email</label>
                    <input class='form-control' style='width:300px ;' type='text' name='email' value='{$email}'/>
    
                </div>
                <div class='mb-3 row'>
                    <label for='inputEmail4' class='form-label'>Data de nascimento</label>
                    <input class='form-control' style='width:300px ;' type='date' name='datanascimento' value='{$datanascimento}'/>
                    </div>";

            if ($ativo == 1) {
                echo "
                        <label for='ativo'>Ativo</label>
                        <input type='radio' name='radio' id='ativo' value='1' checked='checked'>
                        <label for='inativo'>Inativo</label>
                        <input type='radio' name='radio' id='inativo' value='0'>";
            } else {
                echo "
                        <label for='ativo'>Ativo</label>
                        <input type='radio' name='radio' id='ativo' value='1'>
                        <label for='inativo'>Inativo</label>
                        <input type='radio' name='radio' id='inativo' value='0' checked='checked'>";
            }


            echo "<div class=' mt-4' id='div-botoes-editar'>";
            echo "<input type='submit' name='salvar' value='Salvar' class='btn'/>" .
                "</div>" .
                "</div>" .
                "</form>";

        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}





if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = db_connect()->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;

        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}


if (isset($_POST['salvar'])) {
    try {
        if ($id != "") {
            $stmt = db_connect()->prepare("UPDATE usuarios SET nome=?, email=?, datanascimento=?, ativo=? WHERE id = ?");
            $stmt->bindParam(1, $nome);
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
        echo "Erro: " . $erro->getMessage();
    }
}
?>
    <a href="form-login.php">Voltar pro formulário de login</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
        </script>
</body>

</html>