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

    @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap');

    #formEditDefaultText button {
      
        
        transition: 0.8s !important;
        
        color: #FFFFFF !important;
        padding: 9px 31px !important;
        background: #F29F05 !important;
        border: 1px solid #F29F05 !important;
        border-radius: 30px !important;
       
        font-weight: 700 !important;
        
        
    }

    #formEditDefaultText button:hover {
        background: #FFFF !important;
        color: #F29F05 !important;
        
    }

    #formEditDefaultText textarea {
        resize: none;
    }

    #page{
        font-weight: 700 !important;
        color: #FFFFFF !important;
        padding: 9px 31px !important;
        background: #323B50 !important;
        border: 1px solid #323B50 !important;
        border-radius: 30px !important;
        left:90px !important;
        top: -75px !important;
        transition: 0.8s !important;
}
        
        
        
}

#page:hover{
    background: #FFFF !important;
        color:  #323B50 !important;
        border: 1px solid #323B50 !important;

}


       
      







    }
</style>

<?php } ?>