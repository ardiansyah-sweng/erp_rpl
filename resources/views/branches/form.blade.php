{{-- resources/views/branches/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="branchForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <label for="branch_name">Nama Cabang</label>
            <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Masukkan nama cabang" value="{{ old('branch_name', $branch->branch_name ?? '') }}">
            @error('branch_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="branch_address">Alamat</label>
            <textarea class="form-control" id="branch_address" name="branch_address" rows="3" placeholder="Masukkan alamat cabang">{{ old('branch_address', $branch->branch_address ?? '') }}</textarea>
            @error('branch_address')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="branch_telephone">Telepon</label>
            <input type="text" class="form-control" id="branch_telephone" name="branch_telephone" placeholder="Masukkan nomor telepon" value="{{ old('branch_telephone', $branch->branch_telephone ?? '') }}">
            @error('branch_telephone')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', ($branch->is_active ?? true)) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">Aktif</label>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
