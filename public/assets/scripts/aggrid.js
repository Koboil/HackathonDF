const gridOptions = {
    // Row Data: The data to be displayed.
    rowData: [
      { make: "Tesla", model: "Model Y", price: 64950, electric: true },
      { make: "Ford", model: "F-Series", price: 33850, electric: false },
      { make: "Toyota", model: "Corolla", price: 29600, electric: false },
    ],
    // Column Definitions: Defines the columns to be displayed.
    columnDefs: [
      { field: "Statuts", sortable: true, filter: true, headerCheckboxSelection: true},
      { field: "Type", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "Etat", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "téléphone", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "suivi sms", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "Nom", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "Prénom", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "Genre", sortable: true, filter: true, headerCheckboxSelection: true },
      { field: "Date de naissance", sortable: true, filter: true, headerCheckboxSelection: true },

    ]
   };

const myGridElement = document.querySelector('#myGrid');
agGrid.createGrid(myGridElement, gridOptions);