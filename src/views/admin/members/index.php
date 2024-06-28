<?php
$title = 'Members';
include __DIR__ . '/../fragments/header.php';
include __DIR__ . '/../fragments/navbar.php';
include __DIR__ . '/../fragments/sidebar.php';
?>

<div class="content-wrapper" style="min-height: 1589.56px">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Members</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Members</li>
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
            <input type="text" name="search" class="form-control" placeholder="Cari member..."
                   value="<?php echo htmlspecialchars($search); ?>">
        </div>

        <div class="col-md-2">
            <select name="sort" class="form-control">
                <option value="Nama" <?php echo $sort == 'Nama' ? 'selected' : ''; ?>>Nama</option>
                <option value="Alamat" <?php echo $sort == 'Alamat' ? 'selected' : ''; ?>>Alamat</option>
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
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAddMember">
                Tambah Member
            </button>
            <?php if (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == true): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php unset($_SESSION['message'], $_SESSION['success']); ?>
                </div>
            <?php elseif (isset($_SESSION['message']) && isset($_SESSION['success']) && $_SESSION['success'] == false): ?>
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
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No_Telepon</th>
                        <th>Email</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['NIM']); ?></td>
                                <td><?php echo htmlspecialchars($member['Nama']); ?></td>
                                <td><?php echo htmlspecialchars($member['Alamat']); ?></td>
                                <td><?php echo htmlspecialchars($member['No_Telepon']); ?></td>
                                <td><?php echo htmlspecialchars($member['Email']); ?></td>
                                <td class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modalUpdateMember<?php echo $member['NIM']; ?>">Update</button>
                                    <form class="m-1" method="POST" action="/admin/members/delete"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus member ini?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="nim" value="<?php echo $member['NIM']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>

                                </td>
<!-- modal form update member -->
<div class="modal fade" id="modalUpdateMember<?php echo $member['NIM']; ?>" tabindex="-1"
     aria-labelledby="modalUpdateMemberLabel<?php echo $member['NIM']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/members/update" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUpdateMemberLabel<?php echo $member['NIM']; ?>">Edit Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="nim_lama" value="<?php echo $member['NIM']; ?>">
                    <div class="mb-3">
                        <label for="nim_baru" class="form-label">NIM</label>
                        <input type="number" class="form-control" id="nim_baru" name="nim_baru" required
                               value="<?php echo $member['NIM']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required
                               value="<?php echo $member['Nama']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required
                               value="<?php echo $member['Alamat']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">No Telepon</label>
                        <input type="number" class="form-control" id="no_telepon" name="no_telepon" required
                               value="<?php echo $member['No_Telepon']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                               value="<?php echo $member['Email']; ?>">
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
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data member.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($previousPage > 0): ?>
                        <li class="page-item"><a class="page-link"
                                href="?page=<?= $previousPage ?>&<?= $queryString ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link"
                                href="?page=<?php echo $i; ?>&<?= $queryString ?>">
                                <?php echo $i; ?>
                            </a></li>
                    <?php endfor; ?>
                    <?php if ($nextPage <= $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $nextPage ?>&<?= $queryString ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- modal form create book -->
<div class="modal fade" id="modalAddMember" tabindex="-1" aria-labelledby="modalAddMemberLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/members/store" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddMemberLabel">Tambah Member Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="number" class="form-control" id="nim" name="nim" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">No Telepon</label>
                        <input type="number" class="form-control" id="no_telepon" name="no_telepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
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