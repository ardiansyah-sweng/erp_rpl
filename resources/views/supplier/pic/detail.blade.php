@extends('layouts.app')

@section('title', 'Detail PIC Supplier')

@section('content')
<div class="container mt-4">
    <h2>Supplier PIC Detail</h2>

    <form id="supplierForm" action="#" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
                <small class="text-danger d-none" id="error_first_name">Nama depan wajib diisi</small>
            </div>
            <div class="col-md-6">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
                <small class="text-danger d-none" id="error_last_name">Nama belakang wajib diisi</small>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <small class="text-danger d-none" id="error_username">Username wajib diisi</small>
            </div>
            <div class="col-md-6">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
                <small class="text-danger d-none" id="error_city">Kota wajib diisi</small>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="state">State</label>
                <select class="form-control" id="state" name="state" required>
                    <option value="">Choose...</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Surabaya">Surabaya</option>
                </select>
                <small class="text-danger d-none" id="error_state">Pilih salah satu</small>
            </div>
            <div class="col-md-6">
                <label for="zip">Zip</label>
                <input type="text" class="form-control" id="zip" name="zip" required>
                <small class="text-danger d-none" id="error_zip">Kode pos wajib diisi</small>
            </div>
        </div>

        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label" for="terms">
                Agree to terms and conditions
            </label>
            <small class="text-danger d-none" id="error_terms">Harus menyetujui syarat</small>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit Form</button>
    </form>
</div>

<script>
document.getElementById("supplierForm").addEventListener("submit", function(event) {
    let isValid = true;

    function showError(id) {
        document.getElementById(id).classList.remove("d-none");
        isValid = false;
    }

    function hideError(id) {
        document.getElementById(id).classList.add("d-none");
    }

    const firstName = document.getElementById("first_name").value.trim();
    const lastName = document.getElementById("last_name").value.trim();
    const username = document.getElementById("username").value.trim();
    const city = document.getElementById("city").value.trim();
    const state = document.getElementById("state").value;
    const zip = document.getElementById("zip").value.trim();
    const terms = document.getElementById("terms").checked;

    firstName ? hideError("error_first_name") : showError("error_first_name");
    lastName ? hideError("error_last_name") : showError("error_last_name");
    username ? hideError("error_username") : showError("error_username");
    city ? hideError("error_city") : showError("error_city");
    state ? hideError("error_state") : showError("error_state");
    zip ? hideError("error_zip") : showError("error_zip");
    terms ? hideError("error_terms") : showError("error_terms");

    if (!isValid) event.preventDefault();
});
</script>
@endsection
