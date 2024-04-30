<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .card {
            margin: 15px;
            float: left;
            box-shadow: 2px 2px 10px gray;
        }
    </style>
</head>
<body>
<div class="container-lg p-2 float-sm-start">
    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-dark text-white" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>

    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-danger" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>

    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-secondary" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>

    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-info" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>
    <div id="sample"></div>
</div>

<script>
    let x = document.getElementById('sample');

    function test() {
        x.innerHTML += '<div class="card" style="width: 30ch">' +
            '<div class="card-img-top bg-light" style="height: 6vw">' +
            '<div class="card-title p-1 align-middle">Work type</div>' +
            '</div>' +
            '<div class="card-body">' +
            '<div class="card-title">Post Title</div>' +
            '<div class="card-subtitle mb-2 text-muted">Location</div>' +
            '<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>' +
            '<a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>' +
            '</div>' +
            '</div>';
    }
</script>

</body>
</html>
