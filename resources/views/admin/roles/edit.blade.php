@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-edit" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chỉnh Sửa Vai Trò
        </h1>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); max-width: 600px;">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}" novalidate>
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Tên Vai Trò <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" name="name" placeholder="VD: Quản Lý Chất Lượng"
                    value="{{ old('name', $role->name) }}" required
                    style="width: 100%; padding: 0.75rem; border: 2px solid {{ $errors->has('name') ? '#dc2626' : 'var(--light-gray)' }}; border-radius: 0.5rem; font-size: 0.95rem;">
                @error('name')
                    <div style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">
                    Mô Tả
                </label>
                <textarea name="description" placeholder="Nhập mô tả vai trò..." rows="4"
                    style="width: 100%; padding: 0.75rem; border: 2px solid var(--light-gray); border-radius: 0.5rem; font-size: 0.95rem; font-family: inherit;">{{ old('description', $role->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary"
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
