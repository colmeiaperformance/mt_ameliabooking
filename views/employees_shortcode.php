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
    // if(isset($user->allcaps['amelia_write_others_events'])){
    //     if(!$user->allcaps['amelia_write_others_events']){ 
    //         $desc = get_user_meta($user->ID)['description'][0];
    //         $userInfos[] = [
    //         'email' => $user->data->user_email,
    //         'id' => $user->ID,
    //         'otherPlaces' => $desc ? explode(';', $desc) : []
    //         ]; 
    //     }
    // }
   }

?>
<div id="mt_employees_shortcode" class="p-0">
    <div id="mt_container">
        <div id="mt_filters">

        </div>
        <br/>
        <br/>
        <!-- Form when there is no events for selected filter -->
        <div id="mt_empty_form">
            <?php include('empty_result_form.php'); ?>
        </div>
        <section  class="instrutores-carousel">
            <div class="container">
            
                <div id="mt-instrutores" class="swiper mt-swiperInstrutores" data-bs-ride="carousel">
                    <div class="swiper-wrapper" id="mt_employees_result">
                        
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>

        <div id="mt_loader_overlay">
            <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
    </div>
</div>

<script>
    const ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    const baseurl = '<?php echo site_url(); ?>';

    let $ = document.querySelector.bind(document);
    let employee_list = [];
    //Get alla employee
    let wp_user_infos = <?php echo json_encode($userInfos) ?>;
    const controller = new EmployeeController(ajaxurl, baseurl, $("#mt_employees_result"));
    const filterController = new EmployeeFilterController(ajaxurl, baseurl, $("#mt_filters"));
    
    let orderBy = "";
    let state = new State();
    let city = new City();
    let states = [];
    let cities = [];
    let currentName = "";

    employeees = controller.list(false, false, false, wp_user_infos)
    const instructorStateCityFilter = new InstructorStateCityFilter(ajaxurl, employeees);
    
    render();

    async function render() {
        jQuery("#mt_loader_overlay").fadeIn();
        jQuery("#mt_empty_form").css('display', 'none');
        await getFilterEntities();
        await getEmployees();
        jQuery("#mt_loader_overlay").fadeOut();      
    }

    async function getEmployees() {
        jQuery("#origemDeMensagem").css('display', 'none');
        employee_list = await controller.list(false, false, false, wp_user_infos);
        controller.renderItems(employee_list);
        startSlider();
    }

    const showNotFoundMessage = (alertMensage = '', subtitleMensage = 'Informe seus dados para receber nosso contato, assim que houver instrutor em sua região:') => {
        let texto = "";
        
        if(city?.nome){
            texto = `Cidade: ${city.nome}, Estado: ${state.sigla}`;
        }else{
            if(state?.sigla){
                texto = `Estado: ${state.sigla}`;
            }
        } 
        
        jQuery("#typeForm").val('instrutores');
        jQuery("#origemDeMensagem").css('display', 'none');

        if(alertMensage){
            jQuery("#alertMensage").text(alertMensage);
        }

        if(subtitleMensage){
            jQuery("#subtitleMensage").text(subtitleMensage);
        }
        
        if(city?.nome || state?.sigla){
            jQuery("#cidadeOndeQuerPalestra").css('display', 'block');
            jQuery("#cidadeOndeQuerPalestra").val(texto);
        }

        jQuery("#mt_empty_form").css('display', 'block');
        showHideCarousel();
    }

    function showHideCarousel(show = false){
        if(show){
            jQuery(".instrutores-carousel").css('display', 'flex');
        }else{
            jQuery(".instrutores-carousel").css('display', 'none');
        }
    }
    
    async function getFilterEntities(){
        states = await state.list();
        filterController.renderFields(states, cities, "--", "--", currentName);
    }

    function filterByName(str){
        currentName = str;
        let result = employee_list;
        result = result.filter(e => e.firstName.toLowerCase().includes(str.toLowerCase()) || e.lastName.toLowerCase().includes(str.toLowerCase()));

        if(result.length > 0){
            jQuery("#mt_empty_form").css('display', 'none');
            showHideCarousel(true);
        }else{
            showNotFoundMessage('Desculpe! Não encontramos este instrutor.');
        }
        controller.renderItems(result);


        console.log(result);
        startSlider();
    }

    function startSlider() {
        jQuery(document).ready(function() {
            var swiper = new Swiper(".mt-swiperInstrutores", {
                slidesPerView: 4,
                slidesPerGroup: 4,
                loop: false,
                spaceBetween: 10,
                autoplay: true,
                autoplay: {
                delay: 3000,
                pauseOnMouseEnter: true,
                disableOnInteraction: false
                },  
                watchSlidesProgress: true,
                watchOverflow: true,
                lazyLoading: true,
                resizeObserver: true,
                observer: true,
                centeredSlides:false,
                pagination: {
                el: ".swiper-pagination",
                clickable: false
                },
                navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                360: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 0,
                },
                576: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 3,
                    slidesPerGroup: 3,
                    spaceBetween: 0,
                },		
                992: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                    spaceBetween: 0,
                }
                }
            });
        });
    }

    const removeFilters = async() => {
        jQuery("#mt_loader_overlay").fadeIn();
        jQuery("#mt_empty_form").css('display', 'none');
        currentName = "";
        state = new State();
        city = new City();
        filterController.renderFields(states, cities, "--", "--", currentName);
        employess = await controller.list(false, false, false, wp_user_infos);
        employess.length > 0 ? showHideCarousel(true) : showHideCarousel();
        controller.renderItems(employess);
        startSlider();
        jQuery("#mt_loader_overlay").fadeOut(); 
         
    }


    const filterEvents = async() => {
        jQuery("#mt_loader_overlay").fadeIn();
        console.log(city.nome);
        eventList = await controller.list(orderBy, state.sigla ? state.sigla : false,
        city.nome != '' ? city.nome : false, wp_user_infos);
        if(eventList.length > 0){
            jQuery("#mt_empty_form").css('display', 'none');
            showHideCarousel(true);
            controller.renderItems(eventList);
        }else{
            showNotFoundMessage('Desculpe! No momento não temos instrutor em sua região.');
            showHideCarousel();
        }
        startSlider();
        jQuery("#mt_loader_overlay").fadeOut();
    }


    //FilterInteractors
    const changeState = async(uf) =>{
        state.sigla = uf;
        cities = await city.getByUf(uf);
        filterController.renderFields(states, cities, uf, "--", currentName);
    }
    const changeCity = (val) =>{
        city.nome = val;
    }
    const changeOrderBy = (order) => {
        orderBy = order;
        jQuery("#mt_loader_overlay").fadeIn();
        controller.orderBy(employee_list, orderBy);
        controller.renderItems(employee_list);
        jQuery("#mt_loader_overlay").fadeOut();
        startSlider()
    }


</script>
