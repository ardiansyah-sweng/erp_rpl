@extends('layouts.app')

@section('title', 'Tambah Cabang')

@section('page-title')
<h3 class="mb-0">Tambah Cabang</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="/branch/list">Cabang</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulir Tambah Cabang</h3>
                    </div>
                    @include('branches.form', [
                        'action' => route('branches.store'),
                        'method' => 'POST',
                        'branch' => null
                    ])

                    <div id="debug-output" class="mt-4" style="display: none;">
                        <div class="card">
                            <div class="card-body bg-light">
                                <pre id="dd-content" class="p-3 bg-dark text-light" style="border-radius: 5px;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // DEBUG: Form submission debugging for Dusk tests
    $('#branchForm').on('submit', function(e) {
        console.log('Form submit event triggered');
        console.log('Form action:', $(this).attr('action'));
        console.log('Form method:', $(this).attr('method'));
        console.log('CSRF token:', $('input[name="_token"]').val());
        console.log('Form data:', $(this).serialize());

        // Let the form submit naturally - don't prevent default
    });
});
</script>
@endpush
