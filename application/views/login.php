<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle;?></title>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo ASSETS_URL;?>notify.js"></script>
    <link rel="stylesheet" href="<?php echo ASSETS_URL;?>style.css">
</head>
<body>
    
<div class="container">
    <div class="row">
        <div class="col-12 text-center p-5">
            <form id = "loginForm" onsubmit = "return false;">
                <h1 class="h3 mb-3 fw-normal">Please Log In</h1>
                <div class="form-floating">
                    <input type="text" class="form-control" required name = "username" placeholder="Username">
                    <label>Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" required name = "password" placeholder="Password">
                    <label>Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" id = "login_user_button" type="submit">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<script>
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (originalOptions.data instanceof FormData) { 
            originalOptions.data.append("<?= $this->security->get_csrf_token_name(); ?>", "<?= $this->security->get_csrf_hash(); ?>"); 
        }
    });
    $.ajaxSetup({
        data: {
            <?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>",
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
</script>
<script src="<?php echo PAGE_SCRIPTS_URL;?>login.js"></script>
