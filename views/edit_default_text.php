<?php
$currentUser = wp_get_current_user();
$isAdmin = $currentUser->caps['administrator'];

if(!$isAdmin){
    header('Location: '. site_url());
    exit;
    die('Você não é admin');
}

if(isset($_POST['editDefaultText'])){
    $editDefaultText = $_POST['editDefaultText'];

    // $conn = false;

    // try {
    //     $conn = new PDO('sqlite:DB/db.sqlite3');
    //     return $conn;
    // } catch(PDOException $e) {
    //     // header('Location: '. site_url());
    //     // exit;
    //     // die('Erro ao conectar ao banco de dados');

    //     echo 'ERROR: ' . $e->getMessage();
    // }

    // return $conn;

    // var_dump($conn);

    echo realpath('./DB/db.sqlite3');

    echo "Está setado: ";
    echo $editDefaultText;
}
?>

<div class="container-md">
    <form id="formEditDefaultText" method="POST" action="#">
        <div class="mb-3">
            <label for="editDefaultText" class="form-label">Digite seu texto padrão</label>
            <textarea name="editDefaultText" class="form-control" id="editDefaultText" rows="6"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<style>
    #formEditDefaultText button {
        background-color: #F29F05;
    }

    #formEditDefaultText button:hover {
        background-color: #FFC536;
    }

    #formEditDefaultText textarea {
        resize: none;
    }
</style>