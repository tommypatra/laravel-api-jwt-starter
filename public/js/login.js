async function checkToken() {
    const token = localStorage.getItem('token');
    const API_URL = window.APP.API_URL;
    if (!token) {
        clearAuth();
        $('body').show();
        return;
    }
    try {
        const res = await axios.get(`${API_URL}/auth/validate`, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json'
            }
        });
        window.location.href = '/dashboard';
    } catch (error) {
        clearAuth();
        $('body').show();
    }
}

$(document).ready(function () {
    checkToken();
    $('#formAuthentication').validate({
        rules: {
            'input-email': {
                required: true,
                email: true
            },
            'input-password': {
                required: true,
                minlength: 6
            }
        },
        messages: {
            'input-email': {
                required: 'Email wajib diisi',
                email: 'Format email tidak valid'
            },
            'input-password': {
                required: 'Password wajib diisi',
                minlength: 'Minimal 6 karakter'
            }
        },
        errorPlacement: function (error, element) {
            if (element.closest('.input-group').length) {
                error.insertAfter(element.closest('.input-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: async function (form, event) {
            event.preventDefault();
            const API_URL = window.APP.API_URL;
            const $btn = $('#masuk');
            $btn.prop('disabled', true).text('Memproses...');
            try {
                const res = await axios.post(`${API_URL}/auth/login-siakad`, {
                    email: $('#email').val(),
                    password: $('#password').val()
                }, {
                    headers: {
                        Accept: 'application/json'
                    }
                });  
                const data_response=res.data.data;
                redirectLogin(data_response)
            }catch (error) {
                let message = 'Login gagal';
                if (error.response?.data?.message) {
                    message = error.response.data.message;
                } else if (error.response?.data?.errors?.detail) {
                    message = error.response.data.errors.detail;
                }
                alert(message);
            } finally {
                $btn.prop('disabled', false).html(`
                    <span class="d-flex align-items-center justify-content-center gap-2">
                        <i class="bx bxs-key fs-4"></i>
                        <span>Masuk Akun Siakad</span>
                    </span>
                `);
            }
        }
    });

    $('#masuk-gmail').on('click', function (e) {
        e.preventDefault();
        window.location.href = '/api/auth/login-google';
    });
    
    $('#masuk-akun-web').on('click', async function () {
        const API_URL = window.APP.API_URL;
        const $btn = $('#masuk-akun-web');
        const form = $('#formAuthentication');
        if (!form.valid()) {
            return;
        }        
        $btn.prop('disabled', true).text('Memproses...');
        try {
            const res = await axios.post(`${API_URL}/auth/login-web`, {
                email: $('#email').val(),
                password: $('#password').val()
            }, {
                headers: {
                    Accept: 'application/json'
                }
            });
            const data_response = res.data.data;
            redirectLogin(data_response)
        } catch (error) {

            let message = 'Login gagal';
            if (error.response?.data?.message) {
                message = error.response.data.message;
            }
            alert(message);
        } finally {
            $btn.prop('disabled', false).html(`
                <span class="d-flex align-items-center justify-content-center gap-2">
                    <i class="bx bx-globe fs-4"></i>
                    <span>Masuk Akun Web</span>
                </span>
            `);
        }
    });    

    function redirectLogin(data_response){
        localStorage.setItem('token', data_response.token);
        localStorage.setItem('role_default', data_response.role_default);
        localStorage.setItem('role_id_default', data_response.role_id_default);
        localStorage.setItem('user_id', data_response.user_id);
        localStorage.setItem('user_siakad_id', data_response.user_siakad_id);
        localStorage.setItem('email', data_response.email);
        localStorage.setItem('name', data_response.name);
        localStorage.setItem('roles', JSON.stringify(data_response.roles));
        window.location.href = '/dashboard';
    }

});