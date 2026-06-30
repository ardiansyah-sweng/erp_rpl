@extends('layouts.app')

@section('title', 'Tambah PIC Supplier')

@section('page-title')
<h3 class="mb-0">Tambah PIC Supplier</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Tambah PIC Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form id="picForm">
            <div class="mb-3">
                <label for="supplier_id" class="form-label">ID Supplier</label>
                <input type="text" class="form-control" id="supplier_id" name="supplier_id" required>
                <span id="supplierIdError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="supplier_name" class="form-label">Nama Supplier</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" readonly>
            </div>
            <div class="mb-3">
                <label for="pic_name" class="form-label">Nama PIC (Person In Charge)</label>
                <input type="text" class="form-control" id="pic_name" name="pic_name" required>
                <span id="picNameError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <span id="emailError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Telephone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" required>
                <span id="telephoneError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="assignment_date" class="form-label">Assignment Date</label>
                <input type="date" class="form-control" id="assignment_date" name="assignment_date" required>
                <span id="assignmentDateError" class="error"></span>
            </div>
            <div>
                <div><label for="pic_photo" class="form-label">Upload Foto PIC</label></div>
                <div class="d-flex justify">
                    <div><img id="photo_preview" src="{{ asset('assets/dist/assets/img/avatar_default.png') }}" alt="Avatar Default" class="mt-2" style="max-width: 100px; max-height: 100px;"></div>
                    <div>
                        <input type="file" class="form-control" id="pic_photo" name="pic_photo" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <div class="mb-3">
                        <label class="form-check-label" for="status">Status</label>
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                        <label for="status">Aktif</label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="validateForm()">Add</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function validateForm() {
        let isValid = true;
        $('#supplierIdError').html("");
        $('#picNameError').html("");
        $('#emailError').html("");
        $('#telephoneError').html("");
        $('#assignmentDateError').html("");

        let supplierId = $('#supplier_id').val();
        let picName = $('#pic_name').val();
        let email = $('#email').val();
        let telephone = $('#telephone').val();
        let assignmentDate = $('#assignment_date').val();

        if (supplierId === null || supplierId === "") {
            $('#supplierIdError').html("<span style=\"color: red;\">ID Supplier harus diisi.</span>");
            isValid = false;
        }
        if (picName === null || picName === "") {
            $('#picNameError').html("<span style=\"color: red;\">Nama PIC harus diisi.</span>");
            isValid = false;
        }
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            $('#emailError').html("<span style=\"color: red;\">Masukan email yang valid.</span>");
            isValid = false;
        }
        let phonePattern = /^\d{10,13}$/;
        if (!phonePattern.test(telephone)) {
            $('#telephoneError').html("<span style=\"color: red;\">Nomor Telephone harus diisi(10-13 digit).</span>");
            isValid = false;
        }
        if (assignmentDate === null || assignmentDate === "") {
            $('#assignmentDateError').html("<span style=\"color: red;\">Tanggal penugasan harus diisi.</span>");
            isValid = false;
        }
        return isValid;
    }
</script>
@endpush
