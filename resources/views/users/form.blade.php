{{-- resources/views/users/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="userForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ old('name', $user->name ?? '') }}">
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="{{ old('email', $user->email ?? '') }}">
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Password{{ isset($user) ? ' (kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-select" id="role" name="role">
                @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ old('role', $user->role?->value ?? '') === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Simpan' }}</button>
    </div>
</form>
