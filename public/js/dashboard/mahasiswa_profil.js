/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

async function loadData() {
    try {
        const res = await axios.get(`${API_URL}/mahasiswa/profil`);
        renderData(res.data.data);
    } catch (error) {
        console.error(error);
        alert(
            error.response?.data?.message ||
            'Terjadi kesalahan saat mengambil data.'
        );
        window.location.href = '/dashboard';        
    }
}

/*
|--------------------------------------------------------------------------
| Render Data
|--------------------------------------------------------------------------
*/

function renderData(data) {
    $('#name').val(data.name);
    $('#nim').val(data.nim);
    $('#program_studi').val(data.program_studi);
    $('#email').val(data.email);
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
            nim: {
                required: true,
                minlength: 6
            },
        },
        messages: {
            name: {
                required: 'NIM wajib diisi',
                minlength: 'NIM minimal 6 karakter'
            }
        },
        submitHandler: async function (form, event) {
            event.preventDefault();
            try {
                let payload = Object.fromEntries(
                    new FormData(form).entries()
                );
                await axios.put(`${API_URL}/mahasiswa/profil`, payload);
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