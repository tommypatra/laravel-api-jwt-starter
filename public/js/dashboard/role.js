/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

let currentPage = 1;
let perPage = 10;
let search = '';
let isAdmin = '';

/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

async function loadData(page = 1) {
    try {
        currentPage = page;
        const res = await axios.get(`${API_URL}/role`, {
            params: {
                page: currentPage,
                per_page: perPage,
                search: search,
                is_admin: isAdmin
            }
        });

        renderTable(res.data.data, res.data.pagination);
        renderPagination(
            res.data.pagination,
            '#pagination-data',
            loadData
        );
    } catch (error) {
        console.error(error);
        alert(
            error.response?.data?.message ||
            'Terjadi kesalahan saat mengambil data.'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Render Table
|--------------------------------------------------------------------------
*/

function renderTable(data, pagination) {
    let html = '';

    if (!data.length) {
        html = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    Tidak ada data
                </td>
            </tr>
        `;
    } else {
        data.forEach((item, index) => {
            const no = pagination.from + index;

            html += `
                <tr>
                    <td>${no}</td>
                    <td>${item.nama}</td>
                    <td>
                        ${
                            item.is_admin == 1
                                ? '<span class="badge bg-label-primary">Admin</span>'
                                : '<span class="badge bg-label-secondary">Bukan Admin</span>'
                        }
                    </td>
                    <td class="text-center">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-warning btn-edit"
                            data-id="${item.id}"
                            title="Edit"
                        >
                            <i class="bx bx-pencil"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger btn-delete"
                            data-id="${item.id}"
                            title="Hapus"
                        >
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    $('#table-data-body').html(html);

    renderTableInfo(
        pagination,
        '#table-info',
        '#pagination-info'
    );
}

/*
|--------------------------------------------------------------------------
| reset form
|--------------------------------------------------------------------------
*/

function resetForm() {
    $('#form-input')[0].reset();
    $('#id').val('');
    $('#modal-form-title').text('Tambah Role');

    if ($('#form-input').data('validator')) {
        $('#form-input').validate().resetForm();
        $('#form-input .is-invalid').removeClass('is-invalid');
    }
}

/*
|--------------------------------------------------------------------------
| Edit
|--------------------------------------------------------------------------
*/

$(document).on('click', '.btn-edit', async function () {
    try {
        const id = $(this).data('id');

        resetForm();

        const res = await axios.get(`${API_URL}/role/${id}`);

        const data = res.data.data;

        $('#id').val(data.id);
        $('#nama').val(data.nama);
        $('#is_admin').val(data.is_admin);

        $('#modal-form-title').text('Edit Role');
        $('#modal-form').modal('show');

    } catch (error) {
        alert(
            error.response?.data?.message ||
            'Gagal mengambil detail data.'
        );
    }
});

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

$(document).on('click', '.btn-delete', async function () {
    const id = $(this).data('id');
    if (!confirm('Yakin ingin menghapus data ini?')) {
        return;
    }
    try {
        await axios.delete(`${API_URL}/role/${id}`);
        loadData(currentPage);
    } catch (error) {
        alert(
            error.response?.data?.message ||
            'Gagal menghapus data.'
        );
    }
});


$(document).ready(function () {
    /*
    |--------------------------------------------------------------------------
    | Init
    |--------------------------------------------------------------------------
    */
    loadData();

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    $('#btn-search').on('click', function () {
        search = $('#search-data').val().trim();
        loadData(1);
    });

    $('#search-data').on('keypress', function (e) {
        if (e.which === 13) {
            search = $(this).val().trim();
            loadData(1);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Per Page
    |--------------------------------------------------------------------------
    */

    $('#per-page').on('change', function () {
        perPage = $(this).val();
        loadData(1);
    });

    /*
    |--------------------------------------------------------------------------
    | Tambah
    |--------------------------------------------------------------------------
    */

    $('#btn-tambah').on('click', function () {
        resetForm();
        $('#modal-form').modal('show');
    });

    /*
    |--------------------------------------------------------------------------
    | Refresh
    |--------------------------------------------------------------------------
    */

    $('#btn-refresh').on('click', function () {
        currentPage = 1;
        perPage = 10;
        search = '';
        isAdmin = '';

        $('#search-data').val('');
        $('#filter-admin').val('');
        $('#per-page').val('10');

        loadData(1);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */
    $('#form-input').validate({
        rules: {
            nama: {
                required: true
            },
            is_admin: {
                required: true
            }
        },
        messages: {
            nama: {
                required: 'Nama role wajib diisi'
            },
            is_admin: {
                required: 'Status admin wajib dipilih'
            }
        },
        submitHandler: async function (form, event) {
            event.preventDefault();

            try {
                const id = $('#id').val();
                let payload = Object.fromEntries(
                    new FormData(form).entries()
                );
                
                if (id) {
                    await axios.put(`${API_URL}/role/${id}`, payload);
                } else {
                    await axios.post(`${API_URL}/role`, payload);
                }
                $('#modal-form').modal('hide');
                loadData(currentPage);
            } catch (error) {
                alert(
                    error.response?.data?.message ||
                    'Gagal menyimpan data.'
                );
            }
        }
    });    

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    $('#btn-filter').on('click', function () {
        $('#modal-filter').modal('show');
    });

    $('#form-filter').on('submit', function (e) {
        e.preventDefault();
        isAdmin = $('#filter-admin').val();
        $('#modal-filter').modal('hide');
        loadData(1);
    });

    $('#btn-reset-filter').on('click', function () {
        $('#filter-admin').val('');
        isAdmin = '';
        $('#modal-filter').modal('hide');
        loadData(1);
    });
    
});