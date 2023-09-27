@extends('frontend.layout.main')
<!-- @section('title', 'Form Employee') -->
@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <p class="mb-1">Form Employee</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Management</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Form Employee</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Employee</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-validation-employee">
                            <form class="form-valide-employee" action="{{ url('form-employee-add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_name">Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="val_name" name="val_name" placeholder="Enter a name.." required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_nik">NIK<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control" id="val_nik" name="val_nik" placeholder="Enter a NIK.." required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_place_birth">Place of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="val_place_birth" name="val_place_birth" placeholder="Enter a place of birth.." required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_date_birth">Date of birth
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" id="val_date_birth" min="1980-01-01" name="val_date_birth" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_gender">Gender
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_gender" name="val_gender" required>
                                                    <option value="">Please select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_phone">Phone
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="val_phone" name="val_phone" placeholder="+62-000-0000-0000" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_address">Address
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" id="val_address" name="val_address" rows="5" placeholder="Enter a address.." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_photo">Photo
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="file" class="form-control" id="val_photo" name="val_photo" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_position">Position
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_position" name="id_jabatan" required>
                                                    <option value="">Select a position...</option>
                                                    @foreach ($position as $p)
                                                        <option value="{{ $p->id_jabatan }}">{{ $p->nama_jabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_division">Division
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_division" name="id_divisi" required>
                                                    <option value="">Select a division...</option>
                                                    @foreach ($division as $d)
                                                        <option value="{{ $d->id_divisi }}">{{ $d->nama_divisi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_company">Company
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_company" name="id_perusahaan" required>
                                                    <option value="">Select a company...</option>
                                                    @foreach ($company as $c)
                                                        <option value="{{ $c->id_perusahaan }}">{{ $c->nama_perusahaan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_status">Status
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <select class="form-control" id="val_status" name="id_status" required>
                                                    <option value="">Select a status...</option>
                                                    @foreach ($statusEmployee as $s)
                                                        <option value="{{ $s->id_status }}">{{ $s->nama_status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="val_idcard">ID Card
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-6">
                                                <input type="number" class="form-control" id="val_idcard" name="val_idcard" placeholder="Enter a ID card.." required>
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
        // API Indonesian Identification Card (KTP)
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('val_nik').addEventListener('blur', function () {
                // Ambil NIK yang dimasukkan oleh pengguna
                var nik = document.getElementById('val_nik').value;

                // Kirim permintaan ke API KTP
                fetch('https://indonesian-identification-card-ktp.p.rapidapi.com/api/check?nik=' + nik, {
                    method: 'GET',
                    headers: {
                        'X-RapidAPI-Host': 'indonesian-identification-card-ktp.p.rapidapi.com',
                        'X-RapidAPI-Key': 'bb98f2613fmsh4953f916c3d0c17p1e4126jsn872e2cabdbf7',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Isi data formulir secara otomatis dengan data yang diterima dari API
                    getData = data['results']['parse_data']
                    
                    // format tanggal lahir
                    var dateOfBirth1 = getData['tanggal_lahir'];
                    var dateParts = dateOfBirth1.split('/')
                    var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];

                    // gender
                    var gender = getData['jenis_kelamin']
                    var jns_kelamin = ''

                    if(gender == 'LAKI-LAKI'){
                        jns_kelamin = 'male'
                    }else{
                        jns_kelamin = 'female'
                    }

                    document.getElementById('val_date_birth').value = formattedDate;
                    document.getElementById('val_gender').value = jns_kelamin;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

        var divisionSelect = document.getElementById("val_division");
        divisionSelect.addEventListener("change", function() {
            // Ambil nilai terpilih dari <select>
            var selectedDivision = divisionSelect.value;
            if(divisionSelect.value.length == 1){
                selectedDivision = '0'+ selectedDivision
            }
            $('#val_idcard').val(selectedDivision);
        });
    </script>
@endsection