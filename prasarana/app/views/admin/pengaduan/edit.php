<?php

$pageTitle = 'Edit Pengaduan - Admin';

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

    <div class="page-header">

        <h1>
            Update Pengaduan
        </h1>

        <a
            href="<?= url('admin/pengaduan/detail/' . $pengaduan['id']) ?>"
            class="btn"
        >
            Kembali
        </a>

    </div>


    <section class="content-card">

        <form
            action="<?= url('admin/pengaduan/update/' . $pengaduan['id']) ?>"
            method="POST"
        >

            <div class="form-group">

                <label>
                    Status
                </label>

                <select name="status">

                    <?php

                    $statuses = [
                        'Diajukan',
                        'Diproses',
                                                'Selesai',
                        'Ditolak'
                    ];

                    ?>

                    <?php foreach ($statuses as $status): ?>

                        <option
                            value="<?= $status ?>"
                            <?= $pengaduan['status'] === $status ? 'selected' : '' ?>
                        >
                            <?= $status ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Progress (%)
                </label>

                <input
                    type="number"
                    name="progress"
                    min="0"
                    max="100"
                    value="<?= (int)$pengaduan['progress'] ?>"
                >

            </div>


            <div class="form-group">

                <label>
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    rows="5"
                ></textarea>

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan
            </button>

        </form>

    </section>

<?php
require_once __DIR__ . '/../../layouts/footer.php';
?>