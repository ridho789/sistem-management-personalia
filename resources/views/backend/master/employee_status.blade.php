@extends('frontend.layout.main')
<!-- @section('title', 'Employee Status') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Employee Status</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Employee Status</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new employee status -->
            <div class="col-12" id="form-new-employee-status" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New employee status</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('employee-status-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" id="name-employee-status" class="form-control input-new-employee-status" name="nama_status" placeholder="input new employee status" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-employee-status" id="close-form-new-employee-status">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit employee status -->
            <div class="col-12" id="form-edit-employee-status" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit employee status</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-employee-status" action="{{ url('employee-status-update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id" name="id_status">
                                    <input type="text" id="edit-employee-status" class="form-control input-edit-employee-status" name="nama_status" placeholder="input edit employee status" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-employee-status" id="close-form-edit-employee-status">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">employee status</h4>
                        @if (count($tbl_status_kary) > 0)
                            <button type="submit" class="btn btn-primary mt-3" id="new-employee-status">+ Add new employee status</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_status_kary) > 0)
                            <div class="table-responsive">
                                <table id="data-table-employee-status" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Employee Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_status_kary as $e)
                                        <tr data-id="{{$e->id_status}}">
                                            <td class="employee-status-name-selected">{{$e->nama_status}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="edit-button"><i class="fa fa-edit"> edit |</i></a>
                                                <a href="employee-status-delete/{{$e->id_status}}"><i class="fa fa-trash"> delete </i></a>
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
                                        <button type="submit" class="btn btn-light mt-2" id="new-employee-status">click to add new employee status</button>
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
        // script to show/hide form add new employee-status
        const toggleFormButton = document.getElementById('new-employee-status');
        const toggleCloseFormButton = document.getElementById('close-form-new-employee-status');
        const myForm = document.getElementById('form-new-employee-status');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }

            // close form edit
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }
        });

        toggleCloseFormButton.addEventListener('click', function() {
            var nameemployee_statusInput = document.getElementById("name-employee-status");
            var nameemployee_statusValue = nameemployee_statusInput.value;
            if (nameemployee_statusValue){
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            }
        });

        // script to show/hide edit form
        const toggleFormEditButton = document.getElementById('edit-button');
        const toggleCloseFormEditButton = document.getElementById('close-form-edit-employee-status');
        const myEditForm = document.getElementById('form-edit-employee-status');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var employee_statusName = row.querySelector(".employee-status-name-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-employee-status").value = employee_statusName;

                if (myEditForm.style.display === 'none') {
                    myEditForm.style.display = 'block';
                }

                // close form add
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            });
        });

        // close edit form
        toggleCloseFormEditButton.addEventListener('click', function() {
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }
        });
    </script>
@endsection