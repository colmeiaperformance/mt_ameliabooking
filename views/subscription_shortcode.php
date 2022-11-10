<div id="mt_subscription_shortcode">
    <div id="mt_container">
        <div id="mt_filters" class="hideOrder">

        </div>

        <div id="mt_filter_results">

        </div>

        <!-- Form when there is no events for selected filter -->
        <div id="mt_empty_form">
            <?php include('empty_result_form.php'); ?>
        </div>

        <!-- When Loading --->
        <div id="mt_loader_overlay">
            <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
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
    </div>
</div>

<style>
    #mt_filters.hideOrder #orderBy{
        display: none;
    }

    #mt_filter_results .phoneInpt {
        border-top-left-radius: unset !important;
        border-bottom-left-radius: unset !important;
    }

    #mt_filter_results .phone .input-group img{
        z-index: unset !important;
    }
    
    #mt_filter_results .phone .input-group.mt_warning img{
        margin-bottom: 42px !important;        
    }


    @media (max-width: 992px){
        #mt_filter_results .flag{
            margin-bottom: unset !important;
        }

        #mt_filter_results .flag img{
            margin-bottom: -14px !important;
        }

        #mt_filter_results .phone .input-group.mt_warning img{
            margin-bottom: 27px !important;
        }

    }

    .event-desktop, 
    .event-mobile {
        cursor: pointer;
    }
</style>

<?php
    $instrutorID = false; 
    if(isset($_GET['instrutor'])){
        $instrutorID = intval($_GET['instrutor']);
        $instrutorName = '';

        $searchURL = admin_url( 'admin-ajax.php' ).'/?action=wpamelia_api&call=/entities&types[]=employees&types[]=locations';
        $result = json_decode(file_get_contents($searchURL));

        foreach($result->data->employees as $employee){
            if($employee->id == $instrutorID){
                $instrutorName = $employee->firstName.' '.$employee->lastName;
                break;
            }
        }  
    }
?>

<?php 
   $all_users = get_users();
   $userInfos = [];
   
   foreach($all_users  as $user){
        $desc = get_user_meta($user->ID)['description'][0];
        $userInfos[] = [
        'email' => $user->data->user_email,
        'id' => $user->ID,
        'otherPlaces' => $desc ? explode(';', $desc) : []
        ]; 
   }
?>

<?php 
    $searchURL = admin_url( 'admin-ajax.php' ).'/?action=wpamelia_api&call=/entities&types[]=employees&types[]=locations';
    $result = json_decode(file_get_contents($searchURL));

    $employeesList = [];

    foreach($result->data->employees as $employee){
        $employeesList[] = $employee;
    }
?>

<script>
    const ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    const baseurl = '<?php echo plugin_dir_url( __FILE__ ); ?>';

    let $ = document.querySelector.bind(document);

    const controller = new EventsController(ajaxurl, baseurl, $("#mt_filter_results"));
    const filterController = new FilterController(ajaxurl, baseurl, $("#mt_filters"));

    //Get alla employee
    let wp_user_infos = <?php echo json_encode($userInfos) ?>;

    let employeesList = <?php echo json_encode($employeesList); ?>;

    console.log("Emploee list 120");
    console.log(employeesList);

    console.log("wp user infos 139");
    console.log(wp_user_infos);


    
    async function getEvents() {
        let aux = await controller.list();
        let eventsStateCityFilter = new EventsStateCityFilter(ajaxurl, aux);
        return eventsStateCityFilter;
    }
    
    let promisse = getEvents().then((resposta) => {
        return resposta;
    });

    let eventList = [];
    let orderBy = "";

    let state = new State(promisse);
    let city = new City(promisse);
    
    let states = [];
    let cities = [];
    
    let firstName = "";
    let lastName = "";
    let email = "";
    let phone = "";
    let checkBox = [];

    let nomeDaPalestra = "";
    let cidadeDaPalestra = "";
    
    let instrutorID = false;
    let instrutorName = '';

    instrutorID = '<?php echo $instrutorID; ?>';
    if(instrutorID){
        instrutorID = Number(instrutorID);
        instrutorName = '<?php echo $instrutorName; ?>';
    }else{
        instrutorID = false;   
    }

    let mt_filters = document.getElementById('mt_filters')

    render();
    
    async function render(){
        jQuery("#mt_loader_overlay").fadeIn();
        await getFilterEntities();
        eventList = await controller.list();

        console.log("Event List render");
        console.log(eventList);

        if(instrutorID){

            let list = []
            eventList.forEach((element) => {
                if(element._organizerId && element._organizerId == instrutorID){
                    list.push(element);
                }
            });
            eventList = list;


            if(eventList.length == 0){
                document.getElementById('mt_filter_results').style.display = 'none';

                showNotFoundMessage(true, instrutorName, 'Desculpe! No momento não temos palestra agendada para este instrutor.');

            }else{
                mt_filters.classList.remove('hideOrder');
                jQuery("#mt_empty_form").css('display', 'none');
                document.getElementById('mt_filter_results').removeAttribute('style');
                document.getElementById('mt_filters').removeAttribute('style');
            }

            controller.renderItems(eventList);
            jQuery('.phoneMask').mask(phoneBehavior, spOptions);
        }else{
            document.getElementById('mt_filter_results').style.display = 'none';
            document.getElementById('mt_filters').style.marginBottom = '250px';
            jQuery("#mt_empty_form").css('display', 'none');
        }
        
        jQuery("#mt_loader_overlay").fadeOut();
    }

    async function getFilterEntities(){
        states = await state.list();
        filterController.renderFields(states, cities, "--", "");
        jQuery("#districtFilter").css('display', 'none');
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
            console.log("está com erro");
            jQuery("#mt_message_overlay_error").fadeIn();
            jQuery("#mt_message_overlay_error").css('display', 'flex');

            array.forEach((element) => {
                jQuery(element).on('input', function() {
                    validateForm(array[0], array[1], array[2], array[3]);
                });
            });

        }
        else{
            jQuery("#mt_loader_overlay").fadeIn();
            let bkEvent = eventList.filter(e => e.id == eventId);
            bkEvent = bkEvent[0];
            
            let booking = await controller.booking(bkEvent,email, firstName, lastName, phone, ajaxurl);
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
        console.log(checkBox);
    }


    const removeFilters = async() => {
        jQuery("#mt_loader_overlay").fadeIn();
        state = new State(promisse);
        city = new City(promisse);
        await getFilterEntities();
        eventList = await controller.list();
        if(instrutorID){
            let url = document.URL.replace(`?instrutor=${instrutorID}`,'');
            window.location.href = url;
        }else{
            mt_filters.classList.add('hideOrder');
            jQuery("#mt_filter_results").css('display', 'none');
            document.getElementById('mt_filters').style.marginBottom = '250px';
            jQuery("#mt_empty_form").css('display', 'none');
            jQuery("#msg").css('display', 'none');
            controller.renderItems(eventList);
            jQuery('.phoneMask').mask(phoneBehavior, spOptions);
            jQuery("#mt_loader_overlay").fadeOut();
        }
    }

    //FilterEvents
    const filterEvents = async() => {
        
        jQuery("#mt_loader_overlay").fadeIn();
        jQuery("#msg").css('display', 'none');

        eventList = await controller.list(1, moment(), orderBy, state.sigla ? state.sigla : false, city.nome ? city.nome : false);

        console.log("Event List 325");
        console.log(eventList);
        
        if(eventList.length > 0 || instrutorID){
            jQuery("#mt_empty_form").css('display', 'none');
            if(eventList.length > 0){
                let list = [];
                
                if(instrutorID){
                    eventList.forEach((element) => {
                    if(element._organizerId && element._organizerId == instrutorID){
                        list.push(element);
                    }
                    });
                    eventList = list;
                }

                if(state.sigla){
                    if(eventList.length > 0){
                        mt_filters.classList.remove('hideOrder');
                        jQuery("#districtFilter").css('display', 'block');
                        document.getElementById('mt_filter_results').removeAttribute('style');
                    }else{
                        if(instrutorID){
                            showNotFoundMessage(true, instrutorName, 'O instrutor selecionado não possui eventos cadastrados nessa cidade/estado!');
                        }else{
                            jQuery("#districtFilter").css('display', 'none');
                            showNotFoundMessage();
                        }
                    }

                    document.getElementById('mt_filters').removeAttribute('style');
                    controller.renderItems(eventList);
                    jQuery('.phoneMask').mask(phoneBehavior, spOptions);
                }
            }else{
                showNotFoundMessage(true, instrutorName, 'O instrutor selecionado não possui eventos cadastrados nessa cidade/estado!');
                
                controller.renderItems([]);
            }
            
        }else{
            showNotFoundMessage();
        }
        jQuery("#mt_loader_overlay").fadeOut();
    }

    const showNotFoundMessage = (instructor = false, instrutorName = '',alertMensage = '', subtitleMensage = '') => {
        let texto = "";
        
        if(city?.nome){
            texto = `Cidade: ${city.nome}, Estado: ${state.sigla}`;
        }else{
            if(state?.sigla){
                texto = `Estado: ${state.sigla}`;
            }
        }    

        if(alertMensage){
            jQuery("#alertMensage").text(alertMensage);
        }

        if(subtitleMensage){
            jQuery("#subtitleMensage").text(subtitleMensage);
        }
        
        if(instructor){
            if(city?.nome || state?.sigla){
                jQuery("#cidadeOndeQuerPalestra").css('display', 'block');
                jQuery("#cidadeOndeQuerPalestra").val(texto);
            }else{
                jQuery("#cidadeOndeQuerPalestra").css('display', 'none');
            }

            jQuery("#origemDeMensagem").css('display', 'block');
            jQuery("#origemDeMensagem").val(instrutorName);
        }else if(city?.nome || state?.sigla){
            jQuery("#origemDeMensagem").css('display', 'none');
            jQuery("#cidadeOndeQuerPalestra").css('display', 'block');
            jQuery("#cidadeOndeQuerPalestra").val(texto);
        }

        mt_filters.classList.add('hideOrder');
        document.getElementById('mt_filter_results').style.display = 'none';
        document.getElementById('mt_filters').removeAttribute('style');
        jQuery("#mt_empty_form").css('display', 'block');
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

        if(phone.val() == ""){
            valid = false;
            showHideError(phone, true, true, 'Digite seu telefone ou celular.');
        }else{
            showHideError(phone, true, false, 'Este campo está válido.');
        }
        
        phone.parent().addClass('mt_warning');

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
        div.classList.add("mt-1")
        div.innerText = text;
        showHideError(elem, false);
        elem.parent().append(div);
    }

    //FilterInteractors
    const changeState = async(uf) =>{
        state.sigla = uf;
        city.nome = '';
        cities = await city.getByUf(uf);
        filterController.renderFields(states, cities, uf, "");
        filterEvents();
    }
    const changeCity = (val) =>{
        city.nome = val;
        filterEvents();
    }

    const changeOrderBy = (order) => {
        orderBy = order;
        jQuery("#mt_loader_overlay").fadeIn();
        evtL = controller.orderBy(eventList, orderBy);
        controller.renderItems(evtL);
        jQuery("#mt_loader_overlay").fadeOut();
    }

    //DOOM Controller
    function closeModal(){
        jQuery("#mt_message_overlay_success").fadeOut();
        jQuery("#mt_message_overlay_error").fadeOut();
    }
    // const toggleSubmission = (key) => {
    //     let $ = jQuery;
    //     let element = $(`#mt_event_details_subscriptions_${key}`);
    //     if(!element.hasClass('oppened')){
    //         $(".mt_event_details_subscriptions.oppened").removeClass('oppened');
    //         element.addClass('oppened');
    //         $(".mt_event_details_container").css('display', 'none');
    //     }else{
    //         $(".mt_event_details_container").css('display', 'block');
    //         element.removeClass('oppened');
    //     }  
    // }

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

    function toggleDetails(event_key){
        let $ = jQuery;
        let element = $(`#mt_event_details_${event_key}`);
        if(!element.hasClass('oppened')){
            $(".mt_event_details.oppened").removeClass('oppened');
            $(".mt_event_details_container").css('display', 'block');
            $(".mt_event_details_subscriptions.oppened").removeClass('oppened');
            element.addClass('oppened');
        }else{
            element.removeClass('oppened');
            $(".mt_event_details_container").css('display', 'block');
            $(".mt_event_details_subscriptions.oppened").removeClass('oppened');
        }
    }

    function filterByDistrict(str){
        let result = eventList;
        result = result.filter(e => e._name.toLowerCase().includes(str.toLowerCase()));

        if(result.length > 0){
            mt_filters.classList.remove('hideOrder');
            document.getElementById('mt_filter_results').removeAttribute('style');
        }else{
            showNotFoundMessage('Desculpe! Não encontramos evento no seu bairro.');
        }

        controller.renderItems(result);
    }
   
</script>