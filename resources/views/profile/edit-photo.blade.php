@extends('layouts.app')

@section('title', 'Cập Nhật Ảnh Đại Diện - chuaphapvan QC')

@section('styles')
    <style>
        .navbar {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            box-shadow: var(--shadow);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white) !important;
            margin-right: 2rem;
        }

        .container-main {
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            margin: 0;
            color: var(--dark);
        }

        .form-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .current-photo {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: #f9fafb;
            border-radius: 0.75rem;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            border: 4px solid var(--white);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            display: block;
        }

        .form-control {
            border: 2px solid var(--light-gray);
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
            outline: none;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input-label {
            display: block;
            padding: 1rem;
            background: #f9fafb;
            border: 2px dashed var(--light-gray);
            border-radius: 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            border-color: var(--primary-green);
            background: #f0fdf4;
        }

        .file-input-label i {
            font-size: 2rem;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
            display: block;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .file-name {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray);
            font-style: italic;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            flex: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--gray);
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .btn-secondary:hover {
            background: var(--dark-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
        }

        .help-text {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 0.5rem;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        #preview {
            display: none;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .container-main {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-lg" style="padding: 0 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <a href="{{ route('audits.index') }}" class="navbar-brand">
                    <i class="fas fa-table me-2"></i>
                    chuaphapvan QC
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-main">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-image text-green me-2"></i>
                Cập Nhật Ảnh Đại Diện
            </h1>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <!-- Current Photo -->
            <div class="current-photo">
                <div class="photo-preview" id="currentPhoto">
                    @if ($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <p class="photo-label">Ảnh đại diện hiện tại</p>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-upload me-1"></i>
                        Chọn Ảnh Mới
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file" name="photo" id="photo" class="file-input" accept="image/*" required>
                        <label for="photo" class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div>Nhấp để chọn ảnh hoặc kéo thả vào đây</div>
                            <div class="help-text">JPG, PNG, GIF - Tối đa 2MB</div>
                        </label>
                        <div id="fileName" class="file-name"></div>
                    </div>
                    @error('photo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Preview -->
                <div id="preview" class="current-photo">
                    <div class="photo-preview">
                        <img id="previewImage" src="" alt="Preview">
                    </div>
                    <p class="photo-label">Ảnh xem trước</p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Cập Nhật Ảnh
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo');

            if (photoInput) {
                photoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    const fileName = document.getElementById('fileName');
                    const preview = document.getElementById('preview');
                    const previewImage = document.getElementById('previewImage');

                    if (file) {
                        fileName.textContent = file.name;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection
