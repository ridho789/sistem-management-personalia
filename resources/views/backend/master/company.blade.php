@extends('frontend.layout.main')
<!-- @section('title', 'Company') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Company</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Company</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new company -->
            <div class="col-12" id="form-new-company" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Company</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('company-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label for="name-company">Name Company</label>
                                        <input type="text" id="name-company" class="form-control input-new-name-company" name="nama_perusahaan" placeholder="input new name company" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address-company">Address Company</label>
                                        <input type="text" id="alamat-company" class="form-control input-new-address-company" name="alamat_perusahaan" placeholder="input new address company" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary submit-company" id="close-form-new-company">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit company -->
            <div class="col-12" id="form-edit-company" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit company</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-company" action="{{ url('company-update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id-company" name="id_perusahaan">
                                    <div class="mb-3">
                                        <label for="edit-name-company">Name Company</label>
                                        <input type="text" id="edit-name-company" class="form-control input-edit-name-company" name="nama_perusahaan" placeholder="input edit name company" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-address-company">Address Company</label>
                                        <input type="text" id="edit-address-company" class="form-control input-edit-address-company" name="alamat_perusahaan" placeholder="input edit address company" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary submit-company" id="close-form-edit-company">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">company</h4>
                        @if (count($tbl_perusahaan) > 0)
                            <button type="submit" class="btn btn-primary" id="new-company">+ Add new company</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_perusahaan) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-company" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Company</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_perusahaan as $c)
                                        <tr data-id="{{$c->id_perusahaan}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="company-name-selected">{{$c->nama_perusahaan}}</td>
                                            <td class="company-address-selected" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 225px;">{{$c->alamat_perusahaan}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="fa fa-edit"></i></a>
                                                <!-- <a href="company-delete/{{$c->id_perusahaan}}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-3">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <button type="submit" class="btn btn-light mt-2" id="new-company">click to add new company</button>
                                    </p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // script to show/hide form add new company
        const toggleFormButton = document.getElementById('new-company');
        const myForm = document.getElementById('form-new-company');

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
        const toggleFormEditButton = document.getElementById('edit-button');
        const myEditForm = document.getElementById('form-edit-company');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var companyName = row.querySelector(".company-name-selected").textContent;
                var companyAddress = row.querySelector(".company-address-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-id-company").value = id;
                document.getElementById("edit-name-company").value = companyName;
                document.getElementById("edit-address-company").value = companyAddress;

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