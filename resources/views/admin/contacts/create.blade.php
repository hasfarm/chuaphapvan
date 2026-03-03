@extends('admin.layouts.app')

@section('admin-content')
    <div class="page-header">
        <h1>
            <i class="fas fa-user-plus" style="color: var(--primary-green); margin-right: 0.5rem;"></i>
            Thêm Phật Tử Mới
        </h1>
    </div>

    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
        <form method="POST" action="{{ route('admin.contacts.store') }}" novalidate>
            @csrf
            @include('admin.contacts.partials.form', [
                'contact' => null,
                'families' => $families,
                'availableContacts' => $availableContacts,
                'contactRelationships' => $contactRelationships,
            ])
        </form>
    </div>
@endsection
