async function checkToken() {
    const token = localStorage.getItem('token');
    const API_URL = window.APP.API_URL;
    if (!token) {
        clearAuth();
        window.location.href = '/login';
        return;
    }
    try {
        const res = await axios.get(`${API_URL}/check-token`, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json'
            }
        });
        $('body').show();
    } catch (error) {
        clearAuth();
        window.location.href = '/login';
    }
}

async function logout() {
    const token = localStorage.getItem('token');
    try {
        await axios.post(`${window.APP.API_URL}/logout`, {}, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    } catch (e) {
        // abaikan
    }
    clearAuth();
    window.location.href = '/login';
}

$(document).ready(function () {
    checkToken();
    $('#user-name').text(localStorage.getItem('name'));
    $('#user-role').text(localStorage.getItem('role_default'));
});