$(document).on('submit','form#employeeForm', function(){
    var formData = $("#employeeForm").serialize();
    $("#add_employee_button").html('Please Wait...');
    $("#add_employee_button").attr('disabled','disabled');          
    $.ajax({
        type:'POST',
        url: "./" + company_udid + "/add_employee",
        data: formData,
        success: function(data)
        {
            $("#add_employee_button").html('Add Employee');
            $("#add_employee_button").removeAttr('disabled');
            var response = JSON.parse(data);
            if(response.status == 0){
                if(Object.keys(response.errors).length > 0){
                    for(var key in response.errors){
                        if(response.errors[key] != ""){
                            notificationMessage(response.errors[key], 'error');
                        }                            
                    }
                } else {
                    notificationMessage(response.error, 'error');
                }
            } else if(response.status == 1){
                notificationMessage(response.text, 'success');
                $('#employeeForm').trigger("reset");
                $('#employeeModal').modal('hide');
                $('#employees_table').DataTable().ajax.reload(null, false);
            }             
        }
    });
});


function notificationMessage(message, type, duration = '5000', positionX='right', positionY='top'){
    $.notify(message, type);
}

async function runEmployees(){
    $('#employees_table').dataTable().fnDestroy();	
    $("#employees_table").DataTable({
        ajax: {
            url: "./" + company_udid + "/get-company-employees",
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