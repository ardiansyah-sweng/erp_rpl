{{-- resources/views/warehouse/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="warehouseForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <label for="warehouse_name">Nama Gudang</label>
            <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" placeholder="Masukkan nama gudang" value="{{ old('warehouse_name', $warehouse->warehouse_name ?? '') }}">
            @error('warehouse_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="warehouse_address">Alamat</label>
            <textarea class="form-control" id="warehouse_address" name="warehouse_address" rows="3" placeholder="Masukkan alamat gudang">{{ old('warehouse_address', $warehouse->warehouse_address ?? '') }}</textarea>
            @error('warehouse_address')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="warehouse_phone">Telepon</label>
            <input type="text" class="form-control" id="warehouse_phone" name="warehouse_phone" placeholder="Masukkan nomor telepon" value="{{ old('warehouse_phone', $warehouse->warehouse_phone ?? '') }}">
            @error('warehouse_phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', ($warehouse->is_active ?? true)) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">Aktif</label>
            </div>
        </div>
        
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_rm_warehouse" value="0">
                <input type="checkbox" class="custom-control-input" id="is_rm_warehouse" name="is_rm_warehouse" value="1" {{ old('is_rm_warehouse', ($warehouse->is_rm_warehouse ?? false)) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_rm_warehouse">Raw Material Warehouse</label>
            </div>
        </div>
        
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_fg_warehouse" value="0">
                <input type="checkbox" class="custom-control-input" id="is_fg_warehouse" name="is_fg_warehouse" value="1" {{ old('is_fg_warehouse', ($warehouse->is_fg_warehouse ?? false)) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_fg_warehouse">Finished Goods Warehouse</label>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
