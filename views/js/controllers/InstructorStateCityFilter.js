class InstructorStateCityFilter {
    constructor(ajaxurl, employeesList, userInfos){
        this._ajaxurl = ajaxurl;
        // this._baseurl = baseurl;
        this._employeesList = employeesList;
        this._userInfos = userInfos;
        this._listStateCity = [];
        this.states = [];
        this.cities = [];
        this.getStateAndCity();
    }

    getStateAndCity =  async() => {
        let locList = [];
        let listStateCity = [];

        this._employeesList.forEach(empElement => {
            let user = this._userInfos.find(user => user.email == empElement.email);

            if(user && user.hasOwnProperty('otherPlaces') && user.otherPlaces.length > 0){
                locList.push(...user.otherPlaces);
            }
        });
        locList = locList.filter(value => value != "");

        console.log("Loc list");
        console.log(locList);

        let states = [];
        let cities = [];

        locList.forEach(element => {
            let temp = element.split("-");
            
            temp[0] = temp[0].trim();
            temp[1] = temp[1].trim();

            
            if(temp[0].length == 2) {
                temp[0] = temp[0].toUpperCase();
                temp[1] = temp[1];

                if(states.indexOf(temp[0]) == -1){
                    states.push(temp[0]);

                    let index = states.indexOf(temp[0]);

                    cities[index] = [temp[1]];
                }else{
                    let index = states.indexOf(temp[0]);

                    if(cities[index] && cities[index].indexOf(temp[1]) == -1){
                        cities[index].push(temp[1]);
                    }
                }
            }else{
                temp[1] = temp[1].toUpperCase();
                temp[0] = temp[0];

                if(states.indexOf(temp[1]) == -1){
                    states.push(temp[1]);
                    let index = states.indexOf(temp[1]);


                    cities[index] = [temp[0]];
                }else{
                    let index = states.indexOf(temp[1]);

                    if(cities[index] && cities[index].indexOf(temp[0]) == -1){
                        cities[index].push(temp[0]);
                    }
                }
            }

        });
        this.states = states;
        this.cities = cities;        
    }

}