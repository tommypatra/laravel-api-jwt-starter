@extends('template')

@section('title')
    Dashboard - APP Extension
@endsection

@section('container')
    <!-- Conten Web -->
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <!-- Title -->
                    <div>
                        <h5 class="mb-0">Dashboard</h5>
                        <small class="text-muted">Kelola data aplikasi</small>
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
                            <button type="button" class="btn btn-outline-primary btn-sm" title="Tambah">
                                <i class="icon-base bx bx-plus"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" title="Filter">
                                <i class="icon-base bx bx-filter-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" title="Refresh">
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
                Konten Web
            </div>
        </div>

    </div>
@endsection
