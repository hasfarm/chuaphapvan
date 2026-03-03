@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-user-edit" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chỉnh Sửa Người Dùng
        </h1>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); max-width: 600px;">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Họ Tên <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" name="fullname" placeholder="Nhập họ tên đầy đủ"
                    value="{{ old('fullname', $user->fullname) }}" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('fullname') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; font-size: 0.95rem;">
                @error('fullname')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Mã Nhân Viên (User Code) <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" name="user_code" placeholder="Nhập mã nhân viên"
                    value="{{ old('user_code', $user->user_code) }}" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('user_code') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; font-size: 0.95rem;">
                @error('user_code')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Email <span style="color: #dc2626;">*</span>
                </label>
                <input type="email" name="email" placeholder="Nhập email" value="{{ old('email', $user->email) }}"
                    required
                    style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('email') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; font-size: 0.95rem;">
                @error('email')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Mật Khẩu Mới (để trống nếu không thay đổi)
                </label>
                <input type="password" name="password" placeholder="Nhập mật khẩu mới"
                    style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('password') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; font-size: 0.95rem;">
                @error('password')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Xác Nhận Mật Khẩu
                </label>
                <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới"
                    style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem; font-size: 0.95rem;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Vai Trò <span style="color: #dc2626;">*</span>
                </label>
                <select name="role_id" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('role_id') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; font-size: 0.95rem; color: var(--dark)!important; background-color: var(--white);">
                    <option value="" style="color: var(--dark);">-- Chọn vai trò --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" style="color: var(--dark)!important;"
                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"
                    style="flex: 1; justify-content: center;">
                    <i class="fas fa-times"></i>
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <i class="fas fa-save"></i>
                    Cập Nhật
                </button>
            </div>
        </form>
    </div>
@endsection
