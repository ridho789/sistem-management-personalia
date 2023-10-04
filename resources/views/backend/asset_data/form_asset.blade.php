@extends('frontend.layout.main')
<!-- @section('title', 'Form Asset') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <p class="mb-1">Form Asset</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Form Asset</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Asset</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation-asset">
                            <!-- form edit asset -->
                            <form class="form-valide-asset" action="{{ url('form-asset-add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label" for="val_name">Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" 
                                                id="val_name" name="val_name" placeholder="Enter a name.." required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_category">Category
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_category" name="val_category" required>
                                                    <option value="">Select a category...</option>
                                                    @foreach ($category as $ca)
                                                        <option value="{{ $ca->id_kategori }}" {{ old('id_kategori') == $ca->id_kategori ? 'selected' : '' }}>{{ $ca->nama_kategori }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_sub_category">Sub Category
                                                <span id="subCategoryLabel" class="text-primary">(Optional)</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="val_sub_category" name="val_sub_category">
                                                    <option value="">Select a sub category...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="form_vehicle">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_nopol">Police Number
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_nopol" name="val_nopol" placeholder="Enter a police number.." required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_merk">Vehicle Brand
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_merk" name="val_merk" placeholder="Enter a vehicle brand.." required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_tahun">Vehicle Year<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control" 
                                                id="val_tahun" name="val_tahun" placeholder="Enter a vehicle year.." required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_masa_pajak">Tax Expiration Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="date" class="form-control" 
                                                id="val_masa_pajak" min="1980-01-01" name="val_masa_pajak" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_masa_plat">Plate Expiration Date
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="date" class="form-control" 
                                                id="val_masa_plat" min="1980-01-01" name="val_masa_plat" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_location">Location
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" 
                                                id="val_location" name="val_location" placeholder="Enter a location.." required>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="column_specification">
                                            <label class="col-lg-4 col-form-label" for="val_specification">Specification
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="val_spesification" name="val_spesification" rows="5" 
                                                placeholder="Enter a spesification.." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_company">Company
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="val_company" name="val_company" required>
                                                    <option value="">Select a company...</option>
                                                    @foreach ($company as $c)
                                                        <option value="{{ $c->id_perusahaan }}" {{ old('id_perusahaan') == $c->id_perusahaan ? 'selected' : '' }}>{{ $c->nama_perusahaan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8 ml-auto">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menampilkan/menyembunyikan kolom Specification dan mengubah persyaratan required
            function toggleSpecificationField() {
                var categorySelect = document.getElementById('val_category');
                var specificationField = document.getElementById('val_spesification');
                var specificationColumn = document.getElementById('column_specification');

                var formVehicle = document.getElementById('form_vehicle');
                var formVehiclePoliceNumber = document.getElementById('val_nopol');
                var formVehiclePoliceBrand = document.getElementById('val_merk');
                var formVehiclePoliceYear = document.getElementById('val_tahun');
                var formVehicleTax = document.getElementById('val_masa_pajak');
                var formVehiclePlate = document.getElementById('val_masa_plat');

                // Mengubah teks dalam pilihan kategori menjadi huruf kecil sebelum membandingkan
                var selectedCategoryText = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();
    
                // Cek apakah kategori yang dipilih adalah "kendaraan"
                if (selectedCategoryText === 'kendaraan') {
                    // Jika kategori adalah "kendaraan", sembunyikan kolom Specification
                    specificationColumn.style.display = 'none';
                    // Hapus persyaratan required dari kolom Specification
                    specificationField.removeAttribute('required');

                    // Jika kategori "kendaraan", tampilkan kolom id form vehicle
                    formVehicle.style.display = 'flex';
                    // Tambahkan persyaratan required ke kolom id form vehicle
                    formVehiclePoliceNumber.setAttribute('required', 'required');
                    formVehiclePoliceBrand.setAttribute('required', 'required');
                    formVehiclePoliceYear.setAttribute('required', 'required');
                    formVehicleTax.setAttribute('required', 'required');
                    formVehiclePlate.setAttribute('required', 'required');

                } else {
                    // Jika kategori bukan "kendaraan", tampilkan kembali kolom Specification
                    specificationColumn.style.display = 'flex';
                    // Tambahkan persyaratan required ke kolom Specification
                    specificationField.setAttribute('required', 'required');

                    // Jika kategori adalah "kendaraan", sembunyikan kolom id form vehicle
                    formVehicle.style.display = 'none';
                    // Hapus persyaratan required dari kolom id form vehicle
                    formVehiclePoliceNumber.removeAttribute('required');
                    formVehiclePoliceBrand.removeAttribute('required');
                    formVehiclePoliceYear.removeAttribute('required');
                    formVehicleTax.removeAttribute('required');
                    formVehiclePlate.removeAttribute('required');
                }
            }

            // Ketika pilihan kategori berubah
            document.getElementById('val_category').addEventListener('change', function() {
                // Panggil fungsi toggleSpecificationField setiap kali nilai kategori berubah
                toggleSpecificationField();

                var categoryId = this.value;
                var subCategoryLabel = document.getElementById('subCategoryLabel');

                // Mengambil opsi sub kategori berdasarkan kategori yang dipilih
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '/get-sub-categories/' + categoryId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);

                        var subCategorySelect = document.getElementById('val_sub_category');
                        subCategorySelect.innerHTML = ''; // Menghapus semua opsi sebelumnya

                        // Menambahkan opsi "Select a sub category..."
                        var option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Select a sub category...';
                        subCategorySelect.appendChild(option);

                        // Menambahkan opsi-opsi sub kategori dari respons JSON
                        data.forEach(function(value) {
                            var option = document.createElement('option');
                            option.value = value.id_sub_kategori;
                            option.textContent = value.nama_sub_kategori;
                            subCategorySelect.appendChild(option);
                        });
                    }
                };
                xhr.send();
            });
            // Panggil fungsi toggleSpecificationField saat halaman pertama kali dimuat
            toggleSpecificationField();
        });
    </script>
@endsection