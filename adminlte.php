<?php
require_once __DIR__ . '/koneksi.php';

// Ambil semua data nilai untuk ditampilkan.
$result = mysqli_query($conn, 'SELECT nim, nama_mhs, nilai_akhir, nilai_huruf, predikat, gambar, reg_date FROM nilaimhs ORDER BY nim');
if (!$result) {
  http_response_code(500);
  exit('Gagal mengambil data nilai mahasiswa.');
}

// Amankan teks sebelum dimasukkan ke HTML.
$escape = static function ($value) {
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Nilai Mahasiswa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="./css/adminlte.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h1 class="mb-0 fs-3">Data Nilai Mahasiswa</h1>
            </div>
            <div class="col-sm-6">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="adminlte.php">Beranda</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Nilai Mahasiswa</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header">
                  <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                      <h3 class="card-title">Daftar Nilai Mahasiswa</h3>
                    </div>
                    <div class="col-12 col-md-8">
                      <div class="d-flex flex-wrap justify-content-md-end gap-2">
                        <div class="input-group input-group-sm w-auto">
                          <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                          <input type="search" id="student-search" class="form-control" placeholder="Cari mahasiswa" aria-label="Cari mahasiswa" style="width: 180px">
                        </div>
                        <select id="student-sort" class="form-select form-select-sm w-auto" aria-label="Urutkan mahasiswa">
                          <option value="asc" selected>Asc Nilai</option>
                          <option value="desc">Desc Nilai</option>
                        </select>
                        <select id="grade-filter" class="form-select form-select-sm w-auto" aria-label="Filter berdasarkan nilai huruf">
                          <option value="all" selected>Semua Predikat</option>
                          <option value="sangatbaik">Sangat Baik</option>
                          <option value="baik">Baik</option>
                          <option value="cukup">Cukup</option>
                          <option value="buruk">Buruk</option>
                          <option value="sangatburuk">Sangat Buruk</option>
                        </select>
                        <button
                          type="button"
                          id="add-score-button"
                          class="btn btn-sm btn-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#modal-add-user"
                        >
                          <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
                          Tambah data nilai
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tabel nilai mahasiswa beserta tombol aksi. -->
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                      <thead>
                        <tr>
                          <th>NIM</th>
                          <th>Foto Mahasiswa</th>
                          <th>Nama Mahasiswa</th>
                          <th>Nilai Akhir</th>
                          <th>Nilai Huruf</th>
                          <th>Predikat</th>
                          <th>Tanggal Entry</th>
                          <th class="text-end">Aksi</th>
                        </tr>
                      </thead>

                      <tbody id="student-rows">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                          <?php $nim = (string) $row['nim']; ?>
                          <?php
                            $grade = strtoupper((string) $row['nilai_huruf']);
                            $predicateFilter = strtolower(str_replace(' ', '', (string) $row['predikat']));
                            $gradeBadgeClass = in_array($grade, ['A', 'B'], true)
                              ? 'text-bg-success'
                              : ($grade === 'C' ? 'text-bg-warning' : 'text-bg-danger');
                          ?>
                          <tr data-grade="<?php echo $escape($grade); ?>" data-predikat="<?php echo $escape($predicateFilter); ?>">
                            <td><?php echo $escape($nim); ?></td>
                            <td>
                              <?php if (!empty($row['gambar'])): ?>
                                <img
                                  src="<?php echo $escape($row['gambar']); ?>"
                                  alt="Foto <?php echo $escape($row['nama_mhs']); ?>"
                                  class="img-size-32 rounded-circle"
                                />
                              <?php endif; ?>
                            </td>
                            <td>
                              <span class="fw-medium"><?php echo $escape($row['nama_mhs']); ?></span>
                            </td>
                            <td><?php echo $escape($row['nilai_akhir']); ?></td>
                            <td><span class="badge text-bg-primary"><?php echo $escape($grade); ?></span></td>
                            <td><span class="badge <?php echo $gradeBadgeClass; ?>"><?php echo $escape($row['predikat']); ?></span></td>
                            <td><?php echo $escape($row['reg_date']); ?></td>
                            <td class="text-end">
                              <div class="btn-group btn-group-sm">
                                <button
                                  type="button"
                                  class="btn btn-outline-secondary"
                                  data-action="edit"
                                  data-nim="<?php echo $escape($nim); ?>"
                                  data-nama="<?php echo $escape($row['nama_mhs']); ?>"
                                  data-nilai="<?php echo $escape($row['nilai_akhir']); ?>"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modal-edit-user"
                                  aria-label="Edit <?php echo $escape($row['nama_mhs']); ?>"
                                  title="Edit"
                                >
                                  <i class="bi bi-pencil" aria-hidden="true"> </i>
                                </button>
                                <button
                                  type="button"
                                  class="btn btn-outline-danger"
                                  data-action="delete"
                                  data-nim="<?php echo $escape($nim); ?>"
                                  data-nama="<?php echo $escape($row['nama_mhs']); ?>"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modal-delete-user"
                                  aria-label="Hapus <?php echo $escape($row['nama_mhs']); ?>"
                                  title="Hapus"
                                >
                                  <i class="bi bi-trash" aria-hidden="true"> </i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="card-footer clearfix">
                  <div id="student-summary" class="float-start pt-1 fs-7 text-body-secondary" aria-live="polite"></div>
                  <div class="float-end d-flex flex-wrap align-items-center gap-2">
                    <label for="student-page-size" class="fs-7 text-body-secondary">Tampilkan:</label>
                    <select id="student-page-size" class="form-select form-select-sm w-auto" aria-label="Jumlah mahasiswa per halaman">
                      <option value="5" selected>5</option>
                      <option value="10">10</option>
                      <option value="15">15</option>
                    </select>
                    <ul id="student-pagination" class="pagination pagination-sm m-0"></ul>
                  </div>
                </div>
                </div>
                <footer class="app-footer">
                    <!--begin::To the end-->
                    <div class="float-end d-none d-sm-inline">Pemrograman Web - Semester 5 - design menggunakan <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>. </div>
                    <!--end::To the end-->
                    <!-- begin::Copyright -->
                    <strong>
                    Hakcipta &copy; pada prodi TI &nbsp;
                    <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
                    </strong>
                    Seluruh hakcipta dilindungi.
                    <!--end::Copyright-->
                </footer>

            </div>
          </div>

          <!-- Form modal untuk menambah nilai mahasiswa. -->
          <!--begin::Add User Modal-->

          <div
            class="modal fade"
            id="modal-add-user"
            tabindex="-1"
            aria-labelledby="modal-add-user-label"
            aria-hidden="true"
          >
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="olah_nilai.php" method="POST" enctype="multipart/form-data">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-user-label">Tambah data nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="new-score-nim" class="form-label">NIM</label>
                      <input type="text" class="form-control" id="new-score-nim" name="nim" maxlength="20" required>
                    </div>
                    <div class="mb-3">
                      <label for="new-score-name" class="form-label">Nama lengkap</label>
                      <input type="text" class="form-control" id="new-score-name" name="nama_lngkp_mhs" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                      <label for="new-score-value" class="form-label">Nilai akhir</label>
                      <input type="number" class="form-control" id="new-score-value" name="nilai_akhir" min="0" max="100" step="0.01" required>
                      <div class="form-text">Nilai huruf dan predikat dihitung otomatis.</div>
                    </div>
                    <div class="mb-3">
                      <label for="new-score-image" class="form-label">Foto mahasiswa</label>
                      <input type="file" class="form-control" id="new-score-image" name="gambar" accept="image/jpeg,image/png,image/gif" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan data</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!--end::Add User Modal-->

          <!-- Form modal untuk mengubah data nilai. -->
          <!--begin::Edit User Modal-->

          <div
            class="modal fade"
            id="modal-edit-user"
            tabindex="-1"
            aria-labelledby="modal-edit-user-label"
            aria-hidden="true"
          >
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="update_nilai.php" method="POST" enctype="multipart/form-data">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-user-label">Edit data nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="edit-score-nim" class="form-label">NIM</label>
                      <input type="text" class="form-control" id="edit-score-nim" name="nim" readonly required>
                    </div>
                    <div class="mb-3">
                      <label for="edit-score-name" class="form-label">Nama lengkap</label>
                      <input type="text" class="form-control" id="edit-score-name" name="nama_mhs" maxlength="100" required>
                    </div>
                    <div class="mb-3">
                      <label for="edit-score-value" class="form-label">Nilai akhir</label>
                      <input type="number" class="form-control" id="edit-score-value" name="nilai_akhir" min="0" max="100" step="0.01" required>
                      <div class="form-text">Nilai huruf dan predikat dihitung otomatis.</div>
                    </div>
                    <div class="mb-3">
                      <label for="edit-score-image" class="form-label">Ganti foto mahasiswa (opsional)</label>
                      <input type="file" class="form-control" id="edit-score-image" name="gambar" accept="image/jpeg,image/png,image/gif">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!--end::Edit User Modal-->

          <!-- Konfirmasi sebelum data nilai dihapus. -->
          <!--begin::Delete User Modal-->

          <div
            class="modal fade"
            id="modal-delete-user"
            tabindex="-1"
            aria-labelledby="modal-delete-user-label"
            aria-hidden="true"
          >
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="hapus_nilai.php" method="GET">
                  <input type="hidden" id="delete-score-nim" name="nim">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modal-delete-user-label">Hapus data nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    <p class="mb-0">Yakin ingin menghapus data nilai mahasiswa <strong id="delete-score-name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus data</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
           <!--end::Delete User Modal-->

          <div class="modal fade" id="modal-image-error" tabindex="-1" aria-labelledby="modal-image-error-title" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modal-image-error-title">Warning!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">Ukuran foto maksimal 1MB.</div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./js/adminlte.js"></script>
  <script>
    // Mengatur pencarian, pengurutan, pemfilteran, dan paginasi data mahasiswa.
    document.addEventListener('DOMContentLoaded', () => {
      const rows = [...document.querySelectorAll('#student-rows tr')];
      const searchInput = document.getElementById('student-search');
      const sortSelect = document.getElementById('student-sort');
      const gradeFilter = document.getElementById('grade-filter');
      const pageSizeSelect = document.getElementById('student-page-size');
      const summary = document.getElementById('student-summary');
      const pagination = document.getElementById('student-pagination');
      const savedPageSize = localStorage.getItem('student-page-size');
      if ([...pageSizeSelect.options].some((option) => option.value === savedPageSize)) {
        pageSizeSelect.value = savedPageSize;
      }
      let pageSize = Number(pageSizeSelect.value);
      let currentPage = 1;

      // mencari, mengurutkan, memfilter, dan menampilkan halaman data.
      const render = () => {
        const query = searchInput.value.trim().toLowerCase();
        const grade = gradeFilter.value;

        const sortDirection = sortSelect.value;
        rows.sort((first, second) => {
          const firstValue = Number(first.cells[3].textContent.trim());
          const secondValue = Number(second.cells[3].textContent.trim());
          const comparison = firstValue - secondValue;
          return sortDirection === 'desc' ? -comparison : comparison;
        });
        rows.forEach((row) => document.getElementById('student-rows').append(row));
        const filtered = rows.filter((row) => {
          const matchText = row.textContent.toLowerCase().includes(query);
          const matchGrade = grade === 'all' || row.dataset.predikat === grade;
          return matchText && matchGrade;
        });

        const pageCount = Math.ceil(filtered.length / pageSize);
        currentPage = Math.min(currentPage, pageCount || 1);
        const start = (currentPage - 1) * pageSize;
        const visibleRows = new Set(filtered.slice(start, start + pageSize));

        rows.forEach((row) => {
          row.hidden = !visibleRows.has(row);
        });

        summary.textContent = filtered.length
          ? `Menampilkan ${start + 1} sampai ${Math.min(start + pageSize, filtered.length)} dari ${filtered.length} mahasiswa`
          : 'Tidak ada data mahasiswa';

        pagination.replaceChildren();
        const addPage = (page, label, { disabled = false, active = false, ariaLabel } = {}) => {
          const item = document.createElement('li');
          item.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
          const link = document.createElement('a');
          link.className = 'page-link';
          link.href = '#';
          link.textContent = label;
          if (ariaLabel) {
            link.setAttribute('aria-label', ariaLabel);
          }
          if (active) {
            link.setAttribute('aria-current', 'page');
          }
          if (disabled) {
            link.setAttribute('aria-disabled', 'true');
          } else {
            link.dataset.page = page;
          }
          item.append(link);
          pagination.append(item);
        };

        if (pageCount > 0) {
          addPage(currentPage - 1, '«', { disabled: currentPage === 1, ariaLabel: 'Sebelumnya' });
          for (let page = 1; page <= pageCount; page += 1) {
            addPage(page, String(page), { active: page === currentPage });
          }
          addPage(currentPage + 1, '»', { disabled: currentPage === pageCount, ariaLabel: 'Berikutnya' });
        }
      };

      // memperbarui tabel saat pilihan pencarian atau tampilan berubah.
      searchInput.addEventListener('input', () => {
        currentPage = 1;
        render();
      });
      sortSelect.addEventListener('change', () => {
        currentPage = 1;
        render();
      });
      gradeFilter.addEventListener('change', () => {
        currentPage = 1;
        render();
      });
      pageSizeSelect.addEventListener('change', () => {
        localStorage.setItem('student-page-size', pageSizeSelect.value);
        pageSize = Number(pageSizeSelect.value);
        currentPage = 1;
        render();
      });
      pagination.addEventListener('click', (event) => {
        const link = event.target.closest('a[data-page]');
        if (!link) {
          return;
        }
        event.preventDefault();
        currentPage = Number(link.dataset.page);
        render();
      });

      // Isi form modal dari data pada tombol yang dipilih.
      document.getElementById('modal-edit-user').addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        document.getElementById('edit-score-nim').value = button.dataset.nim;
        document.getElementById('edit-score-name').value = button.dataset.nama;
        document.getElementById('edit-score-value').value = button.dataset.nilai;
      });

      document.getElementById('modal-delete-user').addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        document.getElementById('delete-score-nim').value = button.dataset.nim;
        document.getElementById('delete-score-name').textContent = button.dataset.nama;
      });

      // Tolak foto yang melebihi batas ukuran 1 MB.
      document.querySelectorAll('form[action="olah_nilai.php"], form[action="update_nilai.php"]').forEach((form) => {
        form.addEventListener('submit', (event) => {
          const gambar = form.querySelector('input[type="file"]').files[0];
          if (gambar && gambar.size > 1000000) {
            event.preventDefault();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-image-error')).show();
          }
        });
      });

      render();
    });
  </script>
</body>
</html>
<?php mysqli_close($conn); ?>