class InstructorStateCityFilter {
    constructor(ajaxurl, employeees){
        this._ajaxurl = ajaxurl;
        // this._baseurl = baseurl;
        this._listStateCity = this.getStateAndCity(employeees);
    }

    getStateAndCity =  employeees => {
        // console.log("user infos");
        // console.log(userInfos);
        let entities_consult = axios.get(`${this._ajaxUrl}/?action=wpamelia_api&call=/entities&types[]=employees&types[]=locations`);

        console.log("entities consult");
        console.log(entities_consult);

        console.log("employeees");
        console.log(employeees);

        employeees.forEach(element => {
            console.log("Element");
            console.log(element);
        });
        

        return [];
        
    }

}