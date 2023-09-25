@extends('frontend.layout.main')
<!-- @section('title', 'Dashboard') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Division</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Division</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new division -->
            <div class="col-12" id="form-new-division" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Division</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('division-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" id="name-division" class="form-control input-new-division" name="input_divisi" placeholder="input new division" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-division" id="close-form-new-division">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit division -->
            <!-- <div class="col-12" id="form-edit-division" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Division</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-division" action="{{ url('division-edit') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id" name="id_divisi">
                                    <input type="text" id="edit-division" class="form-control input-edit-division" name="value_divisi" placeholder="input edit division" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-division" id="close-form-edit-division">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Division</h4>
                        @if (count($tbl_divisi) > 0)
                            <button type="submit" class="btn btn-primary mt-3" id="new-division">+ Add new divisi</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_divisi) > 0)
                            <div class="table-responsive">
                                <table id="data-table-division" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Division</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_divisi as $d)
                                        <tr>
                                            <td contenteditable="true">{{$d->nama_divisi}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" data-id="{{$d->id_divisi}}"><i class="fa fa-edit"> edit |</i></a>
                                                <a href="division-delete/{{$d->id_divisi}}"><i class="fa fa-trash"> delete </i></a>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="mt-5">
                                <span style="text-align: center;">
                                    <p>Sorry, no data that can be displayed yet. <br>
                                        <button type="submit" class="btn btn-light mt-2" id="new-division">click to add new Division</button>
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
        // script to show/hide form add new division
        const toggleFormButton = document.getElementById('new-division');
        const toggleCloseFormButton = document.getElementById('close-form-new-division');
        const myForm = document.getElementById('form-new-division');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }
        });

        toggleCloseFormButton.addEventListener('click', function() {
            var nameDivisionInput = document.getElementById("name-division");
            var nameDivisionValue = nameDivisionInput.value;
            if (nameDivisionValue){
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            }
        });

        // script to show/hide edit form
        // const toggleFormEditButton = document.getElementById('edit-button');
        // const toggleCloseFormEditButton = document.getElementById('close-form-edit-division');
        // const myEditForm = document.getElementById('form-edit-division');

        // toggleFormEditButton.addEventListener('click', function() {
        //     var id = this.getAttribute("data-id");
        //     var namaDivisi = this.parentNode.parentNode.querySelector("td:first-child").textContent;

        //     document.getElementById("edit-id").value = id;
        //     document.getElementById("edit-division").value = namaDivisi;

        //     if (myEditForm.style.display === 'none') {
        //         myEditForm.style.display = 'block';
        //     }
        // });

        // toggleCloseFormEditButton.addEventListener('click', function() {
        //     if (myEditForm.style.display === 'block') {
        //         myEditForm.style.display = 'none';
        //     }
        // });
    </script>
@endsection