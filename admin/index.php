<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
        }

    </style>
</head>
<body class="bg-dark-subtle">
<nav class="navbar navbar-expand-md bg-info px-2">
    <a href="#" class="navbar-brand">Website</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-area" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapse-area">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link active">Home</a>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Create
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-item">customer Account</a></li>
                    <li><a href="#" class="dropdown-item">Business Account</a></li>
                    <li><a href="#" class="dropdown-item">User Account</a></li>

                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    List
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-item">Contacts</a></li>
                    <li><a href="#" class="dropdown-item">Address</a></li>
                    <li><a href="#" class="dropdown-item">customers</a></li>
                    <li><a href="#" class="dropdown-item">Businesses</a></li>
                    <li><a href="#" class="dropdown-item">Users</a></li>

                </ul>
            </li>
        </ul>
    </div>
</nav>
</body>
</html>