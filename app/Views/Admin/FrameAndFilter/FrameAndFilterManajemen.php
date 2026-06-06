<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="mt-0 p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li><a class="hover:text-blue-600" href="<?= base_url('admin/dashboard') ?>">Home</a></li>
                <li><span class="material-symbols-outlined text-[14px]">chevron_right</span></li>
                <li class="text-blue-700">Manajemen Aset Desain</li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-12 gap-6">

            <!-- ============================================================
                 KIRI: Tabel Frame (7 kolom)
            ============================================================ -->
            <section class="col-span-12 xl:col-span-7">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h2 class="font-h2 text-h2 text-on-surface">Koleksi Frame</h2>
                            <p class="text-body-sm text-slate-500">Kelola bingkai foto untuk sesi pemotretan.</p>
                        </div>
                        <a href="<?= base_url('admin/frames/create') ?>"
                           class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                            Unggah Frame
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600">ID Frame</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600">Nama Frame</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600">Preview</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600">Waktu Unggah</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($frames)): ?>
                                    <?php foreach ($frames as $frame): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-4 font-data-tabular text-data-tabular"><?= esc($frame['id']) ?></td>
                                            <td class="px-6 py-4 font-body-base text-body-base text-on-surface"><?= esc($frame['name']) ?></td>
                                            <td class="px-6 py-4">
                                                <div class="w-16 h-10 rounded border border-slate-200 bg-slate-100 overflow-hidden">
                                                    <?php if (!empty($frame['file_path'])): ?>
                                                        <img alt="<?= esc($frame['name']) ?> preview"
                                                             class="w-full h-full object-cover"
                                                             src="<?= base_url($frame['file_path']) ?>"/>
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                            <span class="material-symbols-outlined text-sm">image</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-body-sm text-body-sm text-slate-500">
                                                <?= date('d M Y, H:i', strtotime($frame['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 text-right space-x-2">
                                                <a href="<?= base_url('admin/frames/' . $frame['id'] . '/edit') ?>"
                                                   class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </a>
                                                <form action="<?= base_url('admin/frames/' . $frame['id'] . '/delete') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus"
                                                            onclick="return confirm('Hapus frame ini?')">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">
                                            Belum ada frame. <a href="<?= base_url('admin/frames/create') ?>" class="text-primary font-semibold">Unggah sekarang</a>.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Frame -->
                    <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Menampilkan <?= count($frames ?? []) ?> dari <?= $totalFrames ?? 0 ?> frame
                        </span>
                        <div class="flex gap-1">
                            <a href="<?= base_url('admin/frames?page=' . max(1, ($currentFramePage ?? 1) - 1)) ?>"
                               class="p-1 border border-slate-300 rounded hover:bg-white transition-colors <?= ($currentFramePage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <a href="<?= base_url('admin/frames?page=' . min(($totalFramePages ?? 1), ($currentFramePage ?? 1) + 1)) ?>"
                               class="p-1 border border-slate-300 rounded hover:bg-white transition-colors <?= ($currentFramePage ?? 1) >= ($totalFramePages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============================================================
                 KANAN: Tabel Filter + Stats (5 kolom)
            ============================================================ -->
            <section class="col-span-12 xl:col-span-5 flex flex-col gap-6">

                <!-- Tabel Filter Warna -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h2 class="font-h2 text-h2 text-on-surface">Filter Warna</h2>
                            <p class="text-body-sm text-slate-500">Preset CSS untuk efek visual.</p>
                        </div>
                        <a href="<?= base_url('admin/filters/create') ?>"
                           class="border border-primary text-primary px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-50 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">tune</span>
                            Tambah Filter
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">ID</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">Nama</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">CSS Value</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">Status</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($filters)): ?>
                                    <?php foreach ($filters as $filter): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-4 py-4 font-data-tabular text-data-tabular"><?= esc($filter['id']) ?></td>
                                            <td class="px-4 py-4 font-body-base text-body-base"><?= esc($filter['name']) ?></td>
                                            <td class="px-4 py-4">
                                                <code class="bg-slate-100 px-2 py-1 rounded text-[11px] text-slate-600">
                                                    <?= esc($filter['css_value']) ?>
                                                </code>
                                            </td>
                                            <td class="px-4 py-4">
                                                <?php if ($filter['is_active']): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Aktif</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-4 text-right flex justify-end gap-1">
                                                <a href="<?= base_url('admin/filters/' . $filter['id'] . '/edit') ?>"
                                                   class="p-1 text-slate-400 hover:text-blue-600" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">settings</span>
                                                </a>
                                                <form action="<?= base_url('admin/filters/' . $filter['id'] . '/toggle') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="p-1 text-slate-400 hover:text-green-600" title="Toggle Aktif">
                                                        <span class="material-symbols-outlined text-[20px]">
                                                            <?= $filter['is_active'] ? 'toggle_on' : 'toggle_off' ?>
                                                        </span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">
                                            Belum ada filter.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Stats Bento -->
                <div class="p-6 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-lg overflow-hidden relative group">
                    <div class="relative z-10">
                        <h3 class="text-h2 font-h2 mb-2">Statistik Aset</h3>
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                                <p class="text-xs text-blue-100">Total Frame</p>
                                <p class="text-2xl font-bold"><?= number_format($statTotalFrames ?? 0) ?></p>
                            </div>
                            <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                                <p class="text-xs text-blue-100">Filter Aktif</p>
                                <p class="text-2xl font-bold"><?= number_format($statActiveFilters ?? 0) ?></p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-xs text-blue-100">
                            <span class="material-symbols-outlined text-sm">info</span>
                            <span>Total Filter: <?= $statTotalFilters ?? 0 ?> | Frame bulan ini: <?= $statFramesThisMonth ?? 0 ?></span>
                        </div>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                </div>
            </section>

            <!-- ============================================================
                 BAWAH: Upload Zone + Panduan
            ============================================================ -->
            <div class="col-span-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Upload Zone -->
                <form action="<?= base_url('admin/frames') ?>" method="post" enctype="multipart/form-data"
                      class="md:col-span-2 bg-white border border-dashed border-slate-300 rounded-xl p-10 flex flex-col items-center justify-center text-center group hover:border-blue-400 transition-colors">
                    <?= csrf_field() ?>
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                    </div>
                    <h4 class="font-bold text-on-surface mb-1">Seret &amp; Letakkan Frame Baru</h4>
                    <p class="text-body-sm text-slate-500 mb-4">Mendukung format PNG transparan, resolusi min. 1080px.</p>
                    <input type="text" name="name" placeholder="Nama Frame" required
                           class="mb-3 w-full max-w-xs px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"/>
                    <label class="cursor-pointer bg-slate-100 text-slate-700 px-6 py-2 rounded-full text-xs font-bold hover:bg-slate-200 transition-colors">
                        Pilih File
                        <input type="file" name="frame_file" accept="image/png,image/jpeg" class="hidden"/>
                    </label>
                    <button type="submit" class="mt-4 bg-primary text-white px-6 py-2 rounded-full text-xs font-bold hover:opacity-90 transition-opacity">
                        Upload Frame
                    </button>
                </form>

                <!-- Panduan Aset -->
                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h4 class="font-h2 text-body-base font-bold mb-4">Panduan Aset</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">check_circle</span>
                            <div>
                                <p class="text-xs font-bold text-on-surface">Aspek Rasio</p>
                                <p class="text-[11px] text-slate-500">Pastikan frame memiliki rasio 4:6 atau 1:1.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">check_circle</span>
                            <div>
                                <p class="text-xs font-bold text-on-surface">Transparansi</p>
                                <p class="text-[11px] text-slate-500">Area tengah harus transparan (Alpha Channel).</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">check_circle</span>
                            <div>
                                <p class="text-xs font-bold text-on-surface">Optimasi</p>
                                <p class="text-[11px] text-slate-500">Ukuran file maksimal 2MB per frame.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
