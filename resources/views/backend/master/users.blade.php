@extends('frontend.layout.main')
<!-- @section('title', 'Users') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Users</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Users</a></li>
                </ol>
            </div>
        </div>

        <!-- row -->
        <div class="row">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form add users -->
            <div class="col-12" id="form-add-user" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-value-user" action="{{ url('users-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="form-control input-name @error('name') 
                                            is-invalid @enderror" 
                                            name="name" placeholder="input new name" required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" class="form-control input-email @error('email')
                                            is-invalid @enderror" 
                                            name="email" placeholder="input new email" required>
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control @error('password')
                                            is-invalid @enderror" id="password" 
                                            placeholder="input new password" required>
                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="level">Level</label>
                                        <select class="form-control input-level" name="level" id="level" required>
                                            <option value="">Please select level</option>
                                            <option value="1">Level 1</option>
                                            <option value="2">Level 2</option>
                                            <option value="3">Level 3</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary submit-employee-status" 
                                        id="close-form-employee-status">Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form edit users -->
            <div class="col-12" id="form-edit-user" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-user" action="{{ url('users-update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id" name="id">
                                    <div class="mb-3">
                                        <label for="edit-name">Name</label>
                                        <input type="text" id="edit-name" class="form-control input-edit-name" 
                                            name="val_name" placeholder="input new name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-email">Email</label>
                                        <input type="email" id="edit-email" class="form-control input-edit-email" 
                                            name="val_email" placeholder="input new email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="level">Level</label>
                                        <select class="form-control input-edit-level" name="val_level" id="edit-level" required>
                                            <option value="">Please select level</option>
                                            <option value="1">Level 1</option>
                                            <option value="2">Level 2</option>
                                            <option value="3">Level 3</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary submit-employee-status" 
                                        id="close-form-edit-employee-status">Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">                           
                        <h4 class="card-title">Users list</h4>
                        <button type="submit" class="btn btn-primary" id="new-user">+ Add new user</button>                       
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-sm" id="data-table-user" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Level</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $u)
                                        <tr data-id="{{$u->id}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="name-selected">{{$u->name}}</td>
                                            <td class="email-selected">{{$u->email}}</td>
                                            <td class="password-selected">{{$u->password}}</td>
                                            <td class="level-selected">{{$u->level}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="btn btn-secondary btn-sm edit-button mt-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <!-- <a href="{{ url('users-delete/' . $u->id) }}" id="delete-button" class="btn btn-dark btn-sm mt-1" 
                                                    onclick="return confirm('Are you sure you want to delete this data?');"><i class="fa fa-trash"></i>
                                                </a> -->
                                            </td>
                                        </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // script to show/hide form add new leave type
        const toggleFormButton = document.getElementById('new-user');
        const myForm = document.getElementById('form-add-user');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }

            // close form edit
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }
        });

        // script to show/hide edit form
        const myEditForm = document.getElementById('form-edit-user');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var nameUser = row.querySelector(".name-selected").textContent;
                var emailUser = row.querySelector(".email-selected").textContent;
                //var passwordUser = row.querySelector(".password-selected").textContent;
                var levelUser = row.querySelector(".level-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-name").value = nameUser;
                document.getElementById("edit-email").value = emailUser;
                //document.getElementById("edit-password").value = passwordUser;
                document.getElementById("edit-level").value = levelUser;

                if (myEditForm.style.display === 'none') {
                    myEditForm.style.display = 'block';
                }

                // close form add
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            });
        });
    </script>
@endsection