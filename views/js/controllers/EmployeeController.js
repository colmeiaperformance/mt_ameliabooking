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

                e.otherLocations = otherLocations;
                e.addressLine = addressLine;

                // if(e_location.name){
                //     otherLocations.push(e_location.name)
                // }

                let otherLocationsPass = false;
                
                if(otherLocations.length > 0){
                    
                    if(otherLocations.length > 0){
                        otherLocations.forEach((element) => {
                            element = element.normalize("NFD");
                            if(element != ""){
                                if(cityFilter && stateFilter){
                                    if(element.toLowerCase().includes(cityFilter.normalize("NFD").toLowerCase()) && element.normalize("NFD").toLowerCase().includes(stateFilter.toLowerCase())){
                                        otherLocationsPass = true;
                                    }
                                }else if(stateFilter){
                                    if(element.toLowerCase().includes(stateFilter.normalize("NFD").toLowerCase())){
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