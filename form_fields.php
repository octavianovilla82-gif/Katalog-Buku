<?php
// Dipakai bersama oleh create.php dan edit.php. Variabel: $data, $error, $title
page_header(); ?>
<h3><?= e($title) ?></h3>
<?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label><?= e(t('book_title')) ?></label>
  <input type="text" name="judul" value="<?= e($data['judul']) ?>">
  <label><?= e(t('author')) ?></label>
  <input type="text" name="penulis" value="<?= e($data['penulis']) ?>">
  <label><?= e(t('publisher')) ?></label>
  <input type="text" name="penerbit" value="<?= e($data['penerbit']) ?>">
  <label><?= e(t('year')) ?></label>
  <input type="text" name="tahun" value="<?= e($data['tahun']) ?>">
  <label><?= e(t('category')) ?></label>
  <select name="kategori">
    <option value=""><?= e(t('select_category')) ?></option>
    <?php foreach (['fiksi', 'nonfiksi', 'teknologi'] as $k): ?>
      <option value="<?= $k ?>" <?= $data['kategori'] === $k ? 'selected' : '' ?>><?= e(t($k)) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn" type="submit"><?= e(t('save')) ?></button>
  <a href="index.php"><?= e(t('cancel')) ?></a>
</form>
</body></html>
