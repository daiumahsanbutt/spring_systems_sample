
$(document).on("change", ".add_state", function(){
    var selected_state = $(this).val();
    $(".add_city").html("<option>Select State First</option>");
    $.ajax({
        type:'POST',
        url: "./companies/get-cities",
        data: {state: selected_state},
        success: function(data)
        {   
            var cities = JSON.parse(data);
            var city_html = "";
            for(var i = 0; i < cities.length; i++){
                city_html += '<option value = "' + cities[i].id + '">' + cities[i].name + '</option>';
            }
            if(city_html != ""){
                $(".add_city").html(city_html);
            }            
        }
    });
})


$(document).on('submit','form#companyForm', function(){
    var formData = $("#companyForm").serialize();
    $("#add_company_button").html('Please Wait...');
    $("#add_company_button").attr('disabled','disabled');          
    $.ajax({
        type:'POST',
        url: "./companies/add",
        data: formData,
        success: function(data)
        {
            $("#add_company_button").html('Add Company');
            $("#add_company_button").removeAttr('disabled');
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
                $('#companyForm').trigger("reset");
                $('#companyModal').modal('hide');
                $('#companies_table').DataTable().ajax.reload(null, false);
            }             
        }
    });
});


function notificationMessage(message, type, duration = '5000', positionX='right', positionY='top'){
    $.notify(message, type);
}

async function runCompanies(){
    $('#companies_table').dataTable().fnDestroy();	
    $("#companies_table").DataTable({
        ajax: {
            url: "./companies/get-companies",
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
            { data: "state_name"},
            { data: "city_name"},
            { data: "total_employees"},
            {
                orderable: false, data:  function (data, type, dataToSet) {
                    return '<a href = "./companies/' + data.company_udid + '"><button class = "btn btn-primary">View Company</button></a>';
                },
            }, 
        ],
    });
}

runCompanies();