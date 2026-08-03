<?php
$id_porto = (int) @$folder[4];

// Mengambil informasi jurnal
$q_porto = mysqli_query($con, "
    SELECT p.*, d.name_glr, 
           kj.desc as nama_jenis,
           lit.judul_kegiatan as judul_lit,
           abmas.judul_kegiatan as judul_abmas
    FROM penelitian_jurnal p 
    LEFT JOIN dosen d ON p.nip_dosen = d.nik 
    LEFT JOIN kode kj ON p.id_jenis = kj.value AND kj.kelompok = 'jurnal' 
    LEFT JOIN penelitian_penelitian lit ON p.link_lit = lit.id
    LEFT JOIN pengabdian_pengabdian abmas ON p.link_abmas = abmas.id
    WHERE p.id = '$id_porto'
");
$porto = mysqli_fetch_array($q_porto);

if (!$porto) {
  echo "<div class='alert alert-danger'>Data Artikel Jurnal tidak ditemukan.</div>";
  return;
}

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
          <a href="/delete/penelitian/jurnal/<?= $porto['id'] ?>" class="btn btn-sm btn-danger shadow-sm ml-1"
            onclick="return confirm('Yakin ingin menghapus seluruh data Jurnal ini?');">
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
                      <i class="fas fa-info-circle text-primary mr-2"></i> <strong>Informasi Umum Jurnal</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 250px; font-weight: 600; color: #444;">Judul Artikel</td>
                    <td style="width: 10px; text-align: center;">:</td>
                    <td class="font-weight-bold text-dark">
                      <?= htmlspecialchars($porto['judul'] ?? '', ENT_QUOTES) ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Jenis Jurnal</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['nama_jenis'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Nama Jurnal</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['nama_jurnal'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">ISSN</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['issn'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">URL Jurnal</td>
                    <td style="text-align: center;">:</td>
                    <td>
                      <?php if (!empty($porto['url_jurnal'])): ?>
                        <a href="<?= htmlspecialchars($porto['url_jurnal']) ?>" target="_blank"
                          rel="noopener noreferrer"><?= htmlspecialchars($porto['url_jurnal']) ?></a>
                      <?php else: ?>
                        -
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Tanggal Terbit</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['tgl_terbit'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Volume</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['vol'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Nomor</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['no'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Halaman</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['halaman'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Penerbit</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['penerbit'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">DOI</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['doi'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Jumlah Sitasi</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['sitasi'] ?? '') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">URL Artikel (Bukan URL Jurnal/Google)</td>
                    <td style="text-align: center;">:</td>
                    <td>
                      <a href="<?= htmlspecialchars($porto['url_artikel']) ?>" target="_blank"
                        rel="noopener noreferrer"><?= htmlspecialchars($porto['url_artikel']) ?></a>
                    </td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Penelitian yang Berkaitan Langsung</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['judul_lit'] ?? '-') ?></td>
                  </tr>
                  <tr>
                    <td style="font-weight: 600; color: #444;">Pengabdian yang Berkaitan Langsung</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($porto['judul_abmas'] ?? '-') ?></td>
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
                <th class="align-middle">File Lampiran</th>
                <th class="align-middle">Tanggal Regis</th>
                <th class="text-center align-middle" style="width: 80px;">Aksi</th>
              </tr>
            </thead>
            <tbody style="font-size: 0.9rem;">
              <?php
              $judul_porto_esc = mysqli_real_escape_string($con, $porto['judul']);
              $q_regis = mysqli_query($con, "
                  SELECT r.*, d.name_glr, d.name, k.`desc` as peran_desc, k2.`desc` as file_desc,
                         (SELECT url FROM file_dosen 
                          WHERE nip_dosen = r.nik AND kelompok = 'file_jurnal' 
                          AND (id_cat = r.id_porto OR id_cat IN (SELECT id FROM penelitian_jurnal WHERE nip_dosen = r.nik AND (judul = '$judul_porto_esc' OR id = r.id_porto)))
                          ORDER BY id DESC LIMIT 1) as url_file,
                         (SELECT value_kode FROM file_dosen 
                          WHERE nip_dosen = r.nik AND kelompok = 'file_jurnal' 
                          AND (id_cat = r.id_porto OR id_cat IN (SELECT id FROM penelitian_jurnal WHERE nip_dosen = r.nik AND (judul = '$judul_porto_esc' OR id = r.id_porto)))
                          ORDER BY id DESC LIMIT 1) as value_kode
                  FROM regis_dosen_penelitian r 
                  LEFT JOIN dosen d ON r.nik = d.nik 
                  LEFT JOIN kode k ON r.role = k.value AND k.kelompok = 'peran_penelitian' 
                  LEFT JOIN kode k2 ON (
                      SELECT value_kode FROM file_dosen 
                      WHERE nip_dosen = r.nik AND kelompok = 'file_jurnal' 
                      AND (id_cat = r.id_porto OR id_cat IN (SELECT id FROM penelitian_jurnal WHERE nip_dosen = r.nik AND (judul = '$judul_porto_esc' OR id = r.id_porto)))
                      ORDER BY id DESC LIMIT 1
                  ) = k2.value AND k2.kelompok = 'file_jurnal'
                  WHERE r.id_porto = '$id_porto' AND r.jenis_porto = 'jurnal' 
                  ORDER BY r.id ASC
              ");

              if (mysqli_num_rows($q_regis) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_array($q_regis)) {
                  $type_text = !empty($row['file_desc']) ? $row['file_desc'] : (!empty($row['value_kode']) ? $row['value_kode'] : 'Berkas');
                  $prodi_str = "";
                  ?>
                  <tr>
                    <td class="text-center align-middle"><?= $no ?></td>
                    <td class="align-middle"><?= $row['nik'] ?></td>
                    <td class="align-middle"><span class="text-dark"><?= $row['name_glr'] ?></span><?= $prodi_str ?></td>
                    <td class="align-middle text-center">
                      <?php if (!empty($row['url_file'])): ?>
                        <a href="<?= @$storage_url . $row['url_file'] ?>" target="_blank" class="btn btn-xs btn-primary">
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
                          <form action="/update/penelitian/anggota_jurnal" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_regis" value="<?= $row['id'] ?>">
                            <input type="hidden" name="id_porto" value="<?= $id_porto ?>">
                            <div class="form-group">
                              <label>Dosen</label>
                              <input type="text" class="form-control"
                                value="<?= $row['nik'] ?> - <?= htmlspecialchars($row['name_glr'], ENT_QUOTES) ?>" readonly>
                            </div>
                            <input type="hidden" name="role" value="2">
                            <div class="form-group">
                              <label>Jenis File <span class="text-danger">*</span></label>
                              <select class="form-control" name="type_file" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $qkode_file = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_jurnal' ORDER BY id");
                                while ($kf = mysqli_fetch_array($qkode_file)) {
                                  $selected = ($row['value_kode'] == $kf['desc']) ? 'selected' : '';
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
                            dari jurnal ini?</p>
                          <form action="/delete/penelitian/anggota_jurnal/<?= $row['id'] ?>?t=jurnal" method="POST">
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
                $q_orig = mysqli_query($con, "SELECT p.*, d.name_glr, d.nik FROM penelitian_jurnal p LEFT JOIN dosen d ON p.nip_dosen = d.nik WHERE p.id = '$id_porto'");
                if ($orig = mysqli_fetch_array($q_orig)) {
                  $peran_desc = 'Ketua';
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
                      Belum ada data anggota dosen untuk jurnal ini.
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
        <form action="/insert/penelitian/anggota_jurnal" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_porto" value="<?= $id_porto ?>">
          <div class="form-group">
            <label>Pilih Dosen <span class="text-danger">*</span></label>
            <select class="form-control select2bs4" name="nik" required style="width: 100%;">
              <option value="">-- Cari Nama/NIK Dosen --</option>
              <?php
              $qdosen = mysqli_query($con, "SELECT nik, name_glr FROM dosen ORDER BY name");
              while ($d = mysqli_fetch_array($qdosen)) {
                echo "<option value='{$d['nik']}'>{$d['name_glr']} ({$d['nik']})</option>";
              }
              ?>
            </select>
          </div>
          <input type="hidden" name="role" value="2">
          <div class="form-group">
            <label>Jenis File <span class="text-danger">*</span></label>
            <select class="form-control" name="type_file" required>
              <option value="">-- Pilih --</option>
              <?php
              $qkode_file = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_jurnal' ORDER BY id");
              while ($kf = mysqli_fetch_array($qkode_file)) {
                echo "<option value='{$kf['desc']}'>{$kf['desc']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>File Lampiran <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
            <small class="text-muted">Format: PDF, JPG, PNG.</small>
          </div>
          <div class="text-right mt-4">
            <button type="submit" class="btn btn-success"><i class="fas fa-plus mr-1"></i> Tambah Anggota</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  });
</script>