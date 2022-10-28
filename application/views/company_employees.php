<main class="container">
    <div class="bg-light p-5 rounded">
        <div class="row">
            <div class="col-8">
                <h1><?php echo $company_data['name'];?></h1>                
            </div>
            <div class="col-4 text-end">
                <button class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal">Add Employee</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="employees_table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Email Address</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</main>


<!-- Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id = "employeeForm" onsubmit = "return false;">
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "first_name" placeholder="First Name">
                        <label>First Name*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "last_name" placeholder="Last Name">
                        <label>Last Name*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "phone" placeholder="Phone Number">
                        <label>Phone Number*</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" name = "email_address" placeholder="Email Address">
                        <label>Email Adress</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" step = "0.01" class="form-control" required name = "salary" placeholder="Annual Salary">
                        <label>Annual Salary ($)*</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id = "add_employee_button" form = "employeeForm" class="btn btn-primary">Add Employee</button>
            </div>
        </div>
    </div>
</div>
<script>
    var company_udid = "<?php echo $company_data['company_udid'];?>";
</script>