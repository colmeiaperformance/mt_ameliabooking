<?php 
   $all_users = get_users();
   $userInfos = [];
   foreach($all_users  as $user){
    $customer = new WC_Customer( $user->ID );
    $desc = get_user_meta($user->ID)['description'][0];
    $userInfos[] = [
        'email' => $user->data->user_email,
        'id' => $user->ID,
        'addressLine' => $customer->get_billing_address_1(),
        'otherPlaces' => $desc ? explode(';', $desc) : []
    ];
   }

?>

<div id="mt_employee_container">



</div>

<div id="mt_loader_overlay" style="position:fixed">
    <div class="lds-grid" style="top:50% !important"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div>


<div id="mt_message_overlay_success" style="max-width:350px">
    <h2> Obrigado!</h2>
    <h3> 
        Sua mensagem foi enviada!
    </h3>
    <center>
        <button class="mt_btn_default" onclick="closeModal()"> Ok </button>
    </center>
</div>


<script>
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };

    const id = getUrlParameter('id'); //Get id from url.
    const ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    console.log(ajaxurl);
    const baseurl = '<?php echo get_template_directory_uri(); ?>';
    let $ = document.querySelector.bind(document);
    let wp_user_infos = <?php echo json_encode($userInfos) ?>;
    let controller = new EmployeeController(ajaxurl, baseurl, $("#mt_employee_container"));
    let employee = new Employee()
    
    getEmployee = async(id) => {
       
       return await employee.find(id,ajaxurl, wp_user_infos)
    } 

    render = async() =>{
        jQuery("#mt_loader_overlay").fadeIn();
        employee = await getEmployee(id);
        controller.render(employee);
        jQuery('#contactPhone').mask('(00) 00000-0000');
        jQuery("#mt_loader_overlay").fadeOut();
    }

    sendContactForm = async(event,form) => {
        jQuery("#mt_loader_overlay").fadeIn();
        event.preventDefault();

        alert("cheguei");

        if(formIsValid(jQuery("#contactEmail"), jQuery("#contactName"), jQuery("#contactPhone"), jQuery("#contactMessage"))){
            console.log("Dentro do if");
            const url = `${ajaxurl}?action=event_form`;
            let formData = new FormData();
            formData.append('email',jQuery("#contactEmail").val())
            formData.append('name',jQuery("#contactName").val())
            formData.append('phone',jQuery("#contactPhone").val())
            formData.append('instrutor',employee.firstName+' '+ employee.lastName)
            formData.append('message',jQuery("#contactMessage").val())

            let contactReq = await axios.post(`${url}`,formData,{
                headers: { 
                    "Content-Type": "application/x-www-form-urlencoded"
                }
            });
            if(contactReq.status === 200){
                jQuery("#mt_message_overlay_success").fadeIn();
            }
        }

        console.log("final");


        jQuery("#mt_loader_overlay").fadeOut();
    }

    function formIsValid(email, name, phone, message){
        let valid = true;

        if(email.val() == ""){
            valid = false;
            showHideError(true, true, email, 'Este campo é necessário.');
        }else if(!email.val().match(/^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)){
            valid = false;
            showHideError(true, true, email, 'Digite um e-mail válido.');
        }else{
            showHideError(true, false, email, 'Este campo está válido.');
        }

        if(name.val() == ""){
            valid = false;
            showHideError(true, true, name, 'Este campo é necessário.');
        }else{
            showHideError(true, false, name, 'Este campo está válido.');
        }

        if(phone.val() == ""){
            valid = false;
            showHideError(true, true, phone, 'Este campo é necessário.');
        }else{
            showHideError(true, false, phone, 'Este campo está válido.');
        }

        if(message.val() == ""){
            valid = false;
            showHideError(true, true, message, 'Este campo é necessário.');
        }else{
            showHideError(true, false, message, 'Este campo está válido.');
        }

        showHideError(false, true, email, '');

        return valid;
    }

    function showHideError(show = true, error = true, elem, text = ''){
        console.log("dentro do show error");

        if(show){
            console.log("mostrar");
            if(error){
                console.log("erro");
                createElementAlert(elem, text, true);
            }else{
                console.log("sem erro");
                createElementAlert(elem, text, false);
            }
        }else{
            console.log(elem);
            console.log(elem.parent().children(".invalid-feedback"));
            elem.parent().remove(".invalid-feedback");
            elem.parent().remove(".valid-feedback");
        }
    }

    function createElementAlert(elem, text = '', error = true){
        let div = document.createElement('div');
        error ? div.classList.add('invalid-feedback') : div.classList.add('valid-feedback');
        div.style.display = 'block';
        div.innerText = text;
        elem.parent().append(div);
    }

    function closeModal(){
        jQuery("#mt_message_overlay_success").fadeOut();
        jQuery("#mt_message_overlay_error").fadeOut();
    }

    render();
</script>