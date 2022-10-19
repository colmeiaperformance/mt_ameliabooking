class State{
    constructor(stateCityFilter = [], id = "", nome = "", sigla = ""){
        this._id = id;
        this._nome = nome;
        this._sigla = sigla;
        this._stateCityFilter = stateCityFilter;
    }

    constructByResponse = (responseObj) => {
        Object.keys(this).forEach((i)=>{
            this[i] = responseObj[i.replace('_','')];
        });
        return this;
    }

    list = async() => {
        let states = await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
        let stateList = [];
        if(states.status == 200){
            states.data.forEach(e => {
                let newState = new State();
                newState.constructByResponse(e);
                stateList.push(
                    newState
                );
            });

            console.log("state list 28");
            console.log(this._stateCityFilter);

           
            stateList = stateList.filter(value => {
                console.log("value filter");
                console.log(value);

                // let result = false;
                // this._stateCityFilter.states.forEach(element => {
                //     if(element == value._sigla) { 
                //         result = true;
                //     }
                // });
                
                // return result;
            })
            

            // stateList = stateList.filter(value => {
            //     let result = false;
            //     this._stateCityFilter.states.forEach(element => {
            //         if(element == value._sigla) { 
            //             result = true;
            //         }
            //     });
                
            //     return result;
            // })

            return stateList;
        }   
        return false;
    }

    get id(){
        return this._id;
    }
    get nome(){
        return this._nome;
    }
    get sigla(){
        return this._sigla;
    }

    set id(id){
        this._id = id;
    }
    set nome(nome){
        this._nome = nome;
    }
    set sigla(sigla){
        this._sigla = sigla;
    }

}