<?php
$currentUser = wp_get_current_user();
$isAdmin = $currentUser->caps['administrator'];

if(!$isAdmin){
    header('Location: '. site_url());
    exit;
    die('Você não é admin');
}

$error = '';

if(isset($_POST['editDefaultText'])){
    $editDefaultText = $_POST['editDefaultText'];
    $cleanValue = strip_tags(trim($editDefaultText));
    $cleanValue = htmlspecialchars($cleanValue);
    $editDefaultText = str_replace(array("<","WHERE","where",">","=","?"), "", $cleanValue);
    
    function hasError($editDefaultText){
        $error = '';

        if(strlen($editDefaultText) > 400){
            $error = "O texto não pode conter mais de 400 caracteres.";
        }
        
        return $error;
    }

    if(!($error = hasError($editDefaultText))){
        update_option("mt_defaultText", $editDefaultText);
        header('Location: '. site_url());
    }
}
?>

<div class="container-md">
    <form id="formEditDefaultText" method="POST" action="#">
        <div class="mb-3">
            <label for="editDefaultText" class="form-label">Digite seu texto padrão</label>
            <textarea name="editDefaultText" class="form-control" id="editDefaultText" rows="6"></textarea>
            <?= $error != '' ? "<div class='invalid-feedback'>$error</div>" : '' ?>
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