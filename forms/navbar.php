<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php echo file_get_contents('forms_bootstrap.html') ?>
    <style>
        body {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<!--
    To create a navigation bar:
    - Create a nav with class navbar, to set the collapsing limit use navbar-expand-*
    - Add a container-fluid to inline the menu
    - Add a button toggler with class navbar-toggler (old: toggle), data-bs-toggle=collapse(class) and data-bs-target=#id (with #)
    - Add a div for the collapsing region class collapse navbar-collapse and the id as the target
    - Add ul with class navbar-nav
    - Add li with class nav-item
    - Add a with class nav-link and active or disabled
-->

<!-- Change expand-* for the correct width -->
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><span class="bi-car-front-fill"></span> Logo</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsing-area">
            <span class="bi-menu-button-wide-fill"></span>
        </button>

        <div id="collapsing-area" class="collapse navbar-collapse">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="#" class="nav-link active">Home</a> </li>
                <li class="nav-item"><a href="#" class="nav-link">Section 1</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Section 2</a></li>
            </ul>

            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <div class="vr mx-2"></div>

            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Register</a></li>
                <li class="nav-item"><a class="btn btn-outline-primary" href="#">Login</a></li>
            </ul>
        </div>
    </div>
</nav>



</body>
</html>