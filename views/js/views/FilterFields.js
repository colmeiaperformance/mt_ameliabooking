class FilterFields extends View{
    constructor(elemento, scope, baseUrl){
    super(elemento, scope, baseUrl);
    }
    template(model,baseUrl, date = true){
    console.log(date);
    return(`
    <div class="mt_filter_options">
      <div class="mt_row">
        <div class="mt_filter select">
          <select id="stateFilter" onchange="changeState(this.value)" class="form-control">
            <option selected disabled>Estado</option>
            ${
            model.states.map((e) => {
            return(`
            <option value="${e.sigla}" ${model.selectedState == e.sigla ? "selected" : ""}>${e.nome}</option>
            `)
            }).join('')
            }
          </select>
        </div>
        <div class="mt_filter select">
          <select id="cityFilter" onchange="changeCity(this.value)" class="form-control">
            <option selected disabled>Cidade</option>
            ${
            model.cities.map((c) => {
            return(`
            <option value="${c.nome}">${c.nome}</option>
            `)
            }).join('')
            }
          </select>
        </div>
      </div>
      <div class="mt_row justify-content-start" style="display: none !important;">
        <div class="mt_filter">
          <input type="text" id="districtFilter" class="form-control" placeholder="Bairro" value="${model.district}" onKeyUp="filterByDistrict(value)">
        </div>
      </div>
      <div class="ms-auto me-0 d-flex justify-content-center justify-content-lg-end flex-nowrap container-fluid">
        <div class="mt_filter">
          <button id="removeFilterButton" onclick="removeFilters()" class="btn btn-remove">
          Remover Filtros
          </button>
        </div>
      </div>
      <div class="mt_row justify-content-start" id="orderBy">
        <div class="mt_filter col-sm-3">
          <select id="stateFilter" onchange="changeOrderBy(this.value)" class="form-control">
            <option selected disabled>Ordenar por</option>
            <option value="local">Local</option>
            ${
            date == true ? `
            <option value="data">Data</option>
            ` : ''
            }
            <option value="instrutor">Instrutor</option>
          </select>
        </div>
      </div>
    </div>
    `);
    }
}
