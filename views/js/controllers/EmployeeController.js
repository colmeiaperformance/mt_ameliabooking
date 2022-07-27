class EmployeeController {
    constructor(ajaxUrl, baseUrl, container, employee = new Employee()) {
        this._container = container;
        this._ajaxUrl = ajaxUrl;
        this._baseUrl = baseUrl;
        this._employee = employee ? employee : new Employee()
        this._listView = new EmployeeSlideItem(container, this, baseUrl);
        this._view = new EmployeeView(container, this, baseUrl);
    }

    renderItems(employeeList) {
        this._listView.update(employeeList);
    }

    render(employee){
        console.log(employee);
        this._view.update(employee);
    }

    dynamicSort(property) {
        var sortOrder = 1;
        if(property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }
        return function (a,b) {

                var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
                return result * sortOrder;
            
        }
    }

    list = async (orderBy = false, stateFilter = false, cityFilter = false, userInfo = false, currentName = false) => {
        let entities_consult = await axios.get(`${this._ajaxUrl}/?action=wpamelia_api&call=/entities&types[]=employees&types[]=locations`);
        let employeeList = [];
        if(entities_consult.status == 200){
            let locations = entities_consult.data.data.locations;
            let entities = entities_consult.data.data.employees;
            entities.forEach((e) => {
                let filterPass = true;
                let employeeItem = new Employee();
                let e_location = locations.find(loc => loc.id == e.locationId);
                let location = new Location();
                let otherLocations = [];
                let addressLine = "";
                if(userInfo){
                    let info = userInfo.filter(u => e.email == u.email ? u.otherPlaces : false)[0];
                    if(info){
                        console.log(info)
                        otherLocations = info.otherPlaces;
                        if(info.addressLine)
                            addressLine = info.addressLine
                    }
                }


                console.log("otherLocations:> ")
                console.log(otherLocations)

                console.log("e_location:>")
                console.log(e_location)


                let filterLocations = []
                if(otherLocations.length > 0 || e_location.name){
                    if(otherLocations.length > 0){
                        otherLocations.forEach((element) => {
                            if(element != ""){
                                filterLocations.push(element)
                            }
                        })

                        let pass = false;
                        let locationComparison = e_location.name;
                        locationComparison = locationComparison.normalize("NFD").replace(/[^a-zA-Z\s]/g, "");
                        locationComparison = locationComparison.toLowerCase();
                        locationComparison = locationComparison.replace(" ", "");

                        let locationComparisonArray = locationComparison.split("-");

                        filterLocations.forEach((element) => {
                            let elemento = element.normalize("NFD").replace(/[^a-zA-Z\s]/g, "");
                            elemento = elemento.toLowerCase();

                            if(elemento.includes(locationComparisonArray[0]) && elemento.includes(locationComparisonArray[1])){
                                pass = true;
                            }

                        })

                        if(!pass){
                            let separateArray = e_location.name.replace(" ", "").split("-");
                            filterLocations.push(`${separateArray[1]} /- ${separateArray[0]}`)
                        }
                    }
                }


                let otherLocationsPass = false;
                
                if(filterLocations.length > 0){
                    
                    if(filterLocations.length > 0){
                        let city = '';
                        let state = '';

                        if(cityFilter){
                            city = cityFilter.normalize("NFD").replace(/[^a-zA-Z\s]/g, "");
                            city = city.toLowerCase();
                        }

                        if(stateFilter){
                            state = stateFilter.normalize("NFD").replace(/[^a-zA-Z\s]/g, "");
                            state = state.toLowerCase();
                        }

                        filterLocations.forEach((element) => {
                            let elemento = element.normalize("NFD").replace(/[^a-zA-Z\s]/g, "");
                            elemento = elemento.toLowerCase();

                            if(element != ""){
                                if(city && state){
                                    if(elemento.includes(city) && elemento.includes(state)){
                                        otherLocationsPass = true;
                                    }
                                }else if(state){
                                    if(elemento.includes(state)){
                                        otherLocationsPass = true;
                                    }
                                }else{
                                    otherLocationsPass = true;
                                }
                            }
                        })
                    }

                }else{
                    if(!cityFilter && !stateFilter){
                        otherLocationsPass = true;
                    }
                }

                e.otherLocations = filterLocations;
                e.addressLine = addressLine;

                employeeItem.constructByObjects(e, e_location ? location.constructByObjects(e_location) : false);
                
                if(otherLocationsPass){
                    employeeList.push(employeeItem);
                }

                //FilterByName
                if(currentName != " " && currentName){
                    employeeList = employeeList.filter(e => e.firstName.toLowerCase().includes(currentName.toLowerCase()) || e.lastName.toLowerCase().includes(currentName.toLowerCase()));
                }

                if(orderBy){
                    switch(orderBy){
                        case 'instrutor':
                            employeeList = employeeList.sort(this.dynamicSort("_instrutor"));
                        break;
                        case 'local':
                            employeeList = employeeList.sort(this.dynamicSort("_local"));
                    }
                }
            });
            return employeeList;
        }   
        return false;
    }


    
    orderBy = function(eventList, orderBy) {
        if(orderBy){
            switch(orderBy){
                case 'instrutor':
                    eventList = eventList.sort(this.dynamicSort("_instrutor"));
                break;
                case 'local':
                    eventList = eventList.sort(this.dynamicSort("_local"));
            }
        }
        return eventList;
    }


}