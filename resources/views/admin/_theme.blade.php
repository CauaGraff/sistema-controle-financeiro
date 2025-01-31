<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Gerenciamento - @yield("title")</title>
  <link rel="shortcut icon" href="{{asset("imgs/icon.png")}}" type="image/x-icon">
  <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
  <link href="{{asset('css/app.css')}}" rel="stylesheet">
  <link href="{{asset('fontawesome/css/all.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}" />
  @yield("css")
</head>

<body class="bg-light">
  <header class="border-bottom" style="background-color: #fff;">
    <nav class="navbar navbar-expand-lg navbar-light p-3">
      <div class="container-fluid">
        <img src="{{asset("imgs/banner.png")}}" alt="Logo" style="width: 140px;">
        <!-- Botão "hambúrguer" para telas menores -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link mx-2 active" aria-current="page" href="{{route('home.adm')}}">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Usuarios
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuariosDropdown">
                <li><a class="dropdown-item" href="{{route('adm.usuarios', ['escritorio'])}}">Escritorio</a></li>
                <li><a class="dropdown-item" href="{{route('adm.usuarios', ['cliente'])}}">Clientes</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link mx-2" href="{{route('adm.empresas')}}">Empresas</a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto d-lg-inline-flex">
            <li class="nav-item mx-2">
              <span class="nav-link">{{auth()->user()->name}}</span>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa-solid fa-user"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end w-auto" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{route("login.destroy")}}"><i
                      class="fa-solid fa-right-from-bracket"></i> Sair</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main class="mt-5">
    <section class="container">
      @yield('content')
    </section>
  </main>

  <!-- Scripts do Bootstrap -->
  <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
  <!-- Remover o jQuery, pois não é necessário com Bootstrap 5 -->
  <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
  <script src="{{asset('js/toastr.min.js')}}"></script>
  <script>
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "3000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    @if(Session::has('alert-success'))
    toastr.success("{{ Session::get('alert-success') }} ")
  @elseif(Session::has('alert-warning'))
  toastr.warning("{{ Session::get('alert-warning') }} ")
@elseif(Session::has('alert-danger'))
  toastr.error("{{ Session::get('alert-danger') }} ")
@endif
  </script>

  @yield("js")
</body>

</html>