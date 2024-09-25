<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" id="bootstrap-css">
  <link href="{{asset("css/app.css")}}" rel="stylesheet">
  <link href="{{asset("fontawesome/css/all.min.css")}}" rel="stylesheet">
</head>

<body class="bg-light">
  <header class="border-bottom">
    <nav class="navbar navbar-expand-sm navbar-light p-3">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class=" collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ms-auto ">
            <li class="nav-item">
              <a class="nav-link mx-2 active" aria-current="page" href="#">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-2" href="#">Pagamentos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-2" href="#">Recebimentos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-2" href="#"><i class="fa-solid fa-gear"></i></a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto d-none d-lg-inline-flex">
            <li class="nav-item mx-2">
              <a class="nav-link text-dark h5" href="" target="blank"></a>
            </li>
            <li class="nav-item mx-2">
              <a class="nav-link text-dark h5" href="" target="blank"></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

  </header>
  <script src="{{asset("js/bootstrap.min.js")}}"></script>

</body>

</html>