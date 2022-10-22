class EventsController {
    constructor(ajaxUrl, baseUrl, container, event = new Event()){
        this._container = container;
        this._ajaxUrl = ajaxUrl;
        this._baseUrl = baseUrl;;
        this._event = event ? event : new Event();
        this._view = new EventItem(container, this, baseUrl)
        this._listView = new EventItem(container, this, baseUrl);
    }

    renderItems(eventList){
        let $ = document.querySelector.bind(document);
        this._view.update(eventList);
    }

    booking = async(event,email, fistName, lastName, phone, ajaxUrl) => {
        let payload = {
            "type": "event",
            "bookings": [
                {
                    "customer": {
                        "email": email,
                        "externalId": null,
                        "firstName": fistName,
                        "id": null,
                        "lastName": lastName,
                        "phone": phone,
                        "countryPhoneIso": "br"
                    },
                    "customFields": {},
                    "customerId": null,
                    "extras": [],
                    "persons": 1,
                    "ticketsData": null,
                    "utcOffset": -180,
                    "deposit": false
                }
            ],
            "payment": {
                "amount": "0",
                "gateway": "onSite",
                "currency": "BRL"
            },
            "recaptcha": false,
            "locale": "pt_BR",
            //"timeZone": "America/Sao_Paulo",
            "couponCode": "",
            "componentProps": {
                "phonePopulated": 0,
                "containerId": "amelia-app-booking0",
                "trigger": "",
                "useGlobalCustomization": 0,
                "bookableType": "event",
                "bookable": {
                    "id": event.id,
                    "name": event.name,
                    "price": event.price,
                    "depositData": null,
                    "maxCapacity": event.maxCapacity,
                    "color": "#1788FB",
                    "aggregatedPrice": 1,
                    "bookingStart": moment(event.periods[0].periodStart).format('YYYY-MM-DD HH:mm:ii'),
                    "bookingStartTime": moment(event.periods[0].periodStart).format('HH:mm:ii'),
                    "ticketsData": null
                },
                "recurringData": [],
                "hasCancel": 0,
                "hasHeader": 0,
                "appointment": {
                    "bookings": [
                        {
                            "customer": {
                                "email": email,
                                "externalId": null,
                                "firstName": fistName,
                                "id": null,
                                "lastName": lastName,
                                "phone": phone,
                                "countryPhoneIso": "br"
                            },
                            "customFields": {},
                            "customerId": null,
                            "extras": [],
                            "persons": 1
                        }
                    ],
                    "payment": {
                        "amount": "0",
                        "gateway": "onSite",
                        "currency": "BRL"
                    },
                    "group": 0
                },
                "dialogClass": "am-confirm-booking-events-list",
                "queryParams": {
                    "dates": [
                        moment().format("YYYY-MM-DD")
                    ],
                    "tag": null,
                    "locationId": null,
                    "page": 1,
                    "id": null,
                    "recurring": 0,
                    "providers": null
                }
            },
            "returnUrl": "http://localhost/colmmedt/eventos/",
            "eventId": event.id,
        }
        let booking_request = await axios.post(`${ajaxUrl}?action=wpamelia_api&call=/bookings`,
            payload
        )
        if(booking_request.status == 200){
            return true
        }
        return false;
    }


    dynamicSort(property) {
        var sortOrder = 1;
        if(property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }
        return function (a,b) {
            if(property == "_start"){
                var result = (moment(a[property]).isBefore(moment(b[property]))) ? -1 : (moment(a[property]).isAfter(moment(b[property]))) ? 1 : 0;
                return result * sortOrder;
            }else{
                var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
                return result * sortOrder;
            }
        }
    }

    listByOrganizer = async(organizerId,startDate = moment(), page = 1) => {
        let events_consult = await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/events&dates[]=${startDate.format('YYYY-MM-DD')}&page=${page}`);
        let events = events_consult.data.data.events;
        let entities_consult = await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/entities&types[]=locations&types[]=tags&types[]=custom_fields&types[]=employees`);     
        let entities = entities_consult.data.data;


        while(events_consult.data.data.count > events.length && events.length > 50) {
            events_consult =  await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/events&dates[]=${startDate.format('YYYY-MM-DD')}&page=${page}`);
            if (events_consult.data.data.events.length > 0)
                events.push(events_consult.data.data.events);
        }

        events = events.filter(e => e.organizerId == organizerId);
        let eventList = [];
        if(events_consult.status == 200){
            events.forEach((e) => {
                let e_location = entities.locations.filter( l => l.id == e.locationId)[0];
                let e_organizer = entities.employees.filter( o => o.id == e.organizerId)[0];
                let newEvent = new Event();
                let location = new Location();
                let employee = new Employee();

                newEvent = newEvent.constructByObjects(e,  e_organizer ? employee.constructByObjects(e_organizer) : false,
                e_location ? location.constructByObjects(e_location) : false);
                eventList.push(newEvent);
            });
        } 
        return eventList;
    }

    list = async(page = 1, startDate = moment(), orderBy = false, stateFilter = false, cityFilter = false, ) => {
        let entities_consult = await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/entities&types[]=locations&types[]=tags&types[]=custom_fields&types[]=employees`);     
        let events_consult = await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/events&dates[]=${startDate.format('YYYY-MM-DD')}&page=${page}`);
        let events = events_consult.data.data.events;
        let entities = entities_consult.data.data;
        let customFields = entities_consult.data.data.customFields;

        console.log("events consult");
        console.log(events_consult);


        console.log("events_consult.data.data.events");
        console.log(events_consult.data.data.events);
        
        while(events_consult.data.data.count > events.length) {
            events_consult =  await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/events&dates[]=${startDate.format('YYYY-MM-DD')}&page=${page}`);
            if (events_consult.data.data.events.length > 0)
                events.push(events_consult.data.data.events);
        }

        let eventList = [];

        console.log("Events 183");
        console.log(events);
               
        events.forEach((e) => {
            console.log("Dentro do forEach");
            console.log(e);

            let filterPass = true;
            let e_location = entities.locations.filter( l => l.id == e.locationId)[0];
            let e_organizer = entities.employees.filter( o => o.id == e.organizerId)[0];
            let newEvent = new Event();
            let location = new Location();
            let employee = new Employee();
            let e_custom_fields = customFields;

            if(e_location){
                console.log("e_location");
                if(cityFilter){
                    console.log("cityFilter");
                    if(!e_location.name.toLowerCase().includes(cityFilter.toLowerCase()) 
                    || !e_location.name.toLowerCase().includes(stateFilter.toLowerCase()))
                        filterPass = false;
                }else{
                    console.log("else cityFilter");
                    if(stateFilter) {
                        console.log("stateFilter");
                        console.log(stateFilter);
                        let e_locationName = (e_location.name.toUpperCase()).trim();
                        let stateFilterLower = stateFilter;
                        if(!e_locationName.includes(stateFilterLower+' ')) {
                            console.log("if dentro do state filter");
                            console.log(!e_locationName.includes(stateFilterLower));
                            filterPass = false;
                        }
                    }                
                }
            }else{
                console.log("Else e_location");
                if(cityFilter || stateFilter) {
                    console.log("cityFilter || stateFilter");
                    filterPass = false;
                }
            }

            newEvent = newEvent.constructByObjects(e,  e_organizer ? employee.constructByObjects(e_organizer) : false,
            e_location ? location.constructByObjects(e_location) : false);
            newEvent.customFields = e_custom_fields;
            if(filterPass && e.status != 'rejected' && e.show == true){
                eventList.push(newEvent);
            }
           
        });

        console.log("Event List antes do order by");
        console.log(eventList);

        if(orderBy){
            switch(orderBy){
                case 'instrutor':
                    eventList = eventList.sort(this.dynamicSort("_instrutor"));
                break;
                case 'local':
                    eventList = eventList.sort(this.dynamicSort("_name"));
                case 'data':
                    eventList = eventList.sort(this.dynamicSort("_start"));
            }
        }
        console.log(eventList);
        return eventList;
        
        
    }

    orderBy = function(eventList, orderBy) {
        if(orderBy){
            switch(orderBy){
                case 'instrutor':
                    eventList = eventList.sort(this.dynamicSort("_instrutor"));
                break;
                case 'local':
                    eventList = eventList.sort(this.dynamicSort("_name"));
                    break;
                case 'data':
                    eventList = eventList.sort(this.dynamicSort("_start"));
                    break;
            }
        }
        return eventList;
    }

}