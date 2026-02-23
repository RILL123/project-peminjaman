<?php
include '../model/koneksi.php';

// Tambah user
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $query = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password', '$role')";
    mysqli_query($koneksi, $query);
    header("Location: ../view/admin/crud_user.php?notif=added");
    exit();
}

// Edit user
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $update_password = '';
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update_password = ", password='$password'";
    }
    $query = "UPDATE users SET nama='$nama', username='$username', role='$role' $update_password WHERE id_user='$id'";
    mysqli_query($koneksi, $query);
    header("Location: ../view/admin/crud_user.php?notif=edited");
    exit();
}

// Hapus user
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM users WHERE id_user='$id'";
    mysqli_query($koneksi, $query);
    header("Location: ../view/admin/crud_user.php?notif=deleted");
    exit();
}
?>
