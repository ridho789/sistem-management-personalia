<!DOCTYPE html>
<html lang="en" class="h-100">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Register | Personalia</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}asset/images/favicon.png">
        <link href="{{ asset('') }}asset/css/style.css" rel="stylesheet">
    </head>

    <body class="h-100">
        <div class="authincation h-100">
            <div class="container-fluid h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-md-6">
                        <div class="authincation-content">
                            <div class="row no-gutters">
                                <div class="col-xl-12">
                                    <div class="auth-form">
                                        <h4 class="text-center mb-4">Register</h4>
                                        <form action="{{url( 'register-add' )}}" method="POST" id="form-register" >
                                            @csrf
                                            <div class="form-group">
                                                <label for="floatingText"><strong>Username</strong></label>
                                                <input type="text" name="name" class="form-control @error('name') 
                                                is-invalid @enderror" id="name" placeholder="" required>
                                                @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="floatingInput"><strong>Email</strong></label>
                                                <input type="email" class="form-control @error('email')
                                                is-invalid @enderror" name="email" id="email" placeholder="" value="{{ old('email')}}" required>
                                                @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="floatingPassword"><strong>Password</strong></label>
                                                <input type="password" name="password" class="form-control" id="Password" placeholder="" required>
                                                @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Passwordconfirm"><strong>Password Confirmation</strong></label>
                                                <input type="password" name="password_confirmation" class="form-control" id="ConfirmPassword" placeholder="" required>
                                                @error('password_confirmation')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <div id="passwordMismatch" class="invalid-feedback" style="display: none;">
                                                    Passwords do not match
                                                </div>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-primary btn-block">Sign me up</button>
                                            </div>
                                        </form>
                                        <div class="new-account mt-3">
                                            <p>Already have an account? <a class="text-primary" href="/login">Sign in</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            document.getElementById("form-register").addEventListener("submit", function (event) {
                var password = document.getElementById("Password").value;
                var confirmPassword = document.getElementById("ConfirmPassword").value;
                var passwordMismatch = document.getElementById("passwordMismatch");
        
                if (password !== confirmPassword) {
                    passwordMismatch.style.display = "block";
                    event.preventDefault(); // Mencegah pengiriman formulir jika password tidak cocok
                } else {
                    passwordMismatch.style.display = "none";
                }
            });
        </script>
        
        <!-- Required vendors -->
        <script src="{{ asset('') }}asset/vendor/global/global.min.js"></script>
        <script src="{{ asset('') }}asset/js/quixnav-init.js"></script>
        <script src="{{ asset('') }}asset/js/custom.min.js"></script>
        <!--endRemoveIf(production)-->
    </body>

</html>


  