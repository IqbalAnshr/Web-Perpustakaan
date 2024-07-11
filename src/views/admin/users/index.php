<?php
$title = 'Users';
include __DIR__ . '/../fragments/header.php';
include __DIR__ . '/../fragments/navbar.php';
include __DIR__ . '/../fragments/sidebar.php';
?>

<div class="content-wrapper" style="min-height: 1589.56px">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <form method="GET" action="" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari user..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="filter" class="form-control">
                            <option value="" <?php echo $filter == '' ? 'selected' : ''; ?>>All</option>
                            <option value="admin" <?php echo $filter == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="pustakawan" <?php echo $filter == 'pustakawan' ? 'selected' : ''; ?>>Pustakawan</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="sort" class="form-control">
                            <option value="ID_User" <?php echo $sort == 'ID_User' ? 'selected' : ''; ?>>ID_User</option>
                            <option value="Username" <?php echo $sort == 'Username' ? 'selected' : ''; ?>>Username</option>
                            <!-- Tambahkan opsi sorting lain sesuai kebutuhan -->
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="order" class="form-control">
                            <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                            <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </form>

            <!--modal add Member-->
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddUser">
                Tambah User
            </button>
            <?php if (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == true) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php unset($_SESSION['message'], $_SESSION['success']); ?>
                </div>
            <?php elseif (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == false) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php unset($_SESSION['message'], $_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID_User</th>
                        <th>Username</th>
                        <th>Role</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['ID_User']); ?></td>
                                <td><?php echo htmlspecialchars($user['Username']); ?></td>
                                <td><?php echo htmlspecialchars($user['Role']); ?></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalUpdateUser<?php echo $user['ID_User']; ?>">Update</button>
                                    <form class="m-1" method="POST" action="/admin/users/delete" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_user" value="<?php echo $user['ID_User']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>

                                </td>

                                <!-- modal form update member -->
                                <div class="modal fade" id="modalUpdateUser<?php echo $user['ID_User']; ?>" tabindex="-1" aria-labelledby="modalUpdateUserLabel<?php echo $user['ID_User']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="/admin/users/update" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalUpdateUserLabel">Edit User</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_user" value="<?php echo $user['ID_User'] ?>">
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" id="username" name="username" required value="<?php echo $user['Username']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Update Password</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">Role</label>
                                                        <select class="custom-select" id="role" name="role">
                                                            <option value="admin" <?php echo $user['Role'] == "admin" ? "selected" : "" ?>>Admin</option>
                                                            <option value="pustakawan" <?php echo $user['Role'] == "pustakawan" ? "selected" : "" ?>>Pustakawan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- end of modal form update book -->
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data user.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($previousPage > 0) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $previousPage ?>&<?= $queryString ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&<?= $queryString ?>">
                                <?php echo $i; ?>
                            </a></li>
                    <?php endfor; ?>
                    <?php if ($nextPage <= $totalPages) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&<?= $queryString ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- modal form create book -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/users/store" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddUserLabel">Tambah User Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="custom-select" id="role" name="role">
                            <option selected>Chose Role</option>
                            <option value="admin">Admin</option>
                            <option value="pustakawan">Pustakawan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" value="submit" name="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../fragments/control_sidebar.php';
include __DIR__ . '/../fragments/footer.php';
?>