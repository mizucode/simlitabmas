<?php

// Proses Validasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['validasi_patenhki'])) {
    $id_patenhki = mysqli_real_escape_string($con, $_POST['id_patenhki']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $keterangan_validasi = mysqli_real_escape_string($con, $_POST['keterangan_validasi']);

    $q_judul = mysqli_query($con, "SELECT judul FROM penelitian_patenhki WHERE id='$id_patenhki'");
    $d_judul = mysqli_fetch_assoc($q_judul);
    $judul_esc = mysqli_real_escape_string($con, $d_judul['judul'] ?? '');

    $update = mysqli_query($con, "UPDATE penelitian_patenhki SET status='$status', keterangan_validasi='$keterangan_validasi' WHERE judul='$judul_esc'");
    
    // Masukkan ke pivot tabel jika disetujui (status = 2)
    if ($status == '2') {
        $q_pen = mysqli_query($con, "SELECT nip_dosen FROM penelitian_patenhki WHERE id='$id_patenhki'");
        $d_pen = mysqli_fetch_array($q_pen);
        if ($d_pen) {
            $nip_dosen = $d_pen['nip_dosen'];
            $date_regis = date('Y-m-d');
            $time_regis = date('H:i:s');
            $registered_by = $nip_dosen;

            // Mencegah duplikasi di tabel pivot
            $cek_pivot = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_patenhki' AND nik='$nip_dosen' AND jenis_porto='patenhki'");
            if (mysqli_num_rows($cek_pivot) == 0) {
                mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_patenhki', '$nip_dosen', '1', 'patenhki', '$date_regis', '$time_regis', '$registered_by')");
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
                        <select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='ta'>
                            <option value='/penelitian/<?php echo $folder[2]; ?>/validasi'>-- Pilih TA --</option>
                            <?php
                            $qk = mysqli_query($con, "SELECT * FROM ts WHERE active!='u' ORDER BY kode DESC");
                            while ($k = mysqli_fetch_array($qk)) {
                                $selected = ($k['kode'] == @$folder[4]) ? 'selected' : '';
                                ?>
                                <option value='/penelitian/<?= $folder[2] ?>/validasi/<?= $k['kode'] ?>' <?= $selected ?>><?= $k['thn'] ?> <?= $k['semester'] ?> (<?= $k['tgl_start'] ?> s.d <?= $k['tgl_end'] ?>)</option>
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
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Menunggu Validasi (Pending)</h3>
            </div>
            <div class="card-body p-3">
                <table id="tabel-pending" class="table table-bordered table-hover table-striped text-nowrap w-100" style="font-size: 0.9rem;">
                    <thead class="bg-white text-center align-middle">
                        <tr>
                            <th class="align-middle" style="width: 50px;">No</th>
                            <th>Ketua</th>
                            <th>Judul Paten</th>
                            <th>Tingkat Kesiapan (TKT)</th>
                            <th>Status Validasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $q = mysqli_query($con, "SELECT ph.*, ph.id AS id_patenhki, ph.status AS status_validasi, IFNULL(dosen.name_glr, mhs.name) AS name_glr FROM penelitian_patenhki ph INNER JOIN (SELECT MIN(id) as min_id FROM penelitian_patenhki WHERE tgl_paten>='$tgl_start' AND tgl_paten<='$tgl_end' AND (status='1' OR status IS NULL OR status='') GROUP BY judul) p2 ON ph.id = p2.min_id LEFT JOIN dosen ON ph.nip_dosen=dosen.nik LEFT JOIN mhs ON ph.nip_dosen=mhs.nim ORDER BY ph.id DESC");
                        
                        if (mysqli_num_rows($q) > 0) {
                            while ($r = mysqli_fetch_array($q)) {
                                $skema = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_tkt']}' AND kelompok='tkt' LIMIT 1"));
                                
                                // Ambil status text
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
                                        <span class="text-dark text-wrap" style="line-height: 1.4;">
                                            <?= $r['judul'] ?>
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-dark"><?= $skema ? $skema['desc'] : '-' ?></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge <?= $badge_class ?> p-2"><?= $status_text ?></span>
                                        <?php if (!empty($r['keterangan_validasi'])) { ?>
                                            <br><small class="text-muted text-wrap" style="max-width: 200px; display: inline-block; margin-top: 5px;"><?= htmlspecialchars($r['keterangan_validasi']) ?></small>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-xs btn-primary shadow-sm" data-toggle="modal" data-target="#modalValidasiPending<?= $r['id_patenhki'] ?>">
                                            Validasi
                                        </button>
                                        
                                        <!-- Modal Validasi Pending -->
                                        <div class="modal fade text-left" id="modalValidasiPending<?= $r['id_patenhki'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form method="post" action="" class="form-validasi">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Validasi Penelitian</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="validasi_patenhki" value="1">
                                                            <input type="hidden" name="id_patenhki" value="<?= $r['id_patenhki'] ?>">
                                                            
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
                                                                <textarea name="keterangan_validasi" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan atau catatan untuk dosen jika ada..."><?= htmlspecialchars($r['keterangan_validasi']) ?></textarea>
                                                            </div>
                                                            
                                                            <hr>
                                                            <h6 class="font-weight-bold text-primary mb-2">Detail Usulan Penelitian</h6>
                                                            <div class="p-3 border rounded shadow-sm" style="max-height: 45vh; overflow-y: auto; background-color: #f8f9fc;">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless table-striped text-left mb-0 text-wrap" style="font-size: 0.9rem; table-layout: fixed; word-wrap: break-word;">
                                                                        <tr><th style="width: 35%">Ketua Peneliti</th><td style="width: 65%">: <?= htmlspecialchars($r['name_glr']) ?></td></tr>
                                                                        <tr><th>Judul Paten</th><td>: <?= htmlspecialchars($r['judul']) ?></td></tr>
                                                                        <tr><th>Afiliasi</th><td>: <?= htmlspecialchars(empty($r['afiliasi']) ? '-' : $r['afiliasi']) ?></td></tr>
                                                                        <tr><th>Tingkat Kesiapan (TKT)</th><td>: <?= htmlspecialchars($skema ? $skema['desc'] : '-') ?></td></tr>
                                                                        <tr><th>Tahun Usulan</th><td>: <?= htmlspecialchars(empty($r['thn_usulan']) ? '-' : $r['thn_usulan']) ?></td></tr>
                                                                        <tr><th>Tanggal Pelaksanaan</th><td>: <?= htmlspecialchars(empty($r['tgl_paten']) ? '-' : $r['tgl_paten']) ?></td></tr>
                                                                        <tr><th>Lama Kegiatan</th><td>: <?= htmlspecialchars(empty($r['lama_kegiatan']) ? '-' : $r['lama_kegiatan']) ?></td></tr>
                                                                        <tr><th>Tahun Pelaksanaan Ke</th><td>: <?= htmlspecialchars(empty($r['thn_pelaksanaan_ke']) ? '-' : $r['thn_pelaksanaan_ke']) ?></td></tr>
                                                                        
                                                                        <?php
                                                                        $_r = isset($r['']) ? $r[''] : '';
                                                                        $q_peran = mysqli_query($con, "SELECT * FROM kode WHERE value='$_r' AND kelompok='peran_penelitian' LIMIT 1");
                                                                        $d_peran = mysqli_fetch_array($q_peran);
                                                                        $peran_text = $d_peran ? $d_peran['desc'] : $_r;
                                                                        ?>
                                                                        <tr><th>Peran</th><td>: <?= htmlspecialchars(empty($peran_text) ? '-' : $peran_text) ?></td></tr>
                                                                        
                                                                        <tr><th>Dana dari Dikti</th><td>: <?= rupiah((int)($r['dana_dikti'] ?? 0)) ?></td></tr>
                                                                        <tr><th>Dana dari Perguruan Tinggi</th><td>: <?= rupiah((int)($r['dana_kampus'] ?? 0)) ?></td></tr>
                                                                        <tr><th>Dana dari Mandiri</th><td>: <?= rupiah((int)($r['dana_mandiri'] ?? 0)) ?></td></tr>
                                                                        <tr><th>Dana dari Sumber Lainnya</th><td>: <?= rupiah((int)($r['dana_lain'] ?? 0)) ?></td></tr>
                                                                        
                                                                        <?php
                                                                        $total_dana = (int)($r['dana_dikti'] ?? 0) + (int)($r['dana_kampus'] ?? 0) + (int)($r['dana_mandiri'] ?? 0) + (int)($r['dana_lain'] ?? 0);
                                                                        ?>
                                                                        <tr><th>Total Dana</th><td>: <strong><?= rupiah($total_dana) ?></strong></td></tr>
                                                                        
                                                                        <tr><th>Nomor SK Tugas</th><td>: <?= htmlspecialchars(empty($r['no_sk']) ? '-' : $r['no_sk']) ?></td></tr>
                                                                        <tr><th>Tanggal SK Tugas</th><td>: <?= htmlspecialchars(empty($r['tgl_sk']) ? '-' : $r['tgl_sk']) ?></td></tr>
                                                                        <tr><th>Mitra</th><td>: <?= htmlspecialchars(empty($r['mitra']) ? '-' : $r['mitra']) ?></td></tr>
                                                                        <tr><th>Melibatkan Mahasiswa?</th><td>: <?= htmlspecialchars(empty($r['libatkan_mhs']) ? 'Tidak' : $r['libatkan_mhs']) ?></td></tr>
                                                                        <tr><th>Rujukan Tesis/Disertasi?</th><td>: <?= htmlspecialchars(empty($r['rujukan_ta']) ? 'Tidak' : $r['rujukan_ta']) ?></td></tr>
                                                                        <tr><th>Kelompok Bidang</th><td>: <?= htmlspecialchars(empty($r['kelompok_bidang']) ? '-' : $r['kelompok_bidang']) ?></td></tr>
                                                                        <tr><th>Lokasi Penelitian</th><td>: <?= htmlspecialchars(empty($r['lokasi']) ? '-' : $r['lokasi']) ?></td></tr>
                                                                        <?php
                                                                        $q_file = mysqli_query($con, "SELECT f.*, k.desc as jenis_file_desc FROM file_dosen f LEFT JOIN kode k ON f.value_kode = k.value AND k.kelompok = 'file_penelitian' WHERE f.id_cat='{$r['id_patenhki']}' AND f.kelompok='file_penelitian'");
                                                                        if (mysqli_num_rows($q_file) > 0) {
                                                                            while ($d_file = mysqli_fetch_array($q_file)) {
                                                                                $file_url = (strpos($d_file['url'], 'http') === 0) ? $d_file['url'] : $storage_url . $d_file['url'];
                                                                                $jenis = !empty($d_file['jenis_file_desc']) ? htmlspecialchars($d_file['jenis_file_desc']) : 'File Lampiran';
                                                                                echo "<tr><th>{$jenis}</th><td>: <a href=\"{$file_url}\" target=\"_blank\" class=\"btn btn-xs btn-info\"><i class=\"fas fa-download\"></i>Unduh File</a></td></tr>";
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
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
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title">Penelitian Ditolak</h3>
            </div>
            <div class="card-body p-3">
                <table id="tabel-ditolak" class="table table-bordered table-hover table-striped text-nowrap w-100" style="font-size: 0.9rem;">
                    <thead class="bg-white text-center align-middle">
                        <tr>
                            <th class="align-middle" style="width: 50px;">No</th>
                            <th>Ketua</th>
                            <th>Judul Paten</th>
                            <th>Tingkat Kesiapan (TKT)</th>
                            <th>Status Validasi</th>
                            <th class="text-center">Keterangan Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Query with condition tgl_start, tgl_end for Ditolak
                        $q_ditolak = mysqli_query($con, "SELECT penelitian_patenhki.*, penelitian_patenhki.id AS id_patenhki, penelitian_patenhki.status AS status_validasi, IFNULL(dosen.name_glr, mhs.name) AS name_glr FROM penelitian_patenhki LEFT JOIN dosen ON penelitian_patenhki.nip_dosen=dosen.nik LEFT JOIN mhs ON penelitian_patenhki.nip_dosen=mhs.nim WHERE tgl_paten>='$tgl_start' AND tgl_paten<='$tgl_end' AND penelitian_patenhki.status='3' ORDER BY penelitian_patenhki.id DESC");
                        
                        if (mysqli_num_rows($q_ditolak) > 0) {
                            while ($r = mysqli_fetch_array($q_ditolak)) {
                                $skema = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_tkt']}' AND kelompok='tkt' LIMIT 1"));
                                
                                // Ambil status text
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
                                        <span class="text-dark text-wrap" style="line-height: 1.4;">
                                            <?= $r['judul'] ?>
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-dark"><?= $skema ? $skema['desc'] : '-' ?></span>
                                    </td>
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
        $("#tabel-pending").DataTable({
            "paging": true,
            "lengthChange": true,
            "info": true,
            "searching": true,
            "autoWidth": false,
            "scrollX": true,
            "language": {
                "emptyTable": "<p class='text-center text-warning mb-0'>Tidak ada data untuk divalidasi</p>"
            }
        });
        $("#tabel-ditolak").DataTable({
            "paging": true,
            "lengthChange": true,
            "info": true,
            "searching": true,
            "autoWidth": false,
            "scrollX": true,
            "language": {
                "emptyTable": "<p class='text-center text-danger mb-0'>Tidak ada data ditolak</p>"
            }
        });

        // AJAX Form Submit untuk Validasi
        $(document).on('submit', '.form-validasi', function(e) {
            e.preventDefault();
            var form = $(this);
            var modal = form.closest('.modal');
            var btnSubmit = form.find('button[type="submit"], input[type="submit"]');
            var originalBtnText = btnSubmit.html() || btnSubmit.val();
            
            if(btnSubmit.is('input')) {
                btnSubmit.prop('disabled', true).val('Menyimpan...');
            } else {
                btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            }

            $.ajax({
                url: form.attr('action') || window.location.href,
                type: 'POST',
                data: form.serialize() + '&ajax=1',
                dataType: 'text',
                success: function(responseStr) {
                    modal.modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');

                    try {
                        var response = JSON.parse(responseStr.trim());
                        if(response.status == 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            });

                            var triggerBtn = $('button[data-target="#' + modal.attr('id') + '"]');
                            var row = triggerBtn.closest('tr');
                            var tableId = row.closest('table').attr('id');
                            
                            if (response.new_status == '2') {
                                // Jika disetujui, hilangkan dari tabel pending maupun ditolak
                                if(tableId && $.fn.DataTable.isDataTable('#' + tableId)) {
                                    $('#' + tableId).DataTable().row(row).remove().draw(false);
                                } else {
                                    row.fadeOut(400, function(){ $(this).remove(); });
                                }
                            } else if (response.new_status == '3') {
                                // Jika ditolak
                                var dt = $('#' + tableId).DataTable();
                                var dtRow = dt.row(row);
                                
                                // Di tabel ditolak, kita hanya tampilkan badge tanpa keterangan di bawahnya
                                // Update via API DataTables agar data cache ikut berubah
                                dt.cell(dtRow, 4).data('<div class="text-center"><span class="badge badge-danger p-2">Ditolak</span></div>');
                                
                                if (tableId === 'tabel-pending') {
                                    // Pindah dari tabel pending ke tabel ditolak
                                    // Ubah kolom aksi menjadi keterangan
                                    var ketText = response.keterangan && response.keterangan.trim() !== '' ? response.keterangan : '-';
                                    var actionHtml = '<div class="text-center align-middle"><span class="text-muted text-wrap" style="display: inline-block;">' + ketText + '</span></div>';
                                    dt.cell(dtRow, 5).data(actionHtml);
                                    
                                    var rowData = dtRow.data();
                                    dtRow.remove().draw(false);
                                    $('#tabel-ditolak').DataTable().row.add(rowData).draw(false);
                                }
                            }
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    } catch (e) {
                        console.error("AJAX Parse Error. Response:", responseStr);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error Parsing (Lihat Console)',
                            html: '<small>' + responseStr.substring(0, 100) + '...</small>',
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    modal.modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    
                    Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Kesalahan Jaringan (' + error + ')',
                            showConfirmButton: false,
                            timer: 3000
                    });
                },
                complete: function() {
                    if(btnSubmit.is('input')) {
                        btnSubmit.prop('disabled', false).val(originalBtnText);
                    } else {
                        btnSubmit.prop('disabled', false).html(originalBtnText);
                    }
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