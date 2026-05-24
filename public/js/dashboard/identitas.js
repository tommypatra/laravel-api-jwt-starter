/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

async function loadData() {
    try {
        const res = await axios.get(`${API_URL}/identitas`);
        renderData(res.data.data);
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
| Render Data
|--------------------------------------------------------------------------
*/

function renderData(data) {
    $('#name').val(data.name);
    $('#email').val(data.email);
    $('#password').val("");
}


$(document).ready(function () {
    /*
    |--------------------------------------------------------------------------
    | Init
    |--------------------------------------------------------------------------
    */
    loadData();

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
                minlength: 'Password minimal 6 karakter'
            }
        },
        submitHandler: async function (form, event) {
            event.preventDefault();
            try {
                let payload = Object.fromEntries(
                    new FormData(form).entries()
                );

                if (payload.password=="") {
                    delete payload.password;
                }                    

                await axios.put(`${API_URL}/identitas`, payload);
                $('#password').val("");
                alert('data berhasil diperbaharui');
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
    | Refresh
    |--------------------------------------------------------------------------
    */

    $('#btn-refresh').on('click', function () {
        loadData();
    });
    
});