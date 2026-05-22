const API_URL = window.APP.API_URL;
/*
|--------------------------------------------------------------------------
| Axios Token
|--------------------------------------------------------------------------
*/
function setAxiosAuth() {
    const token = localStorage.getItem('token');

    axios.defaults.headers.common['Accept'] = 'application/json';

    if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }
}

/*
|--------------------------------------------------------------------------
| Axios Interceptor
|--------------------------------------------------------------------------
*/
axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (error.response?.status === 401) {
            clearAuth();
            window.location.href = '/login';
        }

        return Promise.reject(error);
    }
);

async function checkToken() {
    const token = localStorage.getItem('token');

    if (!token) {
        clearAuth();
        window.location.href = '/login';
        return false;
    }

    try {
        await axios.get(`${API_URL}/auth/validate`);
        return true;
    } catch (error) {
        clearAuth();
        window.location.href = '/login';
        return false;
    }
}

async function logout() {
    if(confirm('apakah anda yakin?')){
        try {
            await axios.post(`${API_URL}/auth/logout`);
        } catch (error) {
            // abaikan
        }
        clearAuth();
        window.location.href = '/login';
    }
}

function getRoles() {
    return JSON.parse(localStorage.getItem('roles') || '[]');
}

function getActiveRole() {
    return localStorage.getItem('role_default');
}

function hasRole(roleNames) {
    const activeRole = getActiveRole();

    if (!Array.isArray(roleNames)) {
        roleNames = [roleNames];
    }

    return roleNames.includes(activeRole);
}

function initMenu() {
    $('[data-role]').each(function () {
        const allowedRoles = $(this)
            .data('role')
            .split(',');

        if (hasRole(allowedRoles)) {
            $(this).removeClass('d-none');
        }
    });

    if (getRoles().length > 1) {
        $('#menu-ganti-role').removeClass('d-none');
    }
}

/*
|--------------------------------------------------------------------------
| Pagination Helpers
|--------------------------------------------------------------------------
*/

function renderPagination(pagination, targetSelector = '#pagination-role', onPageClick = null) {
    let html = '';

    const current = pagination.current_page;
    const last = pagination.last_page;

    /*
    |--------------------------------------------------------------------------
    | Prev
    |--------------------------------------------------------------------------
    */

    html += `
        <li class="page-item ${current === 1 ? 'disabled' : ''}">
            <a class="page-link pagination-link" href="#" data-page="${current - 1}">
                Prev
            </a>
        </li>
    `;

    /*
    |--------------------------------------------------------------------------
    | Smart Numbers
    |--------------------------------------------------------------------------
    */

    let start = Math.max(1, current - 2);
    let end = Math.min(last, current + 2);

    if (start > 1) {
        html += `
            <li class="page-item">
                <a class="page-link pagination-link" href="#" data-page="1">1</a>
            </li>
        `;

        if (start > 2) {
            html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }
    }

    for (let i = start; i <= end; i++) {
        html += `
            <li class="page-item ${current === i ? 'active' : ''}">
                <a class="page-link pagination-link" href="#" data-page="${i}">
                    ${i}
                </a>
            </li>
        `;
    }

    if (end < last) {
        if (end < last - 1) {
            html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
        }

        html += `
            <li class="page-item">
                <a class="page-link pagination-link" href="#" data-page="${last}">
                    ${last}
                </a>
            </li>
        `;
    }

    /*
    |--------------------------------------------------------------------------
    | Next
    |--------------------------------------------------------------------------
    */

    html += `
        <li class="page-item ${current === last ? 'disabled' : ''}">
            <a class="page-link pagination-link" href="#" data-page="${current + 1}">
                Next
            </a>
        </li>
    `;

    $(targetSelector).html(html);

    if (onPageClick) {
        $(document)
            .off('click', `${targetSelector} .pagination-link`)
            .on('click', `${targetSelector} .pagination-link`, function (e) {
                e.preventDefault();

                const page = $(this).data('page');

                if (!$(this).closest('.page-item').hasClass('disabled')) {
                    onPageClick(page);
                }
            });
    }
}

function renderTableInfo(pagination, infoSelector = '#table-info', pageSelector = '#pagination-info') {
    $(infoSelector).text(
        `Menampilkan ${pagination.from || 0} - ${pagination.to || 0} dari ${pagination.total} data`
    );

    $(pageSelector).text(
        `Halaman ${pagination.current_page} dari ${pagination.last_page}`
    );
}

/*
|--------------------------------------------------------------------------
| Otomatis fetch semua data tanpa set per_page
|--------------------------------------------------------------------------
*/

async function fetchAllPages(endpoint, params = {}) {
    let allData = [];
    let page = 1;
    let lastPage = 1;

    do {
        const res = await axios.get(`${API_URL}/${endpoint}`, {
            params: {
                ...params,
                page
            }
        });

        allData = [...allData, ...res.data.data];
        lastPage = res.data.pagination.last_page;

        page++;
    } while (page <= lastPage);

    return allData;
}

/*
|--------------------------------------------------------------------------
| Pilih Role
|--------------------------------------------------------------------------
*/

function renderPilihRole() {
    const roles = getRoles();
    let html = '';
    roles.forEach(item => {
        const active =
            localStorage.getItem('role_id_default') == item.role_id
                ? 'active'
                : '';

        html += `
            <a
                href="javascript:void(0)"
                class="list-group-item list-group-item-action pilih-role ${active}"
                data-role-id="${item.role_id}"
                data-role-name="${item.role.nama}"
            >
                ${item.role.nama}
            </a>
        `;
    });

    $('#pilih_role').html(`
        <div class="list-group">
            ${html}
        </div>
    `);
}

$(document).on('click', '.pilih-role', function () {
    const roleId = $(this).data('role-id');
    const roleName = $(this).data('role-name');

    localStorage.setItem('role_default', roleName);
    localStorage.setItem('role_id_default', roleId);

    $('#modal-pilih-role').modal('hide');

    window.location.href = '/dashboard';
});

$(document).ready(async function () {
    setAxiosAuth();
    const valid = await checkToken();

    if (!valid) return;

    $('#user-name').text(localStorage.getItem('name') || '-');
    $('#user-role').text(localStorage.getItem('role_default') || '-');

    initMenu();

    $('body').show();


    $('#menu-ganti-role-link').on('click', function () {
        renderPilihRole();
        $('#modal-pilih-role').modal('show');
    });

    $('#btn-batal-pilih-role').on('click', function () {
        $('#modal-pilih-role').modal('hide');
    });    

});