@extends('frontend.layout.main')
<!-- @section('title', 'Category') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Category</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Category</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="row">
            <!-- form add new category -->
            <div class="col-12" id="form-new-category" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('category-add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" id="name-category" class="form-control input-new-category" name="input_kategori" placeholder="input new category" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-category" id="close-form-new-category">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit category -->
            <div class="col-12" id="form-edit-category" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-category" action="{{ url('category-update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" id="edit-id" name="id_kategori">
                                    <input type="text" id="edit-category" class="form-control input-edit-category" name="value_kategori" placeholder="input edit category" required>
                                    <button type="submit" class="btn btn-primary mt-3 submit-category" id="close-form-edit-category">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- category -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Category</h4>
                        @if (count($tbl_kategori) > 0)
                            <button type="submit" class="btn btn-primary mt-3" id="new-category">+ Add new category</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_kategori) > 0)
                            <div class="table-responsive">
                                <table id="data-table-category" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_kategori as $k)
                                        <tr data-id="{{$k->id_kategori}}">
                                            <td class="category-name-selected">{{$k->nama_kategori}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-button" class="edit-button"><i class="fa fa-edit"> edit |</i></a>
                                                <a href="category-delete/{{$k->id_kategori}}"><i class="fa fa-trash"> delete </i></a>
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
                                        <button type="submit" class="btn btn-light mt-2" id="new-category">click to add new category</button>
                                    </p>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- form add new sub category -->
            <div class="col-12" id="form-new-sub-category" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Sub Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ url('sub-category-add') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="val_category">Category</label>
                                            <select class="form-control" id="val_category" name="id_kategori" required>
                                                <option value="">Select a category...</option>
                                                @foreach ($categories as $c)
                                                    <option value="{{ $c->id_kategori }}">
                                                        {{ $c->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="val_sub_category">Sub Category</label>
                                            <input type="text" id="name-sub-category" class="form-control input-new-category" name="input_sub_kategori" placeholder="input new sub category" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mt-3 submit-sub-category" id="close-form-new-sub-category">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- form edit sub category -->
            <div class="col-12" id="form-edit-sub-category" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Sub Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form id="form-edit-value-sub-category" action="{{ url('sub-category-update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="edit_val_category">Category</label>
                                            <select class="form-control" id="edit-val-category" name="id_kategori" required>
                                                <option value="">Select a category...</option>
                                                @foreach ($categories as $c)
                                                    <option value="{{ $c->id_kategori }}">
                                                        {{ $c->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="edit_val_sub_category">Sub Category</label>
                                            <input type="hidden" id="edit-sub-id" name="id_sub_kategori">
                                            <input type="text" id="edit-sub-category" class="form-control input-edit-sub-category" name="value_sub_kategori" placeholder="input edit sub category" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mt-3 submit-sub-category" id="close-form-edit-sub-category">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- sub category -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sub Category</h4>
                        @if (count($tbl_sub_kategori) > 0)
                            <button type="submit" class="btn btn-primary mt-3" id="new-sub-category">+ Add new sub category</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (count($tbl_sub_kategori) > 0)
                            <div class="table-responsive">
                                <table id="data-table-sub-category" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tbl_sub_kategori as $sk)
                                        <tr data-id="{{$sk->id_sub_kategori}}">
                                            <td class="id-category-selected">{{ $dataCategories[$sk->id_kategori] }}</td>
                                            <td class="sub-category-name-selected">{{$sk->nama_sub_kategori}}</td>
                                            <td style="text-align:right;">
                                                <a href="#" id="edit-sub-button" class="edit-sub-button"><i class="fa fa-edit"> edit |</i></a>
                                                <a href="sub-category-delete/{{$sk->id_sub_kategori}}"><i class="fa fa-trash"> delete </i></a>
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
                                        <button type="submit" class="btn btn-light mt-2" id="new-sub-category">click to add new sub category</button>
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
        // script to show/hide form add new category
        const toggleFormButton = document.getElementById('new-category');
        const toggleCloseFormButton = document.getElementById('close-form-new-category');
        const myForm = document.getElementById('form-new-category');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }

            // close form edit
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }

            // close form add sub category
            if (mySubForm.style.display === 'block') {
                mySubForm.style.display = 'none';
            }

            // close form edit sub category
            if (mySubEditForm.style.display === 'block') {
                mySubEditForm.style.display = 'none';
            }
        });

        toggleCloseFormButton.addEventListener('click', function() {
            var namecategoryInput = document.getElementById("name-category");
            var namecategoryValue = namecategoryInput.value;
            if (namecategoryValue){
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            }
        });

        // script to show/hide edit form
        const toggleFormEditButton = document.getElementById('edit-button');
        const toggleCloseFormEditButton = document.getElementById('close-form-edit-category');
        const myEditForm = document.getElementById('form-edit-category');

        // show edit form
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var categoryName = row.querySelector(".category-name-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-category").value = categoryName;

                if (myEditForm.style.display === 'none') {
                    myEditForm.style.display = 'block';
                }

                // close form add category
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }

                // close form add sub category
                if (mySubForm.style.display === 'block') {
                    mySubForm.style.display = 'none';
                }

                // close form edit sub category
                if (mySubEditForm.style.display === 'block') {
                    mySubEditForm.style.display = 'none';
                }
            });
        });

        // close edit form
        toggleCloseFormEditButton.addEventListener('click', function() {
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }
        });

        // sub category form add
        const toggleSubFormButton = document.getElementById('new-sub-category');
        const toggleSubCloseFormButton = document.getElementById('close-form-new-sub-category');
        const mySubForm = document.getElementById('form-new-sub-category');

        toggleSubFormButton.addEventListener('click', function() {
            if (mySubForm.style.display === 'none') {
                mySubForm.style.display = 'block';
            }

            // close form add category
            if (myForm.style.display === 'block') {
                myForm.style.display = 'none';
            }

            // close form edit category
            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }

            // close form edit sub category
            if (mySubEditForm.style.display === 'block') {
                mySubEditForm.style.display = 'none';
            }

        });

        toggleSubCloseFormButton.addEventListener('click', function() {
            var namecategoryInput = document.getElementById("name-category");
            var namecategoryValue = namecategoryInput.value;
            if (namecategoryValue){
                if (mySubForm.style.display === 'block') {
                    mySubForm.style.display = 'none';
                }
            }
        });

        // sub category form edit
        const toggleSubFormEditButton = document.getElementById('edit-sub-button');
        const toggleSubCloseFormEditButton = document.getElementById('close-form-edit-sub-category');
        const mySubEditForm = document.getElementById('form-edit-sub-category');

        // show edit form
        var editSubButtons = document.querySelectorAll(".edit-sub-button");
        editSubButtons.forEach(function (button) {
            button.addEventListener("click", function (event) {
                event.preventDefault();

                // Mengambil data dari baris yang dipilih
                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var CategoryName = row.querySelector(".id-category-selected").textContent;
                var subCategoryName = row.querySelector(".sub-category-name-selected").textContent;

                // Mengisi data ke dalam formulir
                document.getElementById("edit-sub-id").value = id;
                document.getElementById("edit-sub-category").value = subCategoryName;

                var categorySelect = document.getElementById("edit-val-category");
        
                // Loop melalui opsi di dropdown Category
                for (var i = 0; i < categorySelect.options.length; i++) {
                    if (categorySelect.options[i].text === CategoryName) {
                        categorySelect.selectedIndex = i; // Pilih opsi yang sesuai
                        break;
                    }
                }

                if (mySubEditForm.style.display === 'none') {
                    mySubEditForm.style.display = 'block';
                }

                // close form add sub category
                if (mySubForm.style.display === 'block') {
                    mySubForm.style.display = 'none';
                }

                // close form add category
                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }

                // close form edit category
                if (myEditForm.style.display === 'block') {
                    myEditForm.style.display = 'none';
                }
            });
        });

        // close edit form
        toggleSubCloseFormEditButton.addEventListener('click', function() {
            if (mySubEditForm.style.display === 'block') {
                mySubEditForm.style.display = 'none';
            }
        });
    </script>
@endsection