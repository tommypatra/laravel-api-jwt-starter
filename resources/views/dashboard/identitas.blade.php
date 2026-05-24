@extends('template')

@section('title')
    Profil - Akun
@endsection

@section('container')
    <!-- Conten Web -->
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <!-- Title -->
                    <div>
                        <h5 class="mb-0">Profil</h5>
                        <small class="text-muted">Kelola profil akun</small>
                    </div>
                    <!-- Toolbar -->
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Action Buttons -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-info btn-sm" id="btn-refresh" title="Refresh">
                                <i class="icon-base bx bx-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card-body">
                <form id="form-input">

                    <div class="mb-3">
                        <label class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <button type="button" class="btn btn-outline-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-save">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard/identitas.js') }}?v={{ filemtime(public_path('js/dashboard/identitas.js')) }}">
    </script>
@endpush
