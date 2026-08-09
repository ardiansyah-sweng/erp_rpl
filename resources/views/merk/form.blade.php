{{-- resources/views/merk/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="merkForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <label for="merk">Nama Merk</label>
            <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" placeholder="Masukkan nama Merk" value="{{old('merk', $merk->merk ?? '')}}" required>
            @error('merk')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', ($merk->is_active ?? true)) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">Aktif</label>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Simpan' }}</button>
    </div>
</form>
