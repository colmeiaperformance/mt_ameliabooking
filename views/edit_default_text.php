<?php
$currentUser = wp_get_current_user();
$isAdmin = $currentUser->caps['administrator'];

if(!$isAdmin){
    header('Location: '. site_url());
    exit;
    die('Você não é admin');
}
endelse:

if(isset($_GET['defaultText'])){
    if(urldecode($_GET['defaultText']) == 'FDadfGHKALD'){
        echo json_encode((object) ["defaultText" => get_option("mt_defaultText")]);
    }else{
        echo json_encode((object) []);
    }
} else { 
    
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
        }
    }

?>

<div class="container-md">
    <form id="formEditDefaultText" method="POST" action="#">
        <div class="mb-3">
            <label for="editDefaultText" class="form-label">Digite seu texto padrão</label>
            <textarea name="editDefaultText" class="form-control" id="editDefaultText" rows="6"><?= $error != '' ? $editDefaultText : '' ?></textarea>
            <?= $error != '' ? "<div class='invalid-feedback' style='display: block;'>$error</div>" : '' ?>
            <?= $error == '' && isset($_POST['editDefaultText']) ? "<div class='valid-feedback' style='display: block;'>Texto salvo com sucesso!</div>" : '' ?>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
    <?= $error == '' && isset($_POST['editDefaultText']) ? '<a href="'.site_url().'" class="btn btn-primary" id="page">Página inicial</a>' : '' ?>
</div>

<script>
    wp_localize_script( 'your-script-handle', 'scriptParams', $script_params );
</script>

<style>
    #formEditDefaultText button {
        background-color: #F29F05;
    }

    #formEditDefaultText button:hover {
        background-color: #FFFF;
    }

    #formEditDefaultText textarea {
        resize: none;
    }

    #page{
        background-color: #323B50 ;
       
      







    }
</style>

<?php } ?>