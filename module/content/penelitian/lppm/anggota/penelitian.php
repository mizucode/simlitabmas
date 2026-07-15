<?php
$id_porto = (int) @$folder[4];

// Mengambil informasi penelitian
$q_porto = mysqli_query($con, "
    SELECT p.*, d.name_glr, 
           s.desc as nama_skema, 
           kp.desc as nama_peran 
    FROM penelitian_penelitian p 
    LEFT JOIN dosen d ON p.nip_dosen = d.nik 
    LEFT JOIN kode s ON p.id_skema = s.value AND s.kelompok = 'skema_penelitian' 
    LEFT JOIN kode kp ON p.id_peran = kp.value AND kp.kelompok = 'peran_penelitian'
    WHERE p.id = '$id_porto'
");
$porto = mysqli_fetch_array($q_porto);

if (!$porto) {
  echo "<div class='alert alert-danger'>Data penelitian tidak ditemukan.</div>";
  return;
}

$nama_peran_display = !empty($porto['nama_peran']) ? $porto['nama_peran'] : ($porto['id_peran'] ?? '');
?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-outline card-danger">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-users mr-2"></i> Tim Dosen Peneliti
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal"
            data-target="#tambahAnggota">
            <i class="fas fa-user-plus mr-1"></i> Tambah Anggota
          </button>
          <a href="/penelitian/<?= @$folder[2] ?>/edit/<?= $porto['id'] ?>"
            class="btn btn-sm btn-warning shadow-sm ml-1">
            <i class="fas fa-edit mr-1"></i> Edit
          </a>
          <a href="/delete/penelitian/penelitian/<?= $porto['id'] ?>" class="btn btn-sm btn-danger shadow-sm ml-1"
            onclick="return confirm('Yakin ingin menghapus seluruh data penelitian ini?');">
            <i class="fas fa-trash mr-1"></i> Hapus
          </a>
        </div>
      </div>
      <div class="card-body p-4">

        <!-- Info Box Penelitian -->
        <div class="card mb-4 shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover mb-0 w-100" style="font-size: 0.95rem;">
                <!-- Section 1: Informasi Umum -->
                <thead style="background-color: #f8f9fa;">
                  <tr>
                    <th colspan="3" class="text-dark py-2" style="font-size: 1.05rem;">
                      <i class="fas fa-info-circle text-primary mr-2"></i> <strong>Informasi Umum</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 250px; font-weight: 600; color: #444;">Judul Kegiatan</td>
                    <td style="width: 10px; text-align: center;">:</td>
                    <td class="font-weight-bold text-dark">
                      <?= htmlspecialchars($porto['judul_kegiatan'] ?? '', ENT_QUOTES) ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Afiliasi</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['afiliasi'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Skema</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['nama_skema'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Kelompok Bidang</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['kelompok_bidang'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Lokasi Penelitian</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['lokasi'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tahun Usulan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['thn_usulan'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Lama Kegiatan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['lama_kegiatan'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tahun Pelaksanaan ke</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['thn_pelaksanaan_ke'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Melibatkan Mahasiswa</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['libatkan_mhs'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Rujukan Tesis/Disertasi</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['rujukan_ta'] ?? '') ?></td>
                  </tr>
                </tbody>

                <!-- Section 2: Administrasi & Pendanaan -->
                <thead style="background-color: #f8f9fa;">
                  <tr>
                    <th colspan="3" class="text-dark border-top py-2" style="font-size: 1.05rem;">
                      <i class="fas fa-file-contract text-success mr-2"></i> <strong>Administrasi & Pendanaan</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>

                  <tr>
                    <td style="font-weight: 600; color: #444;">Nomor SK Tugas</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['no_sk'] ?? '-') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tanggal SK Tugas</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['tgl_sk'] ?? '-') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tanggal Mulai</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['tgl_mulai_kegiatan'] ?? '-') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Mitra</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['mitra'] ?? '-') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Dana dari Dikti</td>
                    <td style="text-align: center;">:</td>
                    <td class="font-weight-bold text-dark">Rp
                      <?= number_format((float) ($porto['dana_dikti'] ?? 0), 0, ',', '.') ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Dana Perguruan Tinggi</td>
                    <td style="text-align: center;">:</td>
                    <td class="font-weight-bold text-dark">Rp
                      <?= number_format((float) ($porto['dana_kampus'] ?? 0), 0, ',', '.') ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Dana Mandiri</td>
                    <td style="text-align: center;">:</td>
                    <td class="font-weight-bold text-dark">Rp
                      <?= number_format((float) ($porto['dana_mandiri'] ?? 0), 0, ',', '.') ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Dana Sumber Lain</td>
                    <td style="text-align: center;">:</td>
                    <td class="font-weight-bold text-dark">Rp
                      <?= number_format((float) ($porto['dana_lain'] ?? 0), 0, ',', '.') ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="m-0 text-dark">
            <i class="fas fa-list-ul mr-2 text-primary"></i>Daftar Anggota Terdaftar
          </h6>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped text-nowrap w-100" style="font-size: 0.9rem;">
            <thead class="bg-white text-center align-middle">
              <tr>
                <th class="align-middle" style="width: 50px;">No</th>
                <th class="align-middle">NIK</th>
                <th class="align-middle">Nama Dosen & Gelar</th>
                <th class="align-middle">Peran</th>
                <th class="align-middle">File Lampiran</th>
                <th class="align-middle">Tanggal Regis</th>
                <th class="text-center align-middle" style="width: 80px;">Aksi</th>
              </tr>
            </thead>
            <tbody style="font-size: 0.9rem;">
              <?php
              $q_regis = mysqli_query($con, "
                  SELECT r.*, d.name_glr, d.name, f.url as url_file, k.`desc` as peran_desc, k2.`desc` as file_desc 
                  FROM regis_dosen_penelitian r 
                  LEFT JOIN dosen d ON r.nik = d.nik 
                  LEFT JOIN (
                      SELECT id_cat, nip_dosen, kelompok, MAX(url) as url 
                      FROM file_dosen 
                      GROUP BY id_cat, nip_dosen, kelompok
                  ) f ON f.id_cat = r.id_porto AND f.nip_dosen = r.nik AND f.kelompok = 'file_penelitian' 
                  LEFT JOIN kode k ON r.role = k.value AND k.kelompok = 'peran_penelitian' 
                  LEFT JOIN kode k2 ON r.type_file = k2.value AND k2.kelompok = 'file_penelitian'
                  WHERE r.id_porto = '$id_porto' AND r.jenis_porto = 'Penelitian' 
                  ORDER BY r.id ASC
              ");

              if (mysqli_num_rows($q_regis) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_array($q_regis)) {
                  $type_text = !empty($row['file_desc']) ? $row['file_desc'] : (!empty($row['type_file']) ? $row['type_file'] : 'Berkas');
                  $prodi_str = "";
                  ?>
                  <tr>
                    <td class="text-center align-middle"><?= $no ?></td>
                    <td class="align-middle"><?= $row['nik'] ?></td>
                    <td class="align-middle"><span class="text-dark"><?= $row['name_glr'] ?></span><?= $prodi_str ?></td>
                    <td class="align-middle text-center">
                      <?= !empty($row['peran_desc']) ? $row['peran_desc'] : $row['role'] ?>
                    </td>
                    <td class="align-middle text-center">
                      <?php if (!empty($row['url_file'])): ?>
                        <a href="<?= $storage_url . $row['url_file'] ?>" target="_blank" class="btn btn-xs btn-primary">
                          <i class="fas fa-download mr-1"></i> <?= $type_text ?>
                        </a>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-center">
                      <?= $row['date_regis'] ?>

                    </td>
                    <td class="text-center align-middle">
                      <button type="button" class="btn btn-xs btn-info shadow-sm" title="Edit Anggota" data-toggle="modal"
                        data-target="#editAnggota<?= $row['id'] ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button type="button" class="btn btn-xs btn-danger shadow-sm" title="Hapus Anggota"
                        data-toggle="modal" data-target="#hapusAnggota<?= $row['id'] ?>">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>

                  <!-- Modal Edit -->
                  <div class="modal fade" id="editAnggota<?= $row['id'] ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Edit Anggota</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body text-left">
                          <form action="/update/penelitian/anggota" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_regis" value="<?= $row['id'] ?>">
                            <input type="hidden" name="id_porto" value="<?= $id_porto ?>">
                            <div class="form-group">
                              <label>Dosen</label>
                              <input type="text" class="form-control"
                                value="<?= $row['nik'] ?> - <?= htmlspecialchars($row['name_glr'], ENT_QUOTES) ?>" readonly>
                            </div>
                            <div class="form-group">
                              <label>Peran <span class="text-danger">*</span></label>
                              <select class="form-control" name="role" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $qperan = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='peran_penelitian' ORDER BY id");
                                while ($kp = mysqli_fetch_array($qperan)) {
                                  $sel = ($row['role'] == $kp['value'] || $row['role'] == $kp['desc']) ? 'selected' : '';
                                  echo "<option value='{$kp['value']}' {$sel}>{$kp['desc']}</option>";
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Jenis File <span class="text-danger">*</span></label>
                              <select class="form-control" name="type_file" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $qkode_file = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_penelitian' ORDER BY id");
                                while ($kf = mysqli_fetch_array($qkode_file)) {
                                  $selected = ($row['type_file'] == $kf['desc']) ? 'selected' : '';
                                  echo "<option value='{$kf['desc']}' {$selected}>{$kf['desc']}</option>";
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Ubah File Lampiran (Biarkan kosong jika tidak diubah)</label>
                              <input type="file" class="form-control" name="file" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="text-right mt-3">
                              <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan
                                Perubahan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Hapus -->
                  <div class="modal fade" id="hapusAnggota<?= $row['id'] ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Hapus Anggota</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body text-left">
                          <p>Apakah Anda yakin ingin menghapus <b><?= htmlspecialchars($row['name_glr'], ENT_QUOTES) ?></b>
                            dari penelitian ini?</p>
                          <form action="/delete/penelitian/anggota/<?= $row['id'] ?>" method="POST">
                            <div class="text-right mt-4">
                              <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Ya,
                                Hapus</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  $no++;
                }
              } else {
                // Fallback jika belum ada data di regis_dosen_penelitian (data lama sebelum migrasi)
                $q_orig = mysqli_query($con, "SELECT p.*, d.name_glr, d.nik FROM penelitian_penelitian p LEFT JOIN dosen d ON p.nip_dosen = d.nik WHERE p.id = '$id_porto'");
                if ($orig = mysqli_fetch_array($q_orig)) {
                  $peran_desc = 'Ketua';
                  $qp = mysqli_query($con, "SELECT * FROM kode WHERE value='{$orig['id_peran']}' AND kelompok='peran_penelitian' LIMIT 1");
                  if ($rp = mysqli_fetch_array($qp)) {
                    $peran_desc = $rp['desc'];
                  }
                  $prodi_str = "";
                  ?>
                  <tr>
                    <td class="text-center align-middle">1</td>
                    <td class="align-middle text-secondary"><?= $orig['nik'] ?></td>
                    <td class="align-middle"><span class="text-dark"><?= $orig['name_glr'] ?></span><?= $prodi_str ?></td>
                    <td class="align-middle">
                      <span class="badge badge-warning"><i class="fas fa-star mr-1 text-danger"></i>
                        <?= $peran_desc ?></span>
                      <small class="d-block text-muted mt-1" style="font-size: 0.75rem;">(Data Lama - Belum Migrasi)</small>
                    </td>
                    <td class="align-middle text-muted">Belum Migrasi</td>
                    <td class="align-middle text-muted"><i class="fas fa-exclamation-circle mr-1 text-warning"></i> Menunggu
                      Migrasi</td>
                    <td class="text-center align-middle">
                      <button type="button" class="btn btn-xs btn-secondary shadow-sm" disabled title="Data lama">
                        <i class="fas fa-lock"></i>
                      </button>
                    </td>
                  </tr>
                  <?php
                } else {
                  ?>
                  <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                      <i class="fas fa-folder-open fa-3x mb-3 d-block" style="color: #dee2e6;"></i>
                      Belum ada data anggota dosen untuk penelitian ini.
                    </td>
                  </tr>
                  <?php
                }
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
        <h4 class="modal-title">Tambah Anggota Dosen</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/insert/penelitian/anggota" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_porto" value="<?= $id_porto ?>">
          <div class="form-group">
            <label>Dosen <span class="text-danger">*</span></label>
            <select class="form-control select2-dosen" name="nik" style="width: 100%;" required>
              <option value="">-- Pilih Dosen --</option>
              <?php
              $qdosen = mysqli_query($con, "SELECT nik, name_glr FROM dosen ORDER BY name");
              while ($d = mysqli_fetch_array($qdosen)) {
                echo "<option value='{$d['nik']}'>{$d['nik']} - {$d['name_glr']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Peran <span class="text-danger">*</span></label>
            <select class="form-control" name="role" required>
              <option value="">-- Pilih --</option>
              <?php
              $qperan = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='peran_penelitian' ORDER BY id");
              while ($kp = mysqli_fetch_array($qperan)) {
                echo "<option value='{$kp['value']}'>{$kp['desc']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Jenis File <span class="text-danger">*</span></label>
            <select class="form-control" name="type_file" required>
              <option value="">-- Pilih --</option>
              <?php
              $qkode_file_t = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_penelitian' ORDER BY id");
              while ($kf = mysqli_fetch_array($qkode_file_t)) {
                echo "<option value='{$kf['desc']}'>{$kf['desc']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>File Lampiran</label>
            <input type="file" class="form-control" name="file" accept=".pdf,.jpg,.jpeg,.png">
          </div>
          <div class="text-right mt-3">
            <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan Anggota</button>
          </div>
        </form>
      </div>
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