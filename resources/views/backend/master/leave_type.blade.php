@extends('frontend.layout.main')
<!-- @section('title', 'Leave Type') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Leave Type</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Leave</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Leave Type</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new leave type -->
            <div class="col-12" id="form-new-type-leave" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New leave type</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('type-leave-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" id="name-type-leave" class="form-control input-new-type-leave" name="input_tipe_cuti" placeholder="input new leave type" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-type-leave" id="close-form-new-type-leave">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit leave type -->
            <div class="col-12" id="form-edit-type-leave" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit leave type</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-type-leave" action="{{ url('type-leave-update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id" name="id_tipe_cuti">
                                    <input type="text" id="edit-type-leave" class="form-control input-edit-type-leave" name="value_tipe_cuti" placeholder="input edit leave type" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-type-leave" id="close-form-edit-type-leave">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">leave type</h4>
                        @if (count($tbl_tipe_cuti) > 0)
                            <button type="submit" class="btn btn-primary" id="new-type-leave">+ Add leave type</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_tipe_cuti) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-type-leave" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>leave type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_tipe_cuti as $tc)
                                        <tr data-id="{{$tc->id_tipe_cuti}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="type-leave-name-selected">{{$tc->nama_tipe_cuti}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="fa fa-edit"></i></a>
                                                <!-- <a href="type-leave-delete/{{$tc->id_tipe_cuti}}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
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
                                        <button type="submit" class="btn btn-light mt-2" id="new-type-leave">click to add new leave type</button>
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
        // script to show/hide form add new leave type
        const toggleFormButton = document.getElementById('new-type-leave');
        const myForm = document.getElementById('form-new-type-leave');

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
        const myEditForm = document.getElementById('form-edit-type-leave');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var typeLeaveName = row.querySelector(".type-leave-name-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-type-leave").value = typeLeaveName;

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