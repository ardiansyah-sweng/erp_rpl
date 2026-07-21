{{-- resources/views/customer/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="customerForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <label for="customer_name">Nama Pelanggan</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Masukkan nama pelanggan" value="{{ old('customer_name', $customer->customer_name ?? '') }}">
            @error('customer_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="customer_address">Alamat</label>
            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" placeholder="Masukkan alamat pelanggan">{{ old('customer_address', $customer->customer_address ?? '') }}</textarea>
            @error('customer_address')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="customer_phone">Telepon</label>
            <input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Masukkan nomor telepon" value="{{ old('customer_phone', $customer->customer_phone ?? '') }}">
            @error('customer_phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', ($customer->is_active ?? true)) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">Aktif</label>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
