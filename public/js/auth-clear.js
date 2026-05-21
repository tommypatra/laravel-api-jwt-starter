function clearAuth() {
    localStorage.removeItem('token');
    localStorage.removeItem('role_default');
    localStorage.removeItem('role_id_default');
    localStorage.removeItem('user_id');
    localStorage.removeItem('user_siakad_id');
    localStorage.removeItem('name');
    localStorage.removeItem('email');
    localStorage.removeItem('roles');
    // localStorage.clear();
    sessionStorage.clear();
}