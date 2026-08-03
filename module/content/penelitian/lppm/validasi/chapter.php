<?php

// Proses Validasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['validasi_chapter'])) {
    $id_chapter = mysqli_real_escape_string($con, $_POST['id_chapter']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $keterangan_validasi = mysqli_real_escape_string($con, $_POST['keterangan_validasi']);

    $update = mysqli_query($con, "UPDATE penelitian_chapter SET status='$status', keterangan_validasi='$keterangan_validasi' WHERE id='$id_chapter'");

    // Masukkan ke pivot tabel jika disetujui (status = 2)
    if ($status == '2') {
        $q_pen = mysqli_query($con, "SELECT nip_dosen FROM penelitian_chapter WHERE id='$id_chapter'");
        $d_pen = mysqli_fetch_array($q_pen);
        if ($d_pen) {
            $nip_dosen = $d_pen['nip_dosen'];
            $date_regis = date('Y-m-d');
            $time_regis = date('H:i:s');
            $registered_by = $nip_dosen;

            // Mencegah duplikasi di tabel pivot
            $cek_pivot = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_chapter' AND nik='$nip_dosen' AND jenis_porto='chapter'");
            if (mysqli_num_rows($cek_pivot) == 0) {
                mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_chapter', '$nip_dosen', '1', 'chapter', '$date_regis', '$time_regis', '$registered_by')");
            }
        }
    }

    if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        if ($update) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Berhasil menyimpan validasi!',
                'new_status' => $status,
                'keterangan' => htmlspecialchars($keterangan_validasi)
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan validasi!']);
        }
        exit;
    }

    $ts_code = isset($folder[4]) ? $folder[4] : '';
    $redirect_url = "/penelitian/" . $folder[2] . "/validasi/" . $ts_code;

    if ($update) {
        $_SESSION['swal_msg'] = 'Berhasil menyimpan validasi!';
        $_SESSION['swal_icon'] = 'success';
    } else {
        $_SESSION['swal_msg'] = 'Gagal menyimpan validasi!';
        $_SESSION['swal_icon'] = 'error';
    }
    header("Location: $redirect_url");
    exit;
}

if (empty($folder[4])) {
    $ta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE active='Y' "));
    $tgl_start = '1970-01-01';
    $tgl_end = '2099-12-31';
} else {
    $sta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE kode='{$folder[4]}' "));
    $tgl_start = $sta ? $sta['tgl_start'] : '1970-01-01';
    $tgl_end = $sta ? $sta['tgl_end'] : '2099-12-31';
}
?>

<div class='row'>
    <div class='col-md-12'>
        <div class='card card-success'>
            <div class='card-body'>
                <form method="GET" action="" id="filterForm">
                    <div class="row">
                        <div class="col-md-12">
                            <select onchange="window.document.location.href=this.options[this.selectedIndex].value;"
                                class='form-control' name='ta'>
                                <option value='/penelitian/<?php echo $folder[2]; ?>/validasi'>-- Pilih TA --</option>
                                <?php
                                $qk = mysqli_query($con, "SELECT * FROM ts WHERE active!='u' ORDER BY kode DESC");
                                while ($k = mysqli_fetch_array($qk)) {
                                    $selected = ($k['kode'] == @$folder[4]) ? 'selected' : '';
                                    ?>
                                    <option value='/penelitian/<?= $folder[2] ?>/validasi/<?= $k['kode'] ?>' <?= $selected ?>><?= $k['thn'] ?>     <?= $k['semester'] ?> (<?= $k['tgl_start'] ?> s.d
                                        <?= $k['tgl_end'] ?>)</option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>

        <!-- Tabel Menunggu Validasi -->
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Book Chapter Menunggu Validasi</h3>
            </div>
            <div class="card-body p-3">
                <table id="tabel-pending" class="table table-bordered table-hover table-striped text-nowrap w-100"
                    style="font-size: 0.9rem;">
                    <thead class="bg-white text-center align-middle">
                        <tr>
                            <th class="align-middle" style="width: 50px;">No</th>
                            <th>Nama Dosen</th>
                            <th>Judul Buku</th>
                            <th>Judul Chapter</th>
                            <th>ISBN</th>
                            <th>Penerbit</th>
                            <th>Tanggal Terbit</th>
                            <th>Status Validasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $q = mysqli_query($con, "SELECT *, penelitian_chapter.id AS id_chapter, penelitian_chapter.status AS status_validasi FROM penelitian_chapter, dosen WHERE penelitian_chapter.nip_dosen=dosen.nik AND tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' AND (penelitian_chapter.status='1' OR penelitian_chapter.status IS NULL OR penelitian_chapter.status='') ORDER BY penelitian_chapter.id DESC");

                        if (mysqli_num_rows($q) > 0) {
                            while ($r = mysqli_fetch_array($q)) {
                                $status_val = empty($r['status_validasi']) ? '1' : $r['status_validasi'];
                                $q_status = mysqli_query($con, "SELECT * FROM kode WHERE value='$status_val' AND kelompok='status_penelitian' LIMIT 1");
                                $d_status = mysqli_fetch_array($q_status);
                                $status_text = $d_status ? $d_status['desc'] : 'Pending';

                                $badge_class = 'badge-warning'; // Pending
                                ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $no ?></td>
                                    <td class="align-middle"><?= $r['name_glr'] ?></td>
                                    <td class="align-middle">
                                        <div class="text-dark text-wrap"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
                                            title="<?= htmlspecialchars($r['judul'], ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($r['judul']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-dark text-wrap"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
                                            title="<?= htmlspecialchars($r['judul_chapter'], ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($r['judul_chapter']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['isbn']) ?></td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['penerbit']) ?></td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['tgl_terbit']) ?></td>
                                    <td class="align-middle text-center">
                                        <span class="badge <?= $badge_class ?> p-2"><?= $status_text ?></span>
                                        <?php if (!empty($r['keterangan_validasi'])) { ?>
                                            <br><small class="text-muted text-wrap" style="max-width: 200px; display: inline-block; margin-top: 5px;"><?= htmlspecialchars($r['keterangan_validasi']) ?></small>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-xs btn-primary shadow-sm" data-toggle="modal"
                                            data-target="#modalValidasiPending<?= $r['id_chapter'] ?>">
                                            Validasi
                                        </button>

                                        <!-- Modal Validasi Pending -->
                                        <div class="modal fade text-left" id="modalValidasiPending<?= $r['id_chapter'] ?>"
                                            tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form method="post" action="" class="form-validasi">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Validasi Book Chapter</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="validasi_chapter" value="1">
                                                            <input type="hidden" name="id_chapter" value="<?= $r['id_chapter'] ?>">

                                                            <div class="form-group">
                                                                <label>Status Validasi</label>
                                                                <select name="status" class="form-control" required>
                                                                    <option value="">-- Pilih Status --</option>
                                                                    <?php
                                                                    $qk_status = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='status_penelitian' AND value IN ('2', '3')");
                                                                    while ($ks = mysqli_fetch_array($qk_status)) {
                                                                        echo "<option value=\"{$ks['value']}\">{$ks['desc']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Keterangan (Opsional, isi jika ditolak/ada
                                                                    catatan)</label>
                                                                <textarea name="keterangan_validasi" class="form-control"
                                                                    rows="3"
                                                                    placeholder="Tuliskan alasan penolakan atau catatan untuk dosen jika ada..."><?= htmlspecialchars($r['keterangan_validasi'] ?? '') ?></textarea>
                                                            </div>

                                                            <hr>
                                                            <h6 class="font-weight-bold text-primary mb-2">Detail Book Chapter
                                                            </h6>
                                                            <div class="p-3 border rounded shadow-sm"
                                                                style="max-height: 45vh; overflow-y: auto; background-color: #f8f9fc;">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-sm table-borderless table-striped text-left mb-0 text-wrap"
                                                                        style="font-size: 0.9rem; table-layout: fixed; word-wrap: break-word;">
                                                                        <tr>
                                                                            <th style="width: 35%">Ketua</th>
                                                                            <td style="width: 65%">:
                                                                                <?= htmlspecialchars($r['name_glr']) ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Judul Buku</th>
                                                                            <td>: <?= htmlspecialchars($r['judul']) ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Judul Chapter</th>
                                                                            <td>: <?= htmlspecialchars($r['judul_chapter'] ?? '-') ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>ISBN</th>
                                                                            <td>: <?= htmlspecialchars($r['isbn'] ?? '-') ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Penerbit</th>
                                                                            <td>: <?= htmlspecialchars($r['penerbit'] ?? '-') ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Tanggal Terbit</th>
                                                                            <td>:
                                                                                <?= htmlspecialchars(empty($r['tgl_terbit']) ? '-' : $r['tgl_terbit']) ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Jumlah Halaman</th>
                                                                            <td>:
                                                                                <?= htmlspecialchars($r['jmlh_halaman'] ?? '-') ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $lit_title = '-';
                                                                        if (!empty($r['link_lit'])) {
                                                                            $q_lit = mysqli_query($con, "SELECT judul_kegiatan FROM penelitian_penelitian WHERE id='{$r['link_lit']}'");
                                                                            $d_lit = mysqli_fetch_array($q_lit);
                                                                            if ($d_lit)
                                                                                $lit_title = $d_lit['judul_kegiatan'];
                                                                        }
                                                                        $abmas_title = '-';
                                                                        if (!empty($r['link_abmas'])) {
                                                                            $q_abmas = mysqli_query($con, "SELECT judul_kegiatan FROM pengabdian_pengabdian WHERE id='{$r['link_abmas']}'");
                                                                            $d_abmas = mysqli_fetch_array($q_abmas);
                                                                            if ($d_abmas)
                                                                                $abmas_title = $d_abmas['judul_kegiatan'];
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <th>Penelitian Terkait</th>
                                                                            <td>: <?= htmlspecialchars($lit_title) ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Pengabdian Terkait</th>
                                                                            <td>: <?= htmlspecialchars($abmas_title) ?></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>

                                                                <h6
                                                                    class="font-weight-bold text-secondary mt-3 mb-2 border-top pt-2">
                                                                    Dokumen Lampiran</h6>
                                                                <div class="table-responsive">
                                                                    <?php
                                                                    $qfile_pivot = mysqli_query($con, "SELECT fd.id, fd.url AS url_file, kode.desc AS type_file, dosen.name_glr 
                                                                                                    FROM file_dosen AS fd
                                                                                                    JOIN kode ON fd.kelompok = kode.kelompok AND fd.value_kode = kode.value 
                                                                                                    JOIN dosen ON fd.nip_dosen = dosen.nik
                                                                                                    WHERE fd.id_cat='{$r['id_chapter']}' AND kode.kelompok='file_chapter'");

                                                                    if (mysqli_num_rows($qfile_pivot) > 0) {
                                                                        ?>
                                                                        <table class="table table-bordered table-sm mb-0 bg-white"
                                                                            style="font-size: 0.85rem;">
                                                                            <thead class="bg-light text-center">
                                                                                <tr>
                                                                                    <th>Jenis Dokumen</th>
                                                                                    <th>Pengunggah</th>
                                                                                    <th class="text-center" style="width: 80px;">
                                                                                        Aksi</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                while ($rf = mysqli_fetch_array($qfile_pivot)) {
                                                                                    $type = !empty($rf['type_file']) ? $rf['type_file'] : 'Dokumen Pendukung';
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="align-middle"><?= $type ?></td>
                                                                                        <td class="align-middle text-center">
                                                                                            <?= $rf['name_glr'] ?></td>
                                                                                        <td class="text-center align-middle">
                                                                                            <a href="<?= @$storage_url . $rf['url_file'] ?>"
                                                                                                target="_blank"
                                                                                                class="btn btn-xs btn-primary">
                                                                                                Unduh
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <?php
                                                                    } else {
                                                                        echo "<span class='text-muted small'>-</span>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Validasi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Ditolak -->
        <div class="card card-outline card-danger mt-4">
            <div class="card-header">
                <h3 class="card-title">Book Chapter Ditolak</h3>
            </div>
            <div class="card-body p-3">
                <table id="tabel-ditolak" class="table table-bordered table-hover table-striped text-nowrap w-100"
                    style="font-size: 0.9rem;">
                    <thead class="bg-white text-center align-middle">
                        <tr>
                            <th class="align-middle" style="width: 50px;">No</th>
                            <th>Nama Dosen</th>
                            <th>Judul Buku</th>
                            <th>Judul Chapter</th>
                            <th>ISBN</th>
                            <th>Penerbit</th>
                            <th>Tanggal Terbit</th>
                            <th>Status Validasi</th>
                            <th>Keterangan Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $q_ditolak = mysqli_query($con, "SELECT *, penelitian_chapter.id AS id_chapter, penelitian_chapter.status AS status_validasi FROM penelitian_chapter, dosen WHERE penelitian_chapter.nip_dosen=dosen.nik AND tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' AND penelitian_chapter.status='3' ORDER BY penelitian_chapter.id DESC");

                        if (mysqli_num_rows($q_ditolak) > 0) {
                            while ($r = mysqli_fetch_array($q_ditolak)) {
                                $status_val = '3';
                                $q_status = mysqli_query($con, "SELECT * FROM kode WHERE value='$status_val' AND kelompok='status_penelitian' LIMIT 1");
                                $d_status = mysqli_fetch_array($q_status);
                                $status_text = $d_status ? $d_status['desc'] : 'Ditolak';

                                $badge_class = 'badge-danger'; // Ditolak
                                ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $no ?></td>
                                    <td class="align-middle"><?= $r['name_glr'] ?></td>
                                    <td class="align-middle">
                                        <div class="text-dark text-wrap"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
                                            title="<?= htmlspecialchars($r['judul'], ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($r['judul']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-dark text-wrap"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
                                            title="<?= htmlspecialchars($r['judul_chapter'], ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($r['judul_chapter']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['isbn']) ?></td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['penerbit']) ?></td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['tgl_terbit']) ?></td>
                                    <td class="align-middle text-center">
                                        <span class="badge <?= $badge_class ?> p-2"><?= $status_text ?></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="text-muted text-wrap" style="max-width: 250px; display: inline-block;">
                                            <?= empty($r['keterangan_validasi']) ? '-' : htmlspecialchars($r['keterangan_validasi']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    $(function () {
        // Init DataTables
        const tableOptions = {
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "info": true,
            "searching": true,
            "scrollX": true,
            "language": {
                "emptyTable": "<p class='text-center text-danger mb-0'>Tidak ada data</p>"
            }
        };

        $("#tabel-pending").DataTable(tableOptions);
        $("#tabel-ditolak").DataTable(tableOptions);

        // AJAX Form Submission
        $('.form-validasi').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let modal = form.closest('.modal');
            let submitBtn = form.find('button[type="submit"]');
            let originalBtnHtml = submitBtn.html();

            // Collect data and add ajax flag
            let formData = form.serialize() + '&ajax=1';

            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: window.location.href, // Current URL
                data: formData,
                dataType: 'json',
                success: function (response) {
                    modal.modal('hide');
                    submitBtn.html(originalBtnHtml).prop('disabled', false);

                    if (response.status == 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        }).then(() => {
                            // Reload the page to reflect changes
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                },
                error: function (xhr, status, error) {
                    modal.modal('hide');
                    submitBtn.html(originalBtnHtml).prop('disabled', false);

                    console.error("AJAX Error:", error);
                    console.error("Response:", xhr.responseText);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Terjadi kesalahan sistem saat menyimpan data.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload(); // Reload anyway on system error to get fresh state
                    });
                }
            });
        });
    });
</script>

<?php if (isset($_SESSION['swal_msg'])): ?>
<script>
    $(function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: '<?= $_SESSION['swal_icon'] ?>',
            title: '<?= $_SESSION['swal_msg'] ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
<?php 
    unset($_SESSION['swal_msg']);
    unset($_SESSION['swal_icon']);
endif; 
?>
