@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-plus-circle" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Thêm Gia Đình Mới
        </h1>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <form method="POST" action="{{ route('admin.families.store') }}" novalidate>
            @csrf
            @include('admin.families.partials.form', ['family' => null])
        </form>
    </div>
@endsection
