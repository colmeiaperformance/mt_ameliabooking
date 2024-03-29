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
    <!-- <div class="instructorEvents">
        <div>Envent Item</div>
    </div> -->
</div>

<div id="mt_loader_overlay" style="position:fixed">
    <div class="lds-grid" style="top:50% !important"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div>

<div id="mt_message_overlay_error">
    <h2> Ops...</h2>
    <h3>Não foi possível realizar sua inscrição, verifique os dados informados e tente novamente.</h3>
    <button class="mt_btn_default" onclick="closeModal()"> Ok </button>
</div>

<div id="mt_message_overlay_success">
    <h2> Obrigada!</h2>
    <h3> 
        Sua inscrição foi realizada com sucesso! <br/>
        Você receberá as informações do evento no seu email.
    </h3>
    <button class="mt_btn_default" onclick="closeModal()"> Ok </button>
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

<style>
    #instructorEventsSection.hide {
        display: none;
    }

    .containerButtons {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-basis: 50% !important;
    }

    .containerButtons .div-button-whatsapp img {
        margin-right: 5px;
    }

    .containerButtons .div-button-whatsapp .btn-whatsapp {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .containerButtons .btn-whatsapp,
    .containerButtons .btn-events {
        width: 100% !important;
        min-height: 40px !important;
    }

    .containerButtons > * {
        height: unset !important;
        min-width: 310px !important;
    }

    @media screen and (max-width: 500px){
        .containerButtons .btn-whatsapp,
        .containerButtons .btn-events {
            font-size: 0.95em;
        }

        .containerButtons .div-button-whatsapp img {
            height: 1.10em;
        }

        .containerButtons > * {
            min-width: 257px !important;
        }
    }  
    #eventsContainer .phone .input-group.mt_warning img {
        margin-bottom: 18px !important;        
    }
</style>

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
    const baseurl = '<?php echo get_template_directory_uri(); ?>';
    const urlbase = '<?php echo plugin_dir_url( __FILE__ ); ?>';
    const urlRedirectInstructorsPage = '<?php echo home_url() . '/instrutores'; ?>';
    let $ = document.querySelector.bind(document);
    let wp_user_infos = <?php echo json_encode($userInfos) ?>;
    let controller = new EmployeeController(ajaxurl, baseurl, $("#mt_employee_container"));
    let employee = new Employee()

    const eventsController = new EventsController(ajaxurl, urlbase, jQuery("#eventsContainer"));
    
    let firstName = "";
    let lastName = "";
    let email = "";
    let phone = "";
    let checkBox = [];
    
    let nomeDaPalestra = "";
    let cidadeDaPalestra = "";
    
    let eventList = [];
    
    getEmployee = async(id) => {
       
       return await employee.find(id,ajaxurl, wp_user_infos)
    } 

    render = async() =>{
        jQuery("#mt_loader_overlay").fadeIn();
        employee = await getEmployee(id);
        controller.render(employee);

        let eventList = await eventsController.list();
        events = eventList.filter((e) => {
            return e._organizerId == id;
        });

        let employeeEvents = new EmployeeEvents(jQuery("#eventsContainer"), events, urlbase);
        employeeEvents.renderView();
        jQuery('.phoneMask').mask(phoneBehavior, spOptions);

        jQuery('#contactPhoneMsg').mask(phoneBehavior, spOptions);
        jQuery('#contactPhoneEmplEvent').mask(phoneBehavior, spOptions);
        jQuery("#mt_loader_overlay").fadeOut();
    }

    sendContactForm = async(event,form) => {
        jQuery("#mt_loader_overlay").fadeIn();
        event.preventDefault();

        jQuery("#contactEmail, #melhorDia, #melhorPeriodo, #contactName, #contactPhoneMsg, #contactMessage").on('input', function() {
            formIsValid(jQuery("#contactEmail"), jQuery("#melhorDia"), jQuery("#melhorPeriodo"), jQuery("#contactName"), jQuery("#contactPhoneMsg"), jQuery("#contactMessage"));
        });

        if(formIsValid(jQuery("#contactEmail"), jQuery("#melhorDia"), jQuery("#melhorPeriodo"), jQuery("#contactName"), jQuery("#contactPhoneMsg"), jQuery("#contactMessage"))){
            const url = `${ajaxurl}?action=event_form`;
            let formData = new FormData();
            formData.append('email',jQuery("#contactEmail").val())
            formData.append('melhorDia',jQuery("#melhorDia").val())
            formData.append('melhorPeriodo',jQuery("#melhorPeriodo").val())
            formData.append('aceite',jQuery("#contactAceite").val())
            formData.append('name',jQuery("#contactName").val())
            formData.append('phone',jQuery("#contactPhoneMsg").val())
            formData.append('instrutor',employee.firstName+' '+ employee.lastName)
            formData.append('message',jQuery("#contactMessage").val())

            let contactReq = await axios.post(`${url}`,formData,{
                headers: { 
                    "Content-Type": "application/x-www-form-urlencoded"
                }
            });
            if(contactReq.status === 200){
                jQuery("#mt_message_overlay_success").fadeIn();
                setTimeout(function(){ window.location.replace(`${urlRedirectInstructorsPage}`); }, 1500);
            }
        }

        jQuery("#mt_loader_overlay").fadeOut();
    }

    function formIsValid(email, melhorDia, melhorPeriodo, name, phone, message){
        let valid = true;

        if(email.val() == ""){
            valid = false;
            showHideError(email, true, true, 'Digite um e-mail válido.');
        }else if(!email.val().match(/^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)){
            valid = false;
            showHideError(email, true, true, 'Digite um e-mail válido.');
        }else{
            showHideError(email, true, false, 'Este campo está válido.');
        }

        if(name.val() == ""){
            valid = false;
            showHideError(name, true, true, 'Insira seu nome completo.');
        }else{
            showHideError(name, true, false, 'Este campo está válido.');
        }

        if(melhorDia.val() == ""){
            valid = false;
            showHideError(melhorDia, true, true, 'Selecione uma opção.');
        }else{
            showHideError(melhorDia, true, false, 'Este campo está válido.');
        }

        if(melhorPeriodo.val() == ""){
            valid = false;
            showHideError(melhorPeriodo, true, true, 'Selecione uma opção.');
        }else{
            showHideError(melhorPeriodo, true, false, 'Este campo está válido.');
        }
        
        phoneValueMsg = document.getElementById("contactPhoneMsg").value;
        if(phone.val() == "" || phone.val().length < 14 ){
            valid = false;
            showHideError(phone, true, true, 'Digite seu DDD + telefone ou celular.');
            console.log("Employee 239");
        }else{
            showHideError(phone, true, false, 'Este campo está válido.');
        }

        if(message.val() == ""){
            valid = false;
            showHideError(message, true, true, 'Escreva uma mensagem.');
        }else{
            showHideError(message, true, false, 'Este campo está válido.');
        }

        return valid;
    }

    function showHideError(elem, show = true, error = true, text = ''){
        if(show){
            if(error){
                createElementAlert(elem, text, true);
            }else{
                createElementAlert(elem, text, false);
            }
        }else{
            elem.parent().children(".invalid-feedback").remove();
            elem.parent().children(".valid-feedback").remove();
        }
    }

    function createElementAlert(elem, text = '', error = true){
        let div = document.createElement('div');
        error ? div.classList.add('invalid-feedback') : div.classList.add('valid-feedback');
        div.style.display = 'block';
        div.classList.add('mb-2');
        div.innerText = text;
        showHideError(elem, false);
        elem.parent().append(div);
    }

    function closeModal(){
        jQuery("#mt_message_overlay_success").fadeOut();
        jQuery("#mt_message_overlay_error").fadeOut();
    }

    const toggleSubmission = (key) => {
        let $ = jQuery;
        let element = $(`#mt_event_details_subscriptions_${key}`);
        
        if(!element.hasClass('oppened')){
            element.addClass('oppened');
            $(`#mt_event_details_${key}`).addClass('oppened');
            $(`#subscription_${key}`).css('display', 'block');
            $(".mt_event_details_container").css('display', 'block');
        }else{
            element.removeClass('oppened');
            $(`#mt_event_details_${key}`).removeClass('oppened');
            $(".mt_event_details_container").css('display', 'none');
            $(`#subscription_${key}`).css('display', 'none');
        }  
    }

    const toggleEvents = async() => {
        let eventsSection = document.getElementById('instructorEventsSection');
        eventsSection.classList.toggle('hide');
    }

    async function bookingEvent(eventId){
        let formQuery = jQuery(`#formEvt${eventId}`);
        let form = document.getElementById(`formEvt${eventId}`);
        let formData = new FormData(form);
        firstName =  formData.getAll('firstName')[0];
        lastName =  formData.getAll('lastName')[0];
        email =  formData.getAll('email')[0];
        phone =  formData.getAll('phone')[0];

        nomeDaPalestra =  formData.getAll('nomeDaPalestra')[0];
        cidadeDaPalestra =  formData.getAll('cidadeDaPalestra')[0];

        let array = [formQuery.find(".firstName"), formQuery.find(".lastName"), formQuery.find(".email"), formQuery.find(".phoneInpt")];

        form.addEventListener("submit", event => {
            event.preventDefault();
        });
        
        if(!validateForm(array[0], array[1], array[2], array[3])){
            jQuery("#mt_message_overlay_error").fadeIn();
            jQuery("#mt_message_overlay_error").css('display', 'flex');

            array.forEach((element) => {
                jQuery(element).on('input', function() {
                    validateForm(array[0], array[1], array[2], array[3]);
                });
            });
        } else {
            jQuery("#mt_loader_overlay").fadeIn();
            let eventList = await eventsController.list();
            let bkEvent = eventList.filter(e => e.id == eventId);
            bkEvent = bkEvent[0];
            
            let booking = await eventsController.booking(bkEvent,email, firstName, lastName, phone, ajaxurl);
            if(booking){
                jQuery("#mt_message_overlay_success").fadeIn();
                jQuery("#mt_message_overlay_success").css('display', 'flex');
                jQuery("#mt_loader_overlay").fadeOut();
                
                let filteredCheckOptions = checkBox.filter(c => c ? c : false);

                //Connecting to Active Campaing
                const url = `${ajaxurl}?action=event_subscription`;
                let formData = new FormData();
                formData.append('email',email)
                formData.append('firstName',firstName)
                formData.append('lastName',lastName)
                formData.append('phone',phone)
                formData.append('oqueTrouxe',filteredCheckOptions.join(', '));
                
                formData.append('nomeDaPalestra',nomeDaPalestra)
                formData.append('cidadeDaPalestra',cidadeDaPalestra)
                
                let start = moment(bkEvent.periods[0].periodStart).subtract(3, 'hours').format('DD-MM-YYYY');
                let hour = moment(bkEvent.periods[0].periodStart).subtract(3, 'hours').format('HH:mm');
                let hourDate = moment(bkEvent.periods[0].periodStart).format('DD-MM-YYYY HH:mm');

                let momentPeriod = moment(bkEvent.periods[0].periodStart);

                let dataHoraText = `${momentPeriod.format('DD')}/${momentPeriod.format('MM')}/${momentPeriod.format('YYYY')}`

                formData.append('dataPalestra', `${start}`);
                formData.append('horaPalestra',  `${hour}`);
                formData.append('dataHoraPalestra',  `${hourDate}`);
                formData.append('dataHoraText',  `${dataHoraText}`);
            
                formData.append('instrutor',bkEvent.organizer?.firstName+' '+ bkEvent.organizer?.lastName)
                formData.append('message',jQuery("#contactMessage").val())

                let contactReq = await axios.post(`${url}`,formData,{
                    headers: { 
                        "Content-Type": "application/x-www-form-urlencoded"
                    }
                });
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            }else{
                validateForm(array[0], array[1], array[2], array[3]);
                jQuery("#mt_message_overlay_error").fadeIn();
                jQuery("#mt_message_overlay_error").css('display', 'flex');
                jQuery("#mt_loader_overlay").fadeOut();
            }
        }
    }

    function changeCheckBoxOque(event,key){
        if(!checkBox[key])
            checkBox[key] = new Array();

        if(event.checked)
            checkBox[key].push(event.value);
        else
            checkBox[key].splice(checkBox[key].indexOf(event.value));
    }

    const validateForm = (firstName, lastName, email, phone) => {
        let valid = true;

        if(email.val() == ""){
            valid = false;
            showHideError(email, true, true, 'Digite um e-mail válido.');
        }else if(!email.val().match(/^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i)){
            valid = false;
            showHideError(email, true, true, 'Digite um e-mail válido.');
        }else{
            showHideError(email, true, false, 'Este campo está válido.');
        }

        if(firstName.val() == ""){
            valid = false;
            showHideError(firstName, true, true, 'Insira seu nome.');
        }else{
            showHideError(firstName, true, false, 'Este campo está válido.');
        }

        if(lastName.val() == ""){
            valid = false;
            showHideError(lastName, true, true, 'Insira seu sobrenome.');
        }else{
            showHideError(lastName, true, false, 'Este campo está válido.');
        }

        phoneValueEmplEvent = document.getElementById("contactPhoneEmplEvent").value;
        if(phone.val() == "" || phone.val().length < 14 ){
            valid = false;
            showHideError(phone, true, true, 'Digite seu DDD + telefone ou celular.');
            console.log("employee 431");
        }else{
            showHideError(phone, true, false, 'Este campo está válido.');
        }
        
        phone.parent().addClass('mt_warning');

        return valid;
    }

    render();

    
</script>