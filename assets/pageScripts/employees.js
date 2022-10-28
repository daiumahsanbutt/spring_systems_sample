async function runEmployees(){
    $('#employees_table').dataTable().fnDestroy();	
    $("#employees_table").DataTable({
        ajax: {
            url: "./employees/get-all-employees",
            type: "POST",            
        },
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        responsive: true,
        pageLength: 10,
        ordering: true,
        order: [[0, 'asc']],
        pageResize: true,
        scrollY: '45vh',
        scrollCollapse: false,
        language: {
            searchPlaceholder: "Search By Name",
        },        
        columns: [
            { data: "company_name"},
            { data: "first_name"},
            { data: "last_name"},
            { data: "phone"},
            { data: "email"},
            {
                data:  function (data, type, dataToSet) {
                    return "$" + numberWithCommas(parseFloat(data.salary).toFixed(2));
                },
            }, 
        ],
    });
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
runEmployees();