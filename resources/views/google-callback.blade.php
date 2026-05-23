<!DOCTYPE html>
<html>

<head>
    <title>Login...</title>
</head>

<body>
    <script>
        const data_response = @json($data);
        localStorage.setItem('token', data_response.token);
        localStorage.setItem('role_default', data_response.role_default);
        localStorage.setItem('role_id_default', data_response.role_id_default);
        localStorage.setItem('user_id', data_response.user_id);
        localStorage.setItem('user_siakad_id', data_response.user_siakad_id);
        localStorage.setItem('email', data_response.email);
        localStorage.setItem('name', data_response.name);
        localStorage.setItem('roles', JSON.stringify(data_response.roles));
        window.location.href = '/dashboard';
    </script>

</body>

</html>
