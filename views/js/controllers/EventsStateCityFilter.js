class EventsStateCityFilter {
    constructor(ajaxurl, eventsList){
        this._ajaxurl = ajaxurl;
        // this._baseurl = baseurl;
        this._eventsList = eventsList;
        this._listStateCity = [];
        this.states = [];
        this.cities = [];
        this.getStateAndCity();
    }

    getStateAndCity =  async() => {
        let locList = [];
        let listStateCity = [];

        console.log("Events List");
        console.log(this._eventsList);

        let states = [];
        let cities = [];

        this._eventsList.forEach(element => {
            console.log(element._local);
            locList.push(element._local);
        });

        console.log("Loc list");
        console.log(locList);

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