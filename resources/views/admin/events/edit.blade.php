@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-edit" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Chỉnh Sửa Sự Kiện
        </h1>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" novalidate>
            @csrf
            @method('PUT')
            @include('admin.events.partials.form', ['event' => $event])
        </form>
    </div>
@endsection
