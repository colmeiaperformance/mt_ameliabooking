<?php

$currentUser = wp_get_current_user();



echo "User caps";

var_dump($currentUser->caps['administrator']);



echo "____________________";



if(isset($_POST['editDefaultText'])){
    $editDefaultText = $_POST['editDefaultText'];

    echo "Está setado: ";
    echo $editDefaultText;
}

echo "_________________________"; 

var_dump(wp_get_current_user()); // funcionou

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