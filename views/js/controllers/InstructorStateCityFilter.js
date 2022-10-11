class InstructorStateCityFilter {
    constructor(ajaxurl){
        this._ajaxurl = ajaxurl;
        // this._baseurl = baseurl;
        this._listStateCity = this.getStateAndCity;
    }

    getStateAndCity = async() => {
        let entities_consult = await axios.get(`${this._ajaxUrl}?action=wpamelia_api&call=/entities&types[]=locations&types[]=tags&types[]=custom_fields&types[]=employees`);    
        let entities = entities_consult.data.data;
        let customFields = entities_consult.data.data.customFields;

        console.log("Entities consult");
        console.log(entities_consult)

        console.log("Entities");
        console.log(entities);

        console.log("Custom Fields");
        console.log(customFields);

        return [];
        
    }

}