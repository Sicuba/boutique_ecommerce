@extends('layouts.light.master')

@section('content')
<div class="container">
    <div style="width: 6em; height: 6em;"></div>
</div>
<div class="container" style="border-radius: 2rem; padding: 2rem; background-color: #f3f4f6; box-shadow:  20px 20px 76px #bebebe,
             -20px -20px 76px #ffffff;">
    <div class="row justify-content-center">
        <div class="col-md-8">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary pr-4 pl-4">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
    </div>
    </div>

</div>
@endsection
@isset($data)

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            const dados = @json($data);
                // Dados a serem enviados
                const data = {
                    email: dados.email,
                    password: dados.password
                };

                const routes = {
                    homeRoute: "{{ route('home') }}",
                 };

                // Fazer uma requisição usando fetch
                 fetch('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na solicitação');
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (result.status == 0) {
                            // Exibir alerta de erro com SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: result.message
                            });
                        } else {
                            // Se a requisição for sucesso
                            // Exibir alerta de sucesso com SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                text: 'Login efetuado com sucesso!'
                            });

                            // Envio dos dados retornados para a controller
                            console.log('Sucesso:', result.data);

                             fetch('/savesession',{
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                     'X-CSRF-TOKEN': dados._token
                                },
                                body: JSON.stringify(result.data)
                            })
                            .then(res=> res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    const redirectUrl = routes.homeRoute; // URL da rota do Laravel
                                    window.location.href = redirectUrl;
                                } else {
                                    console.error('Erro:', data.message);
                                }
                            })

                     }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro na Solicitação',
                        });
                        console.error('Erro:', error);
                    });
        </script>
    @endsection

@endisset
