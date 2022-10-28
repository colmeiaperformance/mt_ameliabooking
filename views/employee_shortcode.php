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
    }

    .titleForm {
        color: #5f7b9d !important;
        margin: unset !important;
        max-width: unset !important;
        text-align: center !important;
        margin-bottom: 30px !important;
    }

    #eventsContainer .mt_row {
        width: 100%;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-line-pack: center;
        align-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .containerButtons .btn {
        margin-top: 10px;
        padding: 9px 31px;
        font-weight: 400;
        font-size: var(--font-size-regular);
        line-height: 30px;
        color: #FFFFFF;
        background: #F29F05;
        border: 1px solid #F29F05;
        border-radius: 30px;
    }
    .containerButtons .btn-whatsapp {
        background-color: #76b882;
        border: 1px solid #76b882;
    }

    #eventsContainer .event-desktop {

    }

    #eventsContainer .phoneInpt {
        border-top-left-radius: unset !important;
        border-bottom-left-radius: unset !important;
    }

    #eventsContainer .phone .input-group img{
        z-index: unset !important;
    }
    
    #eventsContainer .phone .input-group.mt_warning img{
        margin-bottom: 42px !important;        
    }
    .event-desktop, 
    .event-mobile {
        cursor: pointer;
    }
 
    #eventsContainer .mt_event_item, #eventsContainer .mt_event_item {
        margin-bottom: 20px;
        width: 100%;
        display: -webkit-box !important;
        display: -ms-flexbox !important;
        display: flex !important;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        padding: 16px 16px;
        border: 1px solid #F29F05;
        border-radius: 10px;
    }
    
    #eventsContainer .mt_event_item .event-mobile, #eventsContainer .mt_event_item .event-mobile {
        display: none;
    }
    
    #eventsContainer .mt_event_item .event-mobile .date-title, #eventsContainer .mt_event_item .event-mobile .date-title {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        -webkit-box-pack: left;
        -ms-flex-pack: left;
        justify-content: left;
        margin-bottom: 10px;
    }
    #eventsContainer .mt_event_item .event-mobile .status-details, #eventsContainer .mt_event_item .event-mobile .status-details {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        -webkit-box-pack: left;
        -ms-flex-pack: left;
        justify-content: left;
        margin-top: 8px;
    }
    #eventsContainer .mt_event_item .event-mobile .status-details span, #eventsContainer .mt_event_item .event-mobile .status-details span {
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 16px;
        color: #50D63D;
        background: rgba(166, 212, 145, 0.15);
        border-radius: 10px;
        padding: 5px 12px 5px 26px;
        margin-left: 15px;
        position: relative;
    }
    
    #eventsContainer .mt_event_item .event-mobile .status-details span::before, #eventsContainer .mt_event_item .event-mobile .status-details span::before {
        position: absolute;
        content: "";
        border-radius: 50%;
        width: 8px;
        height: 8px;
        background: #50D63D;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        left: 10px;
    }
    
    #eventsContainer .mt_event_item .event-mobile .status-details span.closed, #eventsContainer .mt_event_item .event-mobile .status-details span.closed {
        color: #f13535;
    }
    #eventsContainer .mt_event_item .event-mobile .status-details span.closed::before, #eventsContainer .mt_event_item .event-mobile .status-details span.closed::before {
        background: #f13535;
    }
    
    #eventsContainer .mt_event_item .mt_event_date, #eventsContainer .mt_event_item .mt_event_date {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        background-color: #FFC536;
        width: 72px;
        height: 72px;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 500;
        font-size: 20px;
        line-height: 25px;
        border-radius: 10px;
        color: #FFFFFF;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
        -ms-flex-line-pack: center;
        align-content: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-right: 17px;
    }
   
    #eventsContainer .mt_event_item .mt_event_date span, #eventsContainer .mt_event_item .mt_event_date span {
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 15px;
        color: #323B50;
        text-transform: uppercase;
    }
    
    #eventsContainer .mt_event_item .mt_event_title, #eventsContainer .mt_event_item .mt_event_title {
        padding: 8px 0px;
        text-align: left;
    }
    #eventsContainer .mt_event_item h4, #eventsContainer .mt_event_item h4 {
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 500;
        font-size: 1rem;
        line-height: 20px;
        color: #323B50;
    }
    
    #eventsContainer .mt_event_item h4 span, #eventsContainer .mt_event_item h4 span {
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 16px;
        color: #50D63D;
        background: rgba(166, 212, 145, 0.15);
        border-radius: 10px;
        padding: 5px 12px 5px 26px;
        margin-left: 15px;
        position: relative;
    }
    
    #eventsContainer .mt_event_item h4 span::before, #eventsContainer .mt_event_item h4 span::before {
        position: absolute;
        content: "";
        border-radius: 50%;
        width: 8px;
        height: 8px;
        background: #50D63D;
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        left: 10px;
    }
    
    #eventsContainer .mt_event_item h4 span.closed, #eventsContainer .mt_event_item h4 span.closed {
        color: #f13535;
    }
    #eventsContainer .mt_event_item h4 span.closed::before, #eventsContainer .mt_event_item h4 span.closed::before {
        background: #f13535;
    }
    #eventsContainer .mt_event_item h5, #eventsContainer .mt_event_item h5 {
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 400;
        font-size: 1rem;
        line-height: 19px;
        color: #F29F05;
    }
    
    #eventsContainer .mt_event_item .mt_action_button, #eventsContainer .mt_event_item .mt_action_button {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-left: auto;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
        -ms-flex-line-pack: center;
        align-content: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    
    #eventsContainer .mt_event_item .mt_action_button button, #eventsContainer .mt_event_item .mt_action_button button {
        border: 1px solid #7896C1;
        border-radius: 30px;
        font-family: "Work Sans";
        font-style: normal;
        font-weight: 700;
        font-size: 1rem;
        line-height: 24px;
        color: #7896C1;
        background: none;
        padding: 9px 31px;
        cursor: pointer;
        -webkit-transition: 0.5s;
        transition: 0.5s;
    }
    
    #eventsContainer .mt_event_item .mt_action_button button:hover, #eventsContainer .mt_event_item .mt_action_button button:hover {
        border: 1px solid #7896C1;
        color: white;
        background-color: #7896C1;
    }
    #eventsContainer .mt_event_item .mt_row, #eventsContainer .mt_event_item .mt_row {
        -ms-flex-line-pack: center;
        align-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    #eventsContainer .mt_event_item .mt_row:last-child, #eventsContainer .mt_event_item .mt_row:last-child {
        padding: 0px;
    }
    #eventsContainer .mt_event_item .mt_event_details, #eventsContainer .mt_event_item .mt_event_details {
        height: 0px;
        overflow: hidden;
        border-top: 2px solid #F29F05;
        opacity: 0;
        width: 100%;
        -webkit-transition: 0.8s;
        transition: 0.8s;
    }
    #eventsContainer .mt_event_item .mt_event_details.oppened, #eventsContainer .mt_event_item .mt_event_details.oppened {
        padding: 20px 0px;
        margin-top: 15px;
        height: auto !important;
        opacity: 1 !important;
    }
    #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title, #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title {
        margin-bottom: 20px;
    }
    #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4, #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4 {
        color: #e6aa13;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 100%;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
        -ms-flex-line-pack: center;
        align-content: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        font-size: 1rem;
        font-weight: 600;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding-bottom: 20px;
    }
    
    #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4 img, #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4 img {
        cursor: pointer;
    }
    
    #eventsContainer .mt_event_item .mt_event_details .mt_event_details_description p, #eventsContainer .mt_event_item .mt_event_details .mt_event_details_description p {
        color: #323B50;
        font-size: 18px;
        text-align: justify;
        margin-bottom: 15px;
    }
    #eventsContainer .mt_event_item .mt_event_details button, #eventsContainer .mt_event_details button {
        float: right;
    }

    .containerButtons .btn {
        font-size: var(--font-size-extrasmall);
        line-height: 18px;
        padding: 5px 12px;
    }

    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .event-desktop, #eventsContainer .mt_event_item .event-desktop {
            display: none;
        }

        #eventsContainer {
            margin: 40px auto;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .event-mobile, #eventsContainer .mt_event_item .event-mobile {
            display: block;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .event-mobile .status-details span, #eventsContainer .mt_event_item .event-mobile .status-details span {
            font-weight: 400;
            font-size: 9.4466px;
            line-height: 11px;
            padding: 2px 6px 2px 22px;
            margin-left: 0;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .event-mobile .status-details span::before, #eventsContainer .mt_event_item .event-mobile .status-details span::before {
            width: 4.38px;
            height: 4.38px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .event-mobile h4, #eventsContainer .mt_event_item .event-mobile h4 {
            padding: 0 !important;
            text-align: left !important;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .mt_event_date, #eventsContainer .mt_event_item .mt_event_date {
            margin-right: 5px;
            font-weight: 500;
            font-size: 24.3367px;
            line-height: 29px;
            width: 58.41px;
            height: 58.41px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .mt_event_date span, #eventsContainer .mt_event_item .mt_event_date span {
            font-weight: 400;
            font-size: 12.9796px;
            line-height: 15px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item h4, #eventsContainer .mt_event_item h4 {
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item h4 span, #eventsContainer .mt_event_item h4 span {
            font-weight: 400;
            font-size: 9.4466px;
            line-height: 11px;
            padding: 2px 6px 2px 22px;
            margin-left: 0;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item h4 span::before, #eventsContainer .mt_event_item h4 span::before {
            width: 4.38px;
            height: 4.38px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item h5, #eventsContainer .mt_event_item h5 {
            font-size: 14px;
            font-weight: 400;
            line-height: 16px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item h5 img, #eventsContainer .mt_event_item h5 img {
            max-width: 8px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item h5, #eventsContainer .mt_event_item h5 {
            font-weight: 400;
            font-size: 14px;
            line-height: 15px;
        }
    }
    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .mt_action_button, #eventsContainer .mt_event_item .mt_action_button {
            margin-right: 0;
        }
    }

    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .mt_action_button button, #eventsContainer .mt_event_item .mt_action_button button {
            font-weight: 500;
            font-size: 11px;
            line-height: 8px;
            padding: 4px 12px;
        }
    }

    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4, #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4 {
            font-size: 16px !important;
            line-height: 16px !important;
            margin: 0 auto !important;
        }
    }

    @media (max-width: 992px) {
        #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4 img, #eventsContainer .mt_event_item .mt_event_details .mt_event_details_title h4 img {
            width: 18px;
            height: auto;
        }
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

    @media screen and (max-width: 992px) {
        .mt_event_item .mt_row {
            -webkit-box-orient: vertical !important;
            -webkit-box-direction: normal !important;
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }
        .mt_event_item .mt_row .mt_column {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
    }

    @media screen and (max-width: 992px) and (max-width: 992px) {
        .mt_event_item .mt_row .mt_column {
            width: 90%;
            margin: 0 auto;
            margin-bottom: 10px;
        }
    }
    @media screen and (max-width: 992px) {
        .mt_event_item .mt_row .mt_column:last-child {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
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
    console.log(ajaxurl);
    const baseurl = '<?php echo get_template_directory_uri(); ?>';
    const urlbase = '<?php echo plugin_dir_url( __FILE__ ); ?>';
    const urlRedirectInstructorsPage = '<?php echo home_url() . '/instrutores'; ?>';
    let $ = document.querySelector.bind(document);
    let wp_user_infos = <?php echo json_encode($userInfos) ?>;
    let controller = new EmployeeController(ajaxurl, baseurl, $("#mt_employee_container"));
    let employee = new Employee()

    const eventsController = new EventsController(ajaxurl, urlbase, jQuery("#eventsContainer"));

    console.log("url base");
    console.log(urlbase);
    
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

        jQuery("#contactEmail, #contactName, #contactPhone, #contactMessage").on('input', function() {
            formIsValid(jQuery("#contactEmail"), jQuery("#contactName"), jQuery("#contactPhone"), jQuery("#contactMessage"));
        });

        if(formIsValid(jQuery("#contactEmail"), jQuery("#contactName"), jQuery("#contactPhone"), jQuery("#contactMessage"))){
            const url = `${ajaxurl}?action=event_form`;
            let formData = new FormData();
            formData.append('email',jQuery("#contactEmail").val())
            formData.append('aceite',jQuery("#contactAceite").val())
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
                setTimeout(function(){ window.location.replace(`${urlRedirectInstructorsPage}`); }, 1500);
            }
        }

        jQuery("#mt_loader_overlay").fadeOut();
    }

    function formIsValid(email, name, phone, message){
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

        if(phone.val() == ""){
            valid = false;
            showHideError(phone, true, true, 'Digite seu telefone ou celular.');
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
        
        if(!eventsSection.classList.contains('hide')){
            jQuery("#mt_loader_overlay").fadeIn();
            let events = await eventsController.list();
            events = events.filter((e) => {
                return e._organizerId == id;
            });
            console.log("events cima id");
            console.log(events);

            const eventsContainer = jQuery("#eventsContainer");
            
            let eventsHTML = '';

            if(events.length > 0) {                
                events.map((e,key) => {
                    // let baseurl 
                    console.log('baseurl');
                    console.log(urlbase);
                    let startDate = moment(e.periods[0].periodStart);
                    let endDate = moment(e.periods[0].periodEnd);

                    // startDate.subtract(3, 'hours');
                    // endDate.subtract(3, 'hours');

                    let weekday = (moment(e.periods[0].periodStart)).format('dddd');

                    switch(weekday){
                        case 'Sunday':
                            weekday = 'DOM';
                            break;
                        
                        case 'Monday':
                            weekday = 'SEG';
                            break;

                        case 'Tuesday':
                            weekday = 'TER';
                            break;

                        case 'Wednesday':
                            weekday = 'QUA';
                            break;
                            
                        case 'Thursday':
                            weekday = 'QUI';
                            break;
                        
                        case 'Friday':
                            weekday = 'SEX';
                            break;

                        case 'Saturday':
                            weekday = 'SAB';
                            break;
                    }
                    // '${
                    // 					e.customFields ?
                    // 					`
                    // 						<div class="mt_row">
                    // 							${
                    // 								e.customFields.map(e => {
                    // 									return(`
                    // 										<div class="mt_column" style="padding:0px 10px">
                    // 											<label> ${e.label} </label>
                    // 											<div class="mt_checkbox_container">
                    // 												${e.options.map(opt => {
                    // 													return(`
                    // 														<div class="item">
                    // 															<input id="contactAceite" type="checkbox" onChange="changeCheckBoxOque(this, ${opt.customFieldId})" value="${opt.label}"  ${opt.required ? 'required' : ''} name="customField${opt.customFieldId}[]" >
                    // 															<label for="origin">${opt.label}</label>
                    // 														</div>
                    // 													`)	
                    // 												}).join('')}
                    // 											</div>
                    // 										</div>
                    // 									`)
                    // 								}).join('')
                    // 							}
                    // 						</div>
                    // 					` 
                    // 					:
                    // 					``
                    // 				}'

                    const month_labels = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
                    const month_names = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']
                    let startDateStr = `${startDate.format('D') } de ${month_names[startDate.month()]} de ${startDate.format('YYYY')}`;
                    let endDateStr = `${endDate.format('D') } de ${month_names[endDate.month()]} de ${endDate.format('YYYY')}`;
                    eventsHTML += `
                    <div class="mt_event_item" style="padding: unset !important;"> 
                            <div class="mt_row event-desktop" onclick="toggleSubmission(${key})" style="padding: 16px 16px !important;">
                                <div class="mt_event_date">
                                    <span>${month_labels[startDate.month()]}</span>
                                    ${startDate.format('D')}
                                    <span>${weekday}</span>
                                </div>
                                <div class="mt_event_title">
                                    <h4>${e.name} - ${e.organizer ? e.organizer?.firstName : ''} ${e.organizer ? e.organizer?.lastName : ''}
                                        <span class="${e.closed || !e.bookable ? 'closed' : 'oppened'}" style="display:none;">
                                            Inscrições ${e.closed || !e.bookable ? 'Encerradas' : 'Abertas'}
                                        </span>
                                    </h4>
                                    <h5> 
                                        <img src="${urlbase}resources/svg/map_pointer_icon.svg" />
                                        ${ e.location ? e.location.name : '' }
                                        <img src="${urlbase}resources/svg/clock_icon.svg" />
                                        ${startDateStr}  ${startDate.format('HH:mm')} -
                                        ${startDateStr == endDateStr ? endDate.format('HH:mm') : endDateStr + ' ' + endDate.format('HH:mm') }
                                    </h5>
                                </div>
                                <div class="mt_action_button">
                                    <button class="btn_subscription">
                                        Inscreva-se
                                    </button>
                                </div>
                            </div>						

                            <div class="event-mobile" onclick="toggleSubmission(${key})" style="padding: 16px 16px !important;">
                                <div class="date-title">
                                    <div class="mt_event_date">
                                        <span>${month_labels[startDate.month()]}</span>
                                        ${startDate.format('D')}
                                        <span>${weekday}</span>
                                    </div>
                                    <div style="width: calc(100% - 60px);text-align:left;">
                                        <h4>${e.name} - ${e.organizer ? e.organizer?.firstName : ''} ${e.organizer ? e.organizer?.lastName : ''}
                                        </h4>
                                        <h5> 
                                        <img src="${urlbase}resources/svg/map_pointer_icon.svg" />
                                            ${ e.location ? e.location.name : '' }
                                            <img src="${urlbase}resources/svg/clock_icon.svg" />
                                            ${startDateStr}  ${startDate.format('HH:mm')} -
                                            ${startDateStr == endDateStr ? endDate.format('HH:mm') : endDateStr + ' ' + endDate.format('HH:mm') }
                                        </h5>
                                    </div>
                                </div>
                                <div class="status-details">
                                            <span class="${e.closed || !e.bookable ? 'closed' : 'oppened'}" style="display:none;">
                                                Inscrições ${e.closed || !e.bookable ? 'Encerradas' : 'Abertas'}
                                            </span>
                                        <div class="mt_action_button">
                                            <button class="btn_subscription">
                                                Inscreva-se
                                            </button>
                                        </div>
                                </div>
                            </div>

                        <div class="mt_row" id="subscription_${key}" style="padding: 16px 16px !important; display: none;">
                            <div class="mt_event_details" id="mt_event_details_${key}">
                                <div class="mt_event_details_container">
                                    
                                </div>
                                <form id="formEvt${e.id}" class="needs-validation" method="post">
                                <input type="hidden" name="nomeDaPalestra" value="${e.name}">
                                <input type="hidden" name="cidadeDaPalestra" value="${e.local}">
                                <div class="mt_event_details_subscriptions" id="mt_event_details_subscriptions_${key}">
                                    <div class="mt_row">
                                        <div class="mt_column">
                                            <label> * Primeiro Nome: </label>
                                            <input name="firstName" onchange="firstName = this.value" type="text" class="form-control firstName">										
                                        </div>
                                        <div class="mt_column">
                                            <label> * Sobrenome: </label>
                                            <input  name="lastName" onchange="lastName = this.value" type="text" class="form-control lastName">										
                                        </div>
                                    </div>
                                    <div class="mt_row">
                                        <div class="mt_column">
                                            <label> * Email: </label>
                                            <input  name="email" onchange="email = this.value" type="email" class="form-control email">							
                                        </div>						
                                        <div class="mt_column phone">
                                            <label> * Telefone: </label>		
                                            <div class="input-group">
                                                <span class="input-group-text flag"><img src="${urlbase}resources/svg/flag.svg"></span>
                                                <input name="phone" onchange="phone = this.value" type="tel" class="form-control phoneMask phoneInpt">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    ${
                                        e.customFields ?
                                        `
                                            <div class="mt_row">
                                                ${
                                                    e.customFields.map(e => {
                                                        return(`
                                                            <div class="mt_column" style="padding:0px 10px">
                                                                <label> ${e.label} </label>
                                                                <div class="mt_checkbox_container">
                                                                    ${e.options.map(opt => {
                                                                        return(`
                                                                            <div class="item">
                                                                                <input id="contactAceite" type="checkbox" onChange="changeCheckBoxOque(this, ${opt.customFieldId})" value="${opt.label}"  ${opt.required ? 'required' : ''} name="customField${opt.customFieldId}[]" >
                                                                                <label for="origin">${opt.label}</label>
                                                                            </div>
                                                                        `)	
                                                                    }).join('')}
                                                                </div>
                                                            </div>
                                                        `)
                                                    }).join('')
                                                }
                                            </div>
                                        ` 
                                        :
                                        ``
                                    }


                                    <div class="mt_row confirm">
                                        <div class="mt_column">
                                            <button type="submit" onClick="bookingEvent(${e.id})" class="mt_btn_default"> Confirmar </button>
                                        </div>
                                    </div>
                                </div>
                                </Form>
                            </div>
                        </div>
                        </div>
                    `;
                });
            }else {
                eventsHTML = `
                    <p style="color: #5f7b9d; text-align: center;">O instrutor não possui palestras.</p>
                `;
            }
            
            eventsContainer.html(eventsHTML);
            jQuery('.phoneMask').mask(phoneBehavior, spOptions);
            jQuery("#mt_loader_overlay").fadeOut();
        }
    }

    render();
</script>