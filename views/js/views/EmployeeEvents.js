class EmployeeEvents {
    constructor(container, events, urlbase) {
        this._container = container;
        this._events = events;
        this._urlbase = urlbase;
    }
    
    renderView() {
        let eventsHTML = '';

        if(this._events.length > 0) {                
            this._events.map((e,key) => {
                let startDate = moment(e.periods[0].periodStart);
                let endDate = moment(e.periods[0].periodEnd);

                startDate.subtract(3, 'hours');
                endDate.subtract(3, 'hours');

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
                                    <img src="${this._urlbase}resources/svg/map_pointer_icon.svg" />
                                    ${ e.location ? e.location.name : '' }
                                    <img src="${this._urlbase}resources/svg/clock_icon.svg" />
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
                                    <img src="${this._urlbase}resources/svg/map_pointer_icon.svg" />
                                        ${ e.location ? e.location.name : '' }
                                        <img src="${this._urlbase}resources/svg/clock_icon.svg" />
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
                                            <label> * DDD + Telefone: </label>		
                                            <div class="input-group">
                                                <span class="input-group-text flag"><img src="${this._urlbase}resources/svg/flag.svg"></span>
                                                <input id="contactPhone" name="phone" onchange="phone = this.value" type="tel" class="form-control phoneMask phoneInpt" data-bs-toggle="tooltip" data-bs-placement="top" title="(00) 00000-0000">
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
                <div style="max-width: 500px; margin: auto;">
                    <p style="color: #5f7b9d; text-align: center;">Não tem nenhuma palestra agendada no momento, mas deixe uma mensagem para o instrutor para que ele possa saber do seu interesse!</p>
                </div>
            `;
        }

        this._container.html(eventsHTML);
    }

}