<?php
$currentUser = wp_get_current_user();
$isAdmin = $currentUser->caps['administrator'];

if(!$isAdmin){
    header('Location: '. site_url());
    exit;
    die('Você não é admin');
}

$conn = false;

try {
    $conn = new PDO('sqlite:db.sqlite3');
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    header('Location: '. site_url());
    exit;
    die('Erro ao conectar ao banco de dados');
}

if(isset($_POST['editDefaultText'])){
    $editDefaultText = $_POST['editDefaultText'];

    
    $sql = "INSERT INTO defaultText (id, defaultText) VALUES (1, :defaultText)";
    $stm = $conn->prepare($sql);
    $stm->bindParam(':defaultText', $editDefaultText, PDO::PARAM_STR);
    if($stm->execute()){
        echo "deu certo";
    }else{
        echo "não deu";
    }
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