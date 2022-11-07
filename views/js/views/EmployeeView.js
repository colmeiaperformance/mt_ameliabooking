class EmployeeView extends View {
    constructor(elemento, scope, baseUrl){
        super(elemento, scope, baseUrl);
    }

    template(model, baseUrl){
        let uniteNames = model.otherLocations;
        console.log("modelll 8");
        console.log(model);
        uniteNames = uniteNames.filter(u => u != " " && u != "");
        return(
            `
            <section class="instrutor-profile">
                <div class="col-12 instrutor-picture-mobile mb-5 text-center d-block d-lg-none">
                    <img loading="lazy" src='${model.pictureFullPath}' alt="Instrutor">  
                </div>
                <div class="container">
                    <div class="row d-flex justify-content-center align-items-stat">
                    <div class="col-12 col-lg-5 instrutor-picture pe-lg-4 mb-4 mb-lg-0 text-center d-none d-lg-block">
                        <img loading="lazy" src=${model.pictureFullPath} alt="Instrutor">  
                    </div>
                    <div class="col-12 col-lg-7 instrutor-bio">
                        <div class="d-flex align-items-center justify-content-start mb-2 mb-lg-4">
                        <div class="mt-icon"><img loading="lazy" src= '${baseUrl}/images/instrutor/mt.png' alt="mt."></div>
                        <h1>${model.firstName} ${model.lastName}</h1>
                        </div>
                        <div class="instrutor-address">
                        <div class="d-flex align-items-center align-items-lg-baseline justify-content-start mb-2"><img loading="lazy" src='${baseUrl}/images/instrutor/map.png'  alt="Mapa"> <p><strong>Onde atua:</strong>
                        ${uniteNames ? uniteNames.map(un => un != " " && un != "" ? `${un}` : '' ).join(',') : ''}</p></div> 
                        ${
                            uniteNames.length > 0 ? `
                                <div class="d-flex align-items-center align-items-lg-baseline mb-2"><img loading="lazy" src='${baseUrl}/images/instrutor/building.png' alt="Prédio"> <p><strong>Unidades:</strong> ${model.addressLine ? model.addressLine : ''}
                                    
                                </p></div> 
                            ` : ``
                        }
                        </div>
                        <div class="mt-4">
                        <p>
                            ${model.note ? model.note : ''}
                        </p>
                        </div>
                        <div class="containerButtons">
                            <div class="text-center div-button-whatsapp">
                                <a target="_blank" href="https://api.whatsapp.com/send?phone=&amp;text=Ol%C3%A1,%20acessei%20seu%20contato%20no%20site%20da%20MT%20e%20gostaria%20de%20saber%20mais%20sobre%20o%20curso." class="btn btn-whatsapp"> 
                                    <img loading="lazy" src="${baseUrl}/images/instrutor/wpp.png" alt="Whatsapp"> Fale comigo no Whatsapp
                                </a>
                            </div>
                            <div class="text-center div-button-events">
                                <button class="btn btn-events" onclick="toggleEvents()">Agende a palestra gratuita</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="instructorEventsSection" class="hide">
                <div class="container">
                    <div id="eventsContainer" class="row row d-flex align-items-baseline">
                    
                    </div>
                </div>
            </section>

            <section class="instrutor-contact">
                <div class="container">
                <div class="row d-flex align-items-baseline">
                    <div class="col-12 col-lg-7 mt-4 mt-lg-0 m-auto">
                        <h2 class="titleForm">Quero receber contato do instrutor</h2>
                        <form id="employee-send-contact" onsubmit="sendContactForm(event,this)">
                            <div class="form-group">
                                <input type="text" class="form-control" id="contactName" aria-describedby="textHelp" placeholder="Nome">
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <input type="text" class="form-control" id="contactEmail" aria-describedby="emailHelp" placeholder="Email">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <input type="tel" name="phone" class="form-control" id="contactPhone" aria-describedby="phoneHelp" placeholder="Telefone">
                                </div>
                            </div>
                            <div class="row">
                            <p class="mt-2 mb-1">Melhor dia e período para contato:</p>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 form-group">
                                    <select name="field[80]" class="form-control mt-1" id="field[80]" >
                                        <option selected>
                                        </option>
                                        <option value="Domingo" >
                                        Domingo
                                        </option>
                                        <option value="Segunda-feira" >
                                        Segunda-feira
                                        </option>
                                        <option value="Terça-feira" >
                                        Terça-feira
                                        </option>
                                        <option value="Quarta-feira" >
                                        Quarta-feira
                                        </option>
                                        <option value="Quinta-feira" >
                                        Quinta-feira
                                        </option>
                                        <option value="Sexta-feira" >
                                        Sexta-feira
                                        </option>
                                        <option value="Sábado" >
                                        Sábado
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <select name="field[81]" id="field[81]" class="form-control mt-1">
                                        <option selected>
                                        </option>
                                        <option value="Manhã" >
                                        Manhã
                                        </option>
                                        <option value="Tarde" >
                                        Tarde
                                        </option>
                                        <option value="Noite" >
                                        Noite
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                            <textarea class="form-control" id="contactMessage" rows="7" placeholder="Sua mensagem"></textarea>
                            </div>
                            <div class="form-group d-flex my-3">
                             <input id="contactAceite" type="checkbox" name="field[75][]" value="Ao preencher meus dados, concordo em receber comunicações sobre produtos e serviços, conforme a Política de Privacidade."   >
                                <span class="ms-3 contactAceite">
                                    <label for="contactAceite">
                                    Ao preencher meus dados, concordo em receber comunicações sobre produtos e serviços, conforme a Política de Privacidade.
                                    </label>
                                </span>
                            </div>
                            <div class="text-end">
                            <button type="submit" class="btn">Enviar Mensagem</button>
                            </div>
                        </form>
                    </div>
                </div>

                </div>
            </section>
            
            
            `
        );
    }
}