<!DOCTYPE html>
<html lang="{{env("APP_LOCALE")}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env("APP_NAME")}} Login</title>
    <link rel="shortcut icon" href="{{asset("imgs/icon.png")}}" type="image/x-icon">
    <link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" id="bootstrap-css">
    <link href="{{asset("css/app.css")}}" rel="stylesheet">
    <link href="{{asset("fontawesome/css/all.min.css")}}" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-lg-4 col-md-6 col-10">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4"><img src="{{asset("imgs/banner.png")}}" alt="Logo"
                                style="width: 100%;"></div>

                        <form action="{{ route('login.auth') }}" method="post">
                            @csrf
                            @error('error')
                                <p class="text-danger text-center">{{ $message }}</p>
                            @enderror
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control @error('email')is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha:</label>
                                <input type="password" class="form-control @error('password')is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset("js/bootstrap.min.js")}}"></script>
</body>

</html>