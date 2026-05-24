/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

let currentPage = 1;
let perPage = 10;
let search = '';
let filter_role = '';
let allRoles = [];

/*
|--------------------------------------------------------------------------
| Load Master Roles
|--------------------------------------------------------------------------
*/

async function loadRoles() {
    try {
        allRoles = await fetchAllPages('role');
    } catch (error) {
        alert('Gagal memuat data role.');
    }
}

/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

async function loadData(page = 1) {
    try {
        currentPage = page;
        const res = await axios.get(`${API_URL}/user`, {
            params: {
                page: currentPage,
                per_page: perPage,
                search: search,
                filter_role: filter_role
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
                    <td>${item.name}</td>
                    <td>${item.email}</td>
                    <td>
                        ${item.roles.length
                            ? item.roles.map(role => `
                                <span class="badge bg-label-primary me-1 mb-1">
                                    ${role.nama}
                                </span>
                            `).join('')
                            : '<span class="text-muted">-</span>'
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
                            class="btn btn-sm btn-outline-info btn-role"
                            data-id="${item.id}"
                            title="Kelola Role"
                        >
                            <i class="bx bx-shield-quarter"></i>
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
    $('#modal-form-title').text('Tambah User');

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
        const res = await axios.get(`${API_URL}/user/${id}`);
        const data = res.data.data;

        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);

        $('#modal-form-title').text('Edit User');
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
        await axios.delete(`${API_URL}/user/${id}`);
        loadData(currentPage);
    } catch (error) {
        alert(
            error.response?.data?.message ||
            'Gagal menghapus data.'
        );
    }
});

/*
|--------------------------------------------------------------------------
| Kelola Role User
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Render Filter Roles
|--------------------------------------------------------------------------
*/

function renderFilterRoles() {
    let html = '<option value="">Semua Role</option>';

    allRoles.forEach(role => {
        const selected = filter_role === role.nama
            ? 'selected'
            : '';

        html += `
            <option value="${role.nama}" ${selected}>
                ${role.nama}
            </option>
        `;
    });

    $('#filter_role').html(html);
}

$(document).on('click', '.btn-role', async function () {
    try {
        const userId = $(this).data('id');
        const res = await axios.get(`${API_URL}/user/${userId}`);
        const user = res.data.data;

        const selectedRoleIds = user.roles.map(role => role.role_id);
        let html = '';        
        allRoles.forEach(role => {
            const checked = selectedRoleIds.includes(role.id)? 'checked': '';
            html += `
                <div class="form-check mb-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="roles[]"
                        value="${role.id}"
                        id="role-${role.id}"
                        ${checked}
                    >

                    <label
                        class="form-check-label"
                        for="role-${role.id}"
                    >
                        ${role.nama}
                    </label>
                </div>
            `;
        });
        $('#form-role').data('user-id', userId);
        $('#role-list').html(html);
        $('#modal-role').modal('show');

    } catch (error) {
        alert(
            error.response?.data?.message ||
            'Gagal memuat data role user.'
        );
    }
});


$('#form-role').on('submit', async function (e) {
    e.preventDefault();
    try {
        const userId = $(this).data('user-id');
        const roles = [];
        $('input[name="roles[]"]:checked').each(function () {
            roles.push($(this).val());
        });
        await axios.put(`${API_URL}/user/${userId}/roles
            `, {
            roles
        });
        $('#modal-role').modal('hide');
        loadData(currentPage);
    } catch (error) {
        alert(
            error.response?.data?.message ||
            'Gagal menyimpan role.'
        );
    }
});

$(document).ready(async function () {
    /*
    |--------------------------------------------------------------------------
    | Init
    |--------------------------------------------------------------------------
    */
    await loadRoles();
    renderFilterRoles();
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
        filter_role = '';

        $('#search-data').val('');
        $('#filter_role').val('');
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
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: function () {
                    return !$('#id').val();
                },
                minlength: 6
            }
        },
        messages: {
            name: {
                required: 'Nama wajib diisi'
            },
            email: {
                required: 'Email wajib diisi',
                email: 'Format email tidak valid'
            },
            password: {
                required: 'Password wajib diisi',
                minlength: 'Password minimal 6 karakter'
            }
        },
        submitHandler: async function (form, event) {
            event.preventDefault();

            try {
                const id = $('#id').val();
                let payload = Object.fromEntries(
                    new FormData($('#form-input')[0]).entries()
                );

                if (id) {
                    if (!payload.password) {
                        delete payload.password;
                    }                    
                    await axios.put(`${API_URL}/user/${id}`, payload);
                } else {
                    await axios.post(`${API_URL}/user`, payload);
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
        filter_role = $('#filter_role').val();
        $('#modal-filter').modal('hide');
        loadData(1);
    });

    $('#btn-reset-filter').on('click', function () {
        $('#filter_role').val('');
        filter_role = '';
        $('#modal-filter').modal('hide');
        loadData(1);
    });
    
});