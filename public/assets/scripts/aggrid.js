const gridOptions = {
    // Row Data: The data to be displayed.
    rowData: gridData,
    // Column Definitions: Defines the columns to be displayed.
    columnDefs: [
      { headerName:"Statuts",field: "bubble_status", sortable: true, filter: true, headerCheckboxSelection: true, cellRenderer: funct},
      { headerName:"Type", field: "type", sortable: true, filter: true, headerCheckboxSelection: true },
      { headerName:"Etat", field: "isActive", sortable: true, filter: true, headerCheckboxSelection: true, cellRenderer: actifronot},
      { headerName:"Téléphone", field: "phone_number", sortable: true, filter: true, headerCheckboxSelection: true},
      { headerName:"Suivi sms", field: "send_sms", sortable: true, filter: true, headerCheckboxSelection: true, cellRenderer: okornot},
      { headerName:"Nom", field: "surname", sortable: true, filter: true, headerCheckboxSelection: true },
      { headerName:"Prénom", field: "name", sortable: true, filter: true, headerCheckboxSelection: true },
      { headerName:"Genre", field: "gender", sortable: true, filter: true, headerCheckboxSelection: true },
    ]
   };
function funct(params) {
    return `<span class="petit-rond-${params.value}"></span>`
}
function actifronot(params) {
    if(params.value === true){
        return `<span >actif</span>`
    } else {
        return `<span >inactif</span>`
    }   

}
function okornot(params) {
    if(params.value === true){
        return `<span >ok</span>`
    } else {
        return `<span >ko</span>`
    }
}
const myGridElement = document.querySelector('#myGrid');
agGrid.createGrid(myGridElement, gridOptions);