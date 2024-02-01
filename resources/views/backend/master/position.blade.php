@extends('frontend.layout.main')
<!-- @section('title', 'Position') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Position</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Position</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new position -->
            <div class="col-12" id="form-new-position" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New position</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('position-add') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="name-position">Position</label>
                                                <input type="text" id="name-position" class="form-control input-new-position" 
                                                name="input_jabatan" placeholder="input new position" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="position-allowance">Position Allowance</label>
                                                <input type="text" id="position-allowance" class="form-control input-new-position-allowance" 
                                                name="input_tunjangan_jabatan" placeholder="input new position allowance" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary submit-position" id="close-form-new-position">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit position -->
            <div class="col-12" id="form-edit-position" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit position</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-position" action="{{ url('position-update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12"> 
                                        <input type="hidden" id="edit-id" name="id_jabatan">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="name-position">Position</label>
                                                <input type="text" id="edit-position" class="form-control input-edit-position" 
                                                name="value_jabatan" placeholder="input edit position" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label for="position-allowance">Position Allowance</label>
                                                <input type="text" id="edit-position-allowance" class="form-control input-edit-position-allowance" 
                                                name="value_tunjangan_jabatan" placeholder="input edit position allowance" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary submit-position" id="close-form-edit-position">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">position</h4>
                        @if (count($tbl_jabatan) > 0)
                            <button type="submit" class="btn btn-primary" id="new-position">+ Add new position</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_jabatan) > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-sm" id="data-table-position" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Position</th>
                                            <th>Position Allowance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_jabatan as $j)
                                        <tr data-id="{{$j->id_jabatan}}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="position-name-selected">{{$j->nama_jabatan}}</td>
                                            <td class="position-allowance-selected">{{$j->tunjangan_jabatan}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="btn btn-secondary btn-sm edit-button"><i class="fa fa-edit"></i></a>
                                                <!-- <a href="{{ url('position-delete/' . $j->id_jabatan)  }}" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i></a> -->
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
                                        <button type="submit" class="btn btn-light mt-2" id="new-position">click to add new position</button>
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
        // script to show/hide form add new position
        const toggleFormButton = document.getElementById('new-position');
        const myForm = document.getElementById('form-new-position');

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
        const myEditForm = document.getElementById('form-edit-position');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                
                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var positionName = row.querySelector(".position-name-selected").textContent;
                var positionAllowance = row.querySelector(".position-allowance-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-position").value = positionName;
                document.getElementById("edit-position-allowance").value = positionAllowance;

                if (myEditForm.style.display === 'none') {
                    myEditForm.style.display = 'block';
                }

                // close form add
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            });
        });

        // function formatToIDR(amount) {
        //     return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
        // }

        // function updateIDRFormat(inputElement) {
        //     const value = inputElement.value;
        //     const numericValue = parseFloat(value.replace(/[^\d.-]/g, ''));

        //     if (!isNaN(numericValue)) {
        //         inputElement.value = formatToIDR(numericValue);
        //     }
        // }

        // const inputSelectors = [
        //     '.form-control.input-new-position-allowance',
        //     '.form-control.input-edit-position-allowance'
        // ];

        // inputSelectors.forEach(function (selector) {
        //     const inputElements = document.querySelectorAll(selector);
        //     inputElements.forEach(function (inputElement) {
        //         inputElement.addEventListener('blur', function () {
        //             updateIDRFormat(this);
        //         });
        //     });
        // });

        function formatCurrency(num) {
            num = num.toString().replace(/\D/g, '');
            if (num.length > 2) {
                num = num.slice(0, -2) + ",00";
            }
            return "Rp " + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.addEventListener('DOMContentLoaded', function() {
            let inputPrices = document.querySelectorAll("#position-allowance, #edit-position-allowance");
            inputPrices.forEach(function(inputPrice) {
                inputPrice.addEventListener("input", function() {
                    this.value = formatCurrency(this.value);
                });
            });
        });

    </script>
@endsection