<x-layout>
    <div class="container">
        <div class="row justify-content-center height-custom align-items-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center fw-bold">{{ __('ui.register') }}</h3>
                        <form action="/register" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ __('ui.name') }}</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('ui.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">{{ __('ui.register') }}</button>
                        </form>
                        <p class="text-center mt-3">
                            {{ __('ui.have_account') }} <a href="/login">{{ __('ui.login') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
