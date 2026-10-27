<?php
$id_porto = (int) @$folder[4];

// Mengambil informasi pembicara
$q_porto = mysqli_query($con, "
    SELECT p.*, d.name_glr, 
           kk.desc as nama_kategori_kegiatan, 
           kp.desc as nama_kategori_pembicara,
           kt.desc as nama_tingkat 
    FROM pengabdian_pembicara p 
    LEFT JOIN dosen d ON p.nip_dosen = d.nik 
    LEFT JOIN kode kk ON p.id_kategori_kegiatan = kk.value AND kk.kelompok = 'frek_kegiatan' 
    LEFT JOIN kode kp ON p.id_kategori_pembicara = kp.value AND kp.kelompok = 'pembicara'
    LEFT JOIN kode kt ON p.id_tingkat = kt.value AND kt.kelompok = 'tingkat'
    WHERE p.id = '$id_porto'
");
$porto = mysqli_fetch_array($q_porto);

if (!$porto) {
  echo "<div class='alert alert-danger'>Data pembicara tidak ditemukan.</div>";
  return;
}

// Mencegah pivot kosong jika pengusul utama belum masuk di regis_dosen_penelitian
$nip_pem = mysqli_real_escape_string($con, $porto['nip_dosen']);
$cek_main = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_porto' AND nik='$nip_pem' AND (jenis_porto='pembicara' OR jenis_porto='Pembicara')");
if (mysqli_num_rows($cek_main) == 0 && !empty($nip_pem)) {
  $tgl_reg = date('Y-m-d');
  $jam_reg = date('H:i:s');
  $ses_user = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
  mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_porto', '$nip_pem', '1', 'pembicara', '$tgl_reg', '$jam_reg', '$ses_user')");
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-outline card-danger">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-users mr-2"></i> Tim Dosen Pembicara / Narasumber
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal"
            data-target="#tambahAnggota">
            <i class="fas fa-user-plus mr-1"></i> Tambah Anggota
          </button>
          <a href="/pengabdian/<?= @$folder[2] ?>/edit/<?= $porto['id'] ?>"
            class="btn btn-sm btn-warning shadow-sm ml-1">
            <i class="fas fa-edit mr-1"></i> Edit
          </a>
          <a href="/delete/pengabdian/pembicara/<?= $porto['id'] ?>" class="btn btn-sm btn-danger shadow-sm ml-1"
            onclick="return confirm('Yakin ingin menghapus seluruh data pembicara ini?');">
            <i class="fas fa-trash mr-1"></i> Hapus
          </a>
        </div>
      </div>
      <div class="card-body p-4">

        <!-- Info Box Pembicara -->
        <div class="card mb-4 shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover mb-0 w-100" style="font-size: 0.95rem;">
                <thead style="background-color: #f8f9fa;">
                  <tr>
                    <th colspan="3" class="text-dark py-2" style="font-size: 1.05rem;">
                      <i class="fas fa-info-circle text-primary mr-2"></i> <strong>Informasi Pembicara</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 250px; font-weight: 600; color: #444;">Judul Makalah/Materi</td>
                    <td style="width: 10px; text-align: center;">:</td>
                    <td class="font-weight-bold text-dark"><?= htmlspecialchars((string)$porto['judul']) ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Nama Dosen Utama</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars((string)$porto['name_glr']) ?> (<?= $porto['nip_dosen'] ?>)</td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Nama Pertemuan / Kegiatan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars((string)$porto['nama_pertemuan']) ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Penyelenggara</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars((string)$porto['penyelenggara']) ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tingkat</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars((string)($porto['nama_tingkat'] ?? '-')) ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tanggal Pelaksanaan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars((string)$porto['tgl_pelaksanaan']) ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">No / Tanggal SK</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars((string)$porto['no_sk']) ?> / <?= htmlspecialchars((string)$porto['tgl_sk']) ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Section Anggota Pivot -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="m-0 text-dark font-weight-bold">
            <i class="fas fa-list-ul mr-2 text-primary"></i>Daftar Anggota Dosen Terdaftar
          </h6>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped text-nowrap w-100" style="font-size: 0.9rem;">
            <thead class="bg-white text-center align-middle">
              <tr>
                <th class="align-middle" style="width: 50px;">No</th>
                <th class="align-middle">NIK / NIP</th>
                <th class="align-middle">Nama Dosen & Gelar</th>
                <th class="align-middle">File Lampiran</th>
                <th class="align-middle">Tanggal Regis</th>
                <th class="text-center align-middle" style="width: 80px;">Aksi</th>
              </tr>
            </thead>
            <tbody style="font-size: 0.9rem;">
              <?php
              $judul_porto_esc = mysqli_real_escape_string($con, $porto['judul']);
              $q_regis = mysqli_query($con, "
                  SELECT r.*, d.name_glr, d.name, k2.`desc` as file_desc,
                         (SELECT url FROM file_dosen 
                          WHERE nip_dosen = r.nik AND kelompok = 'file_pembicara' 
                          AND (id_cat = r.id_porto OR id_cat IN (SELECT id FROM pengabdian_pembicara WHERE nip_dosen = r.nik AND (judul = '$judul_porto_esc' OR id = r.id_porto)))
                          ORDER BY id DESC LIMIT 1) as url_file,
                         (SELECT value_kode FROM file_dosen 
                          WHERE nip_dosen = r.nik AND kelompok = 'file_pembicara' 
                          AND (id_cat = r.id_porto OR id_cat IN (SELECT id FROM pengabdian_pembicara WHERE nip_dosen = r.nik AND (judul = '$judul_porto_esc' OR id = r.id_porto)))
                          ORDER BY id DESC LIMIT 1) as value_kode
                  FROM regis_dosen_penelitian r 
                  LEFT JOIN dosen d ON r.nik = d.nik 
                  LEFT JOIN kode k2 ON (
                      SELECT value_kode FROM file_dosen 
                      WHERE nip_dosen = r.nik AND kelompok = 'file_pembicara' 
                      AND (id_cat = r.id_porto OR id_cat IN (SELECT id FROM pengabdian_pembicara WHERE nip_dosen = r.nik AND (judul = '$judul_porto_esc' OR id = r.id_porto)))
                      ORDER BY id DESC LIMIT 1
                  ) = k2.value AND k2.kelompok = 'file_pembicara'
                  WHERE r.id_porto = '$id_porto' AND (r.jenis_porto = 'pembicara' OR r.jenis_porto = 'Pembicara') 
                  ORDER BY r.id ASC
              ");

              if ($q_regis && mysqli_num_rows($q_regis) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_array($q_regis)) {
                  $type_text = !empty($row['file_desc']) ? $row['file_desc'] : (!empty($row['value_kode']) ? $row['value_kode'] : 'File Lampiran');
                  ?>
                  <tr>
                    <td class="text-center align-middle"><?= $no ?></td>
                    <td class="align-middle"><?= htmlspecialchars((string)$row['nik']) ?></td>
                    <td class="align-middle"><span class="text-dark font-weight-bold"><?= htmlspecialchars((string)$row['name_glr']) ?></span></td>
                    <td class="align-middle text-center">
                      <?php if (!empty($row['url_file'])): ?>
                        <a href="<?= $storage_url . $row['url_file'] ?>" target="_blank" class="btn btn-xs btn-primary">
                          <i class="fas fa-download mr-1"></i> <?= htmlspecialchars((string)$type_text) ?>
                        </a>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-center"><?= htmlspecialchars((string)$row['date_regis']) ?></td>
                    <td class="text-center align-middle">
                      <button type="button" class="btn btn-xs btn-info shadow-sm" title="Edit Anggota" data-toggle="modal"
                        data-target="#editAnggota<?= $row['id'] ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                      <a href="/delete/pengabdian/anggota_pembicara/<?= $row['id'] ?>" class="btn btn-xs btn-danger shadow-sm" title="Hapus Anggota"
                        onclick="return confirm('Yakin ingin menghapus anggota ini?');">
                        <i class="fas fa-trash"></i>
                      </a>
                    </td>
                  </tr>

                  <!-- Modal Edit -->
                  <div class="modal fade" id="editAnggota<?= $row['id'] ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Edit Anggota Pembicara</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="/update/pengabdian/anggota_pembicara" method="POST" enctype="multipart/form-data">
                          <div class="modal-body text-left">
                            <input type="hidden" name="id_regis" value="<?= $row['id'] ?>">
                            <input type="hidden" name="id_porto" value="<?= $id_porto ?>">
                            <input type="hidden" name="jenis_porto" value="pembicara">
                            <input type="hidden" name="role" value="<?= htmlspecialchars((string)($row['role'] ?? '2')) ?>">
                            <div class="form-group">
                              <label>Dosen</label>
                              <input type="text" class="form-control"
                                value="<?= $row['nik'] ?> - <?= htmlspecialchars((string)$row['name_glr'], ENT_QUOTES) ?>" readonly>
                            </div>
                            <div class="form-group">
                              <label>Jenis File</label>
                              <select class="form-control" name="type_file">
                                <option value="">-- Pilih Jenis File --</option>
                                <?php
                                $q_kf = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_pembicara'");
                                while ($rkf = mysqli_fetch_array($q_kf)) {
                                  $sel_f = ($rkf['value'] == $row['value_kode']) ? 'selected' : '';
                                  echo "<option value='{$rkf['value']}' {$sel_f}>{$rkf['desc']}</option>";
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Ganti File Lampiran (Opsional)</label>
                              <input type="file" name="file" class="form-control-file">
                              <small class="form-text text-muted">Abaikan jika tidak ingin mengubah berkas.</small>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <?php
                  $no++;
                }
              } else {
                echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada anggota dosen terdaftar.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="tambahAnggota">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Anggota Pembicara</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/insert/pengabdian/anggota_pembicara" method="POST" enctype="multipart/form-data">
        <div class="modal-body text-left">
          <input type="hidden" name="id_porto" value="<?= $id_porto ?>">
          <input type="hidden" name="jenis_porto" value="pembicara">
          <input type="hidden" name="role" value="2">
          <div class="form-group">
            <label>Pilih Dosen <span class="text-danger">*</span></label>
            <select class="form-control select2-dosen" name="nik" style="width: 100%;" required>
              <option value="">-- Pilih Dosen --</option>
              <?php
              $qdosen = mysqli_query($con, "SELECT nik, name_glr FROM dosen ORDER BY name_glr ASC");
              while ($rd = mysqli_fetch_array($qdosen)) {
                echo "<option value='{$rd['nik']}'>{$rd['nik']} - " . htmlspecialchars($rd['name_glr']) . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Jenis File</label>
            <select class="form-control" name="type_file">
              <option value="">-- Pilih Jenis File --</option>
              <?php
              $q_kf = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_pembicara'");
              while ($rkf = mysqli_fetch_array($q_kf)) {
                echo "<option value='{$rkf['value']}'>{$rkf['desc']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>File Lampiran</label>
            <input type="file" name="file" class="form-control-file">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Tambah Anggota</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(function () {
    if ($.fn.select2) {
      $('.select2-dosen').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#tambahAnggota')
      });
    }
  });
</script>
