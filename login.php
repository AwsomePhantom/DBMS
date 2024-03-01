
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1">
    <title>Login page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
    .row {
        margin-top: 2vw;
    }
</style>
</head>
<body>
<!-- remove top margin when importing the page -->
<div class="card">
    <img src="" class="card-img-top bg-primary" style="height: 200px;" alt="Logo">
    <form class="card-body">
        <div class="form-group mb-5 p-5">
            <div class="row my-3">
                <div class="col">
                    <h1 class="card-title"><strong>Login</strong></h1>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input name="username" class="form-control" type="text" placeholder="Username">
                </div>
            </div>
            <div class="row">
                <div class="col col-sm">
                    <input name="password" class="form-control" type="password" placeholder="Password">
                </div>
            </div>
            <div class="row">
                <div class="col col-sm-4">

                </div>
                <div class="col-4">
                    <input name="login" type="submit" class="form-control btn btn-primary" value="Submit">
                </div>
                <div class="col-4">
                    <input type="reset" class="form-control" value="Clear">
                </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>
