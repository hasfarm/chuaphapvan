@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-plus" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Thêm Sự Kiện Mới
        </h1>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <form method="POST" action="{{ route('admin.events.store') }}" novalidate>
            @csrf
            @include('admin.events.partials.form', ['event' => null])
        </form>
    </div>
@endsection
