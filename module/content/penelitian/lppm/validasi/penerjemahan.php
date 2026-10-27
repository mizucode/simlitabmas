<?php

// Proses Validasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['validasi_penerjemahan'])) {
    $id_penerjemahan = mysqli_real_escape_string($con, $_POST['id_penerjemahan']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $keterangan_validasi = mysqli_real_escape_string($con, $_POST['keterangan_validasi']);

    $update = mysqli_query($con, "UPDATE penelitian_penerjemahan SET status='$status', keterangan_validasi='$keterangan_validasi' WHERE id='$id_penerjemahan'");

    // Masukkan ke pivot tabel jika disetujui (status = 2)
    if ($status == '2') {
        $q_pen = mysqli_query($con, "SELECT nip_dosen FROM penelitian_penerjemahan WHERE id='$id_penerjemahan'");
        $d_pen = mysqli_fetch_array($q_pen);
        if ($d_pen) {
            $nip_dosen = $d_pen['nip_dosen'];
            $date_regis = date('Y-m-d');
            $time_regis = date('H:i:s');
            $registered_by = $nip_dosen;

            // Mencegah duplikasi di tabel pivot
            $cek_pivot = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_penerjemahan' AND nik='$nip_dosen' AND jenis_porto='penerjemahan'");
            if (mysqli_num_rows($cek_pivot) == 0) {
                mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_penerjemahan', '$nip_dosen', '1', 'penerjemahan', '$date_regis', '$time_regis', '$registered_by')");
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
                <h3 class="card-title">Penerjemahan Menunggu Validasi</h3>
            </div>
            <div class="card-body p-3">
                <table id="tabel-pending" class="table table-bordered table-hover table-striped text-nowrap w-100"
                    style="font-size: 0.9rem;">
                    <thead class="bg-white text-center align-middle">
                        <tr>
                            <th class="align-middle" style="width: 50px;">No</th>
                            <th>Nama Dosen</th>
                            <th>Judul Terjemahan</th>
                            <th>Judul Asli</th>
                            <th>Penerbit</th>
                            <th>Tanggal Terbit</th>
                            <th>Status Validasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $q = mysqli_query($con, "SELECT penelitian_penerjemahan.*, penelitian_penerjemahan.id AS id_penerjemahan, penelitian_penerjemahan.status AS status_validasi, dosen.name_glr FROM penelitian_penerjemahan LEFT JOIN dosen ON (penelitian_penerjemahan.nip_dosen=dosen.nik OR penelitian_penerjemahan.nip_dosen=dosen.nip OR penelitian_penerjemahan.nip_dosen=dosen.nidn) WHERE tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' AND (penelitian_penerjemahan.status='1' OR penelitian_penerjemahan.status IS NULL OR penelitian_penerjemahan.status='') ORDER BY penelitian_penerjemahan.id DESC");

                        if (mysqli_num_rows($q) > 0) {
                            while ($r = mysqli_fetch_array($q)) {
                                $status_val = empty($r['status_validasi']) ? '1' : $r['status_validasi'];
                                $q_status = mysqli_query($con, "SELECT * FROM kode WHERE value='$status_val' AND kelompok='status_penelitian' LIMIT 1");
                                $d_status = mysqli_fetch_array($q_status);
                                $status_text = $d_status ? $d_status['desc'] : 'Pending';

                                // Fallback pencarian nama dosen dari tabel dosen (name_glr / name)
                                $nama_dosen = $r['name_glr'] ?? '';
                                if (empty($nama_dosen)) {
                                    if (!empty($r['nip_dosen'])) {
                                        $qd = mysqli_query($con, "SELECT name_glr, name FROM dosen WHERE nik='{$r['nip_dosen']}' OR nip='{$r['nip_dosen']}' OR nidn='{$r['nip_dosen']}' LIMIT 1");
                                        if ($dd = mysqli_fetch_array($qd)) {
                                            $nama_dosen = !empty($dd['name_glr']) ? $dd['name_glr'] : $dd['name'];
                                        }
                                    }
                                    if (empty($nama_dosen)) {
                                        $qp = mysqli_query($con, "SELECT d.name_glr, d.name FROM regis_dosen_penelitian r JOIN dosen d ON (r.nik=d.nik OR r.nik=d.nip OR r.nik=d.nidn) WHERE r.id_porto='{$r['id_penerjemahan']}' AND r.jenis_porto='penerjemahan' ORDER BY r.role ASC LIMIT 1");
                                        if ($dp = mysqli_fetch_array($qp)) {
                                            $nama_dosen = !empty($dp['name_glr']) ? $dp['name_glr'] : $dp['name'];
                                        }
                                    }
                                }

                                // Jika dosen tidak ditemukan di tabel dosen, jangan tampilkan data ini
                                if (empty($nama_dosen)) {
                                    continue;
                                }

                                $badge_class = 'badge-warning'; // Pending
                                ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $no ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($nama_dosen) ?></td>
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
                                            title="<?= htmlspecialchars($r['judul_asli'], ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($r['judul_asli']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?= htmlspecialchars($r['penerbit'] ?? '-') ?></td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['tgl_terbit'] ?? '-') ?></td>
                                    <td class="align-middle text-center">
                                        <span class="badge <?= $badge_class ?> p-2"><?= $status_text ?></span>
                                        <?php if (!empty($r['keterangan_validasi'])) { ?>
                                            <br><small class="text-muted text-wrap" style="max-width: 200px; display: inline-block; margin-top: 5px;"><?= htmlspecialchars($r['keterangan_validasi']) ?></small>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-xs btn-primary shadow-sm" data-toggle="modal"
                                            data-target="#modalValidasiPending<?= $r['id_penerjemahan'] ?>">
                                            Validasi
                                        </button>

                                        <!-- Modal Validasi Pending -->
                                        <div class="modal fade text-left" id="modalValidasiPending<?= $r['id_penerjemahan'] ?>"
                                            tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form method="post" action="" class="form-validasi">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Validasi Penerjemahan Buku</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="validasi_penerjemahan" value="1">
                                                            <input type="hidden" name="id_penerjemahan" value="<?= $r['id_penerjemahan'] ?>">

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
                                                                <label>Keterangan (Opsional, isi jika ditolak/ada catatan)</label>
                                                                <textarea name="keterangan_validasi" class="form-control"
                                                                    rows="3"
                                                                    placeholder="Tuliskan alasan penolakan atau catatan untuk dosen jika ada..."><?= htmlspecialchars($r['keterangan_validasi'] ?? '') ?></textarea>
                                                            </div>

                                                            <hr>
                                                            <h6 class="font-weight-bold text-primary mb-2">Detail Penerjemahan</h6>
                                                            <div class="p-3 border rounded shadow-sm"
                                                                style="max-height: 45vh; overflow-y: auto; background-color: #f8f9fc;">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-sm table-borderless table-striped text-left mb-0 text-wrap"
                                                                        style="font-size: 0.9rem; table-layout: fixed; word-wrap: break-word;">
                                                                        <tr>
                                                                            <th style="width: 35%">Dosen</th>
                                                                            <td style="width: 65%">: <?= htmlspecialchars($nama_dosen) ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Judul Terjemahan</th>
                                                                            <td>: <?= htmlspecialchars($r['judul']) ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Judul Asli</th>
                                                                            <td>: <?= htmlspecialchars($r['judul_asli'] ?? '-') ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Penerbit</th>
                                                                            <td>: <?= htmlspecialchars($r['penerbit'] ?? '-') ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>ISBN</th>
                                                                            <td>: <?= htmlspecialchars($r['isbn'] ?? '-') ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Jumlah Halaman</th>
                                                                            <td>: <?= htmlspecialchars($r['jmlh_halaman'] ?? '-') ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Tanggal Terbit</th>
                                                                            <td>: <?= htmlspecialchars(empty($r['tgl_terbit']) ? '-' : $r['tgl_terbit']) ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>File Lampiran</th>
                                                                            <td>:
                                                                                <?php
                                                                                $qfile = mysqli_query($con, "SELECT fd.id, fd.url, kode.desc FROM file_dosen AS fd, kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.id_cat='{$r['id_penerjemahan']}' AND kode.kelompok='file_penerjemahan'");
                                                                                if (mysqli_num_rows($qfile) > 0) {
                                                                                    while ($rfile = mysqli_fetch_array($qfile)) {
                                                                                        $file_url = (strpos($rfile['url'], 'http') === 0) ? $rfile['url'] : (isset($storage_url) ? $storage_url . ltrim($rfile['url'], '/') : '/' . ltrim($rfile['url'], '/'));
                                                                                        echo "<a href='" . htmlspecialchars($file_url) . "' target='_blank' class='btn btn-xs btn-outline-primary mb-1'><i class='fa fa-file'></i> {$rfile['desc']}</a><br>";
                                                                                    }
                                                                                } else {
                                                                                    echo "<span class='text-muted small'>Tidak ada file</span>";
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Batal</button>
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
                <h3 class="card-title">Penerjemahan Ditolak</h3>
            </div>
            <div class="card-body p-3">
                <table id="tabel-ditolak" class="table table-bordered table-hover table-striped text-nowrap w-100"
                    style="font-size: 0.9rem;">
                    <thead class="bg-white text-center align-middle">
                        <tr>
                            <th class="align-middle" style="width: 50px;">No</th>
                            <th>Nama Dosen</th>
                            <th>Judul Terjemahan</th>
                            <th>Judul Asli</th>
                            <th>Penerbit</th>
                            <th>Tanggal Terbit</th>
                            <th>Status Validasi</th>
                            <th>Keterangan Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $q_ditolak = mysqli_query($con, "SELECT penelitian_penerjemahan.*, penelitian_penerjemahan.id AS id_penerjemahan, penelitian_penerjemahan.status AS status_validasi, dosen.name_glr FROM penelitian_penerjemahan LEFT JOIN dosen ON (penelitian_penerjemahan.nip_dosen=dosen.nik OR penelitian_penerjemahan.nip_dosen=dosen.nip OR penelitian_penerjemahan.nip_dosen=dosen.nidn) WHERE tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' AND penelitian_penerjemahan.status='3' ORDER BY penelitian_penerjemahan.id DESC");

                        if (mysqli_num_rows($q_ditolak) > 0) {
                            while ($r = mysqli_fetch_array($q_ditolak)) {

                                // Fallback pencarian nama dosen dari tabel dosen (name_glr / name)
                                $nama_dosen = $r['name_glr'] ?? '';
                                if (empty($nama_dosen)) {
                                    if (!empty($r['nip_dosen'])) {
                                        $qd = mysqli_query($con, "SELECT name_glr, name FROM dosen WHERE nik='{$r['nip_dosen']}' OR nip='{$r['nip_dosen']}' OR nidn='{$r['nip_dosen']}' LIMIT 1");
                                        if ($dd = mysqli_fetch_array($qd)) {
                                            $nama_dosen = !empty($dd['name_glr']) ? $dd['name_glr'] : $dd['name'];
                                        }
                                    }
                                    if (empty($nama_dosen)) {
                                        $qp = mysqli_query($con, "SELECT d.name_glr, d.name FROM regis_dosen_penelitian r JOIN dosen d ON (r.nik=d.nik OR r.nik=d.nip OR r.nik=d.nidn) WHERE r.id_porto='{$r['id_penerjemahan']}' AND r.jenis_porto='penerjemahan' ORDER BY r.role ASC LIMIT 1");
                                        if ($dp = mysqli_fetch_array($qp)) {
                                            $nama_dosen = !empty($dp['name_glr']) ? $dp['name_glr'] : $dp['name'];
                                        }
                                    }
                                }

                                // Jika dosen tidak ditemukan di tabel dosen, jangan tampilkan data ini
                                if (empty($nama_dosen)) {
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $no ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($nama_dosen) ?></td>
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
                                            title="<?= htmlspecialchars($r['judul_asli'], ENT_QUOTES) ?>">
                                            <?= htmlspecialchars($r['judul_asli']) ?>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?= htmlspecialchars($r['penerbit'] ?? '-') ?></td>
                                    <td class="align-middle text-center"><?= htmlspecialchars($r['tgl_terbit'] ?? '-') ?></td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-danger p-2">Ditolak</span>
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
        var dtPending = $("#tabel-pending").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "searching": true,
            "scrollX": true,
            "language": {
                "emptyTable": "<p class='text-center text-muted mb-0'>Tidak ada data penerjemahan yang menunggu validasi</p>"
            }
        });

        var dtDitolak = $("#tabel-ditolak").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "searching": true,
            "scrollX": true,
            "language": {
                "emptyTable": "<p class='text-center text-muted mb-0'>Tidak ada data penerjemahan yang ditolak</p>"
            }
        });

        $(document).on('submit', '.form-validasi', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $modal = $form.closest('.modal');
            var formData = $form.serialize() + '&ajax=1';

            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $modal.modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', res.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                }
            });
        });
    });
</script>
