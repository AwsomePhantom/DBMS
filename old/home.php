

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1">
    <title>Page 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        body {
            padding: 5px 5px 5px 5px;
            background-image: url('https://media.istockphoto.com/id/1176654815/vector/car-service-seamless-pattern-with-thin-line-icons.webp?s=2048x2048&w=is&k=20&c=FuDZ99Xq1YAFqrYdZqcljinbCjMi9GrXQKti6Z4qtFM=');
            background-size: cover;
        }
        .nav-link {
            color: white;
        }
        .border {
            border-width: 10px !important;
        }
    </style>
</head>
<body>
<!--
Two ways, use nav attribute or ul li list
justify-content-center -> to center the menu
justify-content-end -> to justify to the right
flex-column -> to stack vertically
nav-pills -> to show menu items as buttons or pills
nav-full -> to fill the navbar
nav-tabs -> to show menu items as tabs
see bootstrap tab guide with jquery animation

mb-4 means bottom margin 4
px-4 means padding x axis 4
units from auto, 0 to 5, units multiplies * 0.25
see at https://getbootstrap.com/docs/4.0/utilities/spacing/

-->
<div class="m-4">
    <nav class="shadow border border-white navbar navbar-expand-lg navbar-dark text-white bg-dark rounded-4">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">Car repair system</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#topMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="topMenu">
                <div class="navbar-nav">
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#" class="nav-item nav-link">Profile</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Options</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">Account settings</a>
                            <a href="#" class="dropdown-item">List Car Models</a>
                            <a href="#" class="dropdown-item">Preferences</a>
                        </div>
                    </div>
                </div>
                <form class="d-flex ms-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <button type="button" class="btn btn-secondary"><i class="bi-search"></i></button>
                    </div>
                </form>
                <div class="navbar-nav">
                    <a href="#" class="nav-item nav-link">Register</a>
                    <a href="#" class="nav-item nav-link">Login</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="container-lg my-4 px-3">

    <div class="shadow-lg border border-white p-5 mb-4 bg-dark text-white rounded-3">
        <h1>Online Car Repair System</h1>
        <!-- lead makes standout the paragraph from other paragraphs -->
        <p class="lead">
            It is a comprehensive online platform designed to provide efficient and convenient solutions for
            individuals facing mechanical issues with their vehicles written in PHP and JavaScript that uses
            MYSQL database to store and handle user data and administration tasks. This system aims to
            bridge the gap between distressed vehicle owners and nearby car and bike repair shops by
            leveraging technology and offering a range of services to address various repair needs. The
            HTML skeleton with appropriate CSS files and Bootstrap is to give style to the platform that are
            prepared for daily random vehicle owner or driver and vehicle repair shop owner
        </p>
        <p><a href="#" target="_blank" class="btn btn-light btn-lg">Start using the service</a></p>
    </div>

    <div class="shadow-lg border border-black-secondary container-fluid bg-white rounded-3 p-5">
        <p>
            <strong>Objective</strong><br>
            The primary objective of the Online Car Repair System is to offer prompt and reliable assistance
            to users experiencing vehicle breakdowns or mechanical issues while on the road. By utilizing
            advanced technology and providing a user-friendly interface, the platform aims to streamline
            the process of connecting users with nearby repair shops and facilitating seamless
            communication and service delivery.
        </p>
        <p>
            <strong>Key Features of the System:</strong>
            <ul>
            <li>User accounts authentication</li>
            <li>Location-Based Assistance</li>
            <li>Communication and Coordination</li>
            <li>Repair Services</li>
            <li>Parts and Components Procurement</li>
            <li>Fuel Delivery Service</li>
            <li>Billing and Payment</li>
            <li>Review and Feedback System</li>
            </ul>
        </p>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>
