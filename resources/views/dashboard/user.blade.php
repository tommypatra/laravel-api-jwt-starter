@extends('template')

@section('title')
    User - Website
@endsection

@section('container')
    <!-- Conten Web -->
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <!-- Title -->
                    <div>
                        <h5 class="mb-0">User</h5>
                        <small class="text-muted">Kelola data user</small>
                    </div>
                    <!-- Toolbar -->
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Search Group -->
                        <div class="input-group input-group-sm" style="width: 340px;">
                            <input type="text" class="form-control" placeholder="Cari data..." id="search-data">
                            <button class="btn btn-primary" type="button" id="btn-search" title="Cari">
                                <i class="icon-base bx bx-search"></i>
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-tambah" title="Tambah">
                                <i class="icon-base bx bx-plus"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-filter" title="Filter">
                                <i class="icon-base bx bx-filter-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" id="btn-refresh" title="Refresh">
                                <i class="icon-base bx bx-refresh"></i>
                            </button>
                        </div>
                        <!-- Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-printer me-2"></i>
                                        Cetak
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-import me-2"></i>
                                        Import
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-export me-2"></i>
                                        Export
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bx bx-trash me-2"></i>
                                        Hapus Massal
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card-body">
                <!-- Table Toolbar -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="mb-0 small text-muted">Tampilkan</label>
                        <select class="form-select form-select-sm" id="per-page" style="width: 90px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label class="mb-0 small text-muted">data</label>
                    </div>
                    <div>
                        <small class="text-muted" id="table-info">
                            Menampilkan 0 - 0 dari 0 data
                        </small>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role User</th>
                                <th width="180" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-data-body">
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">

                    <div>
                        <small class="text-muted" id="pagination-info">
                            Halaman 1 dari 1
                        </small>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination-data">
                            <!-- render js -->
                        </ul>
                    </nav>
                </div>
            </div>

        </div>

    </div>


    <!-- Modal Form -->
    <div class="modal fade" id="modal-form" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-input">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-form-title">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" id="id">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn-simpan">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Role -->
    <div class="modal fade" id="modal-role" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-role">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-role-title">Role User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" id="role-list">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn-simpan-role">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Filter -->
    <div class="modal fade" id="modal-filter" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="form-filter">

                    <div class="modal-header">
                        <h5 class="modal-title">Filter Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" id="filter_role">
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                            Reset
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Terapkan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard/user.js') }}"></script>
@endpush
