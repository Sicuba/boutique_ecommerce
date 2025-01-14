@extends('layouts.light.master')

@section('content')

<div style="padding: 30px; width: 100%; height: 100vh; display: flex; align-items: center; background-color: #fff">
    <div class="row justify-content-center">
        <div class="col-md-8">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="mobile" type="phone" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" required autocomplete="off">

                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <select class="form-control" aria-label="Default select example" style="margin:15px;" id="country" name="country_id">
                                <option selected>País</option>
                                @isset($countries)
                                @foreach ($countries as $country )
                                <option value="{{$country['id']}}">{{$country['name']}}</option>
                                @endforeach
                                @endisset

                              </select>
                        </div>
                        <div class="form-group row">
                            <select class="form-control" aria-label="Default select example" style="margin:15px;" id="state" name="state_id" disabled>
                                <option selected>Estado</option>
                              </select>
                        </div>
                        <div class="form-group row">
                            <select class="form-control" aria-label="Default select example" style="margin:15px;" id="city" name="city_id" disabled>
                                <option selected>Cidade</option>
                              </select>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary pl-4 pr-4">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

        document.getElementById('country').addEventListener('change', function () {
        const countryId = this.value;
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');

        stateSelect.innerHTML = '<option value="">Select a state</option>';
        citySelect.innerHTML = '<option value="">Select a city</option>';
        citySelect.disabled = true;

        if (countryId) {
            fetch(`/states/${countryId}`)
                .then(response => response.json())
                .then(states => {
                    stateSelect.disabled = false;
                    states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.id;
                        option.textContent = state.name;
                        stateSelect.appendChild(option);
                    });
                });
        } else {
            stateSelect.disabled = true;
        }
    });

    document.getElementById('state').addEventListener('change', function () {
        const stateId = this.value;
        const citySelect = document.getElementById('city');

        citySelect.innerHTML = '<option value="">Select a city</option>';

        if (stateId) {
            fetch(`/cities/${stateId}`)
                .then(response => response.json())
                .then(cities => {
                    citySelect.disabled = false;
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                });
        } else {
            citySelect.disabled = true;
        }
    });
        </script>
    @endsection
