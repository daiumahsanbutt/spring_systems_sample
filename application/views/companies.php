<main class="container">
    <div class="bg-light p-5 rounded">
        <div class="row">
            <div class="col-8">
                <h1>Companies</h1>                
            </div>
            <div class="col-4 text-end">
                <button class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#companyModal">Add Company</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="companies_table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Number of Employees</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</main>


<!-- Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id = "companyForm" onsubmit = "return false;">
                    <div class="form-floating">
                        <select class="form-control" required name = "industry">
                          <option value="">Select Industry</option>
                          <?php
                            foreach($industries as $industry){
                                echo '<option value = "' . $industry['id'] . '">' . $industry['name'] . '</option>';
                            }
                          ?>
                        </select>
                        <label>Industry*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "company_name" placeholder="Company Name">
                        <label>Company Name*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "street_1" placeholder="Street Address 1">
                        <label>Street Address 1*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" name = "street_2" placeholder="Street Address 2">
                        <label>Street Address 2</label>
                    </div>
                    <div class="form-floating">
                        <select class="form-control add_state" required name = "state">
                            <option>Select State</option>
                            <?php
                            foreach($states as $state){
                                echo '<option value = "' . $state['id'] . '">' . $state['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <label>State*</label>
                    </div>
                    <div class="form-floating">
                        <select class="form-control add_city" required name = "city">
                            <option>Select State First</option>
                        </select>
                        <label>City*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "zip" placeholder="Zip Code">
                        <label>Zip Code*</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" required name = "phone" placeholder="Phone Number">
                        <label>Phone Number*</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" name = "email_address" placeholder="Email Address">
                        <label>Email Adress</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id = "add_company_button" form = "companyForm" class="btn btn-primary">Add Company</button>
            </div>
        </div>
    </div>
</div>