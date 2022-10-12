class EventsStateCityFilter {
    constructor(ajaxurl){
        // , employeesList, userInfos
        this._ajaxurl = ajaxurl;
        // this._baseurl = baseurl;
        // this._listStateCity = this.getStateAndCity(employeesList, userInfos);
        this.states = [];
        this.cities = [];
    }

    getStateAndCity =  async (employeesList, userInfos) => {
        let locList = [];
        let listStateCity = [];

        employeesList.forEach(empElement => {
            let user = userInfos.find(user => user.email == empElement.email);
            locList.push(...user.otherPlaces);
        });
        locList = locList.filter(value => value != "");

        // console.log("Loc list");
        // console.log(locList);

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

        // console.log("states");
        // console.log(states);

        // console.log("cities");
        // console.log(cities);

        // this.states = states;
        // this.cities = cities;

        // console.log("this states");
        // console.log(this._states);

        // console.log("this cities");
        // console.log(this._cities);
        
        return [];        
    }

}