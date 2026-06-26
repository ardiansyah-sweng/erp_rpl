@extends('layouts.app')

@section('title', 'Edit PIC Supplier')

@section('page-title')
<h3 class="mb-0">Edit PIC Supplier</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Edit PIC Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="picForm" action="{{ route('supplier.pic.updateData', $pic->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            <div class="mb-3">
                <label for="supplier_id" class="form-label">ID Supplier</label>
                <input type="text" class="form-control" id="supplier_id" name="supplier_id" value="{{ $pic->supplier_id }}" readonly>
                <span id="supplierIdError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="supplier_name" class="form-label">Nama Supplier</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ $pic->supplier_name }}" readonly>
            </div>
            <div class="mb-3">
                <label for="pic_name" class="form-label">Nama PIC (Person In Charge)</label>
                <input type="text" class="form-control" id="pic_name" name="pic_name" value="{{ $pic->name }}" readonly>
                <span id="picNameError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $pic->email }}" required>
                <span id="emailError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $pic->phone_number }}" required>
                <span id="telephoneError" class="error"></span>
            </div>
            <div class="mb-3">
                <label for="assignment_date" class="form-label">Assignment Date</label>
                <input type="date" class="form-control" id="assignment_date" name="assignment_date" value="{{ \Carbon\Carbon::parse($pic->assigned_date)->format('Y-m-d') }}" readonly>
                <span id="assignmentDateError" class="error"></span>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('supplier.pic.list') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#supplier_id').on('input', function() {
            let sid = $(this).val();
            if (sid) {
                $.get('/api/supplier/' + sid, function(response) {
                    if (response.status === 'success') {
                        $('#supplier_name').val(response.name);
                        $('#supplierIdError').html("");
                    }
                }).fail(function() {
                    $('#supplier_name').val("");
                });
            } else {
                $('#supplier_name').val("");
            }
        });
    });

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

        if (supplierId === null || supplierId.trim() === "") {
            $('#supplierIdError').html("<span style=\"color: red;\">ID Supplier harus diisi.</span>");
            isValid = false;
        }
        if (picName === null || picName.trim() === "") {
            $('#picNameError').html("<span style=\"color: red;\">Nama PIC harus diisi.</span>");
            isValid = false;
        }
        if (email === null || email.trim() === "") {
            $('#emailError').html("<span style=\"color: red;\">Email harus diisi.</span>");
            isValid = false;
        }
        if (telephone === null || telephone.trim() === "") {
            $('#telephoneError').html("<span style=\"color: red;\">Nomor Telephone harus diisi.</span>");
            isValid = false;
        }
        if (assignmentDate === null || assignmentDate.trim() === "") {
            $('#assignmentDateError').html("<span style=\"color: red;\">Tanggal penugasan harus diisi.</span>");
            isValid = false;
        }

        return isValid;
    }
</script>
@endpush
