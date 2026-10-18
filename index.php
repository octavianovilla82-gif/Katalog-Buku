<?php
require 'config.php';
require_login();
$result = mysqli_query($db, 'SELECT * FROM books ORDER BY id DESC');
page_header(); ?>
<?php if (($_GET['msg'] ?? '') === 'saved'): ?><p class="ok"><?= e(t('saved')) ?></p><?php endif; ?>
<?php if (($_GET['msg'] ?? '') === 'deleted'): ?><p class="ok"><?= e(t('deleted')) ?></p><?php endif; ?>
<p><a class="btn" href="create.php">+ <?= e(t('add')) ?></a></p>
<table>
  <tr>
    <th><?= e(t('no')) ?></th><th><?= e(t('book_title')) ?></th><th><?= e(t('author')) ?></th>
    <th><?= e(t('publisher')) ?></th><th><?= e(t('year')) ?></th><th><?= e(t('category')) ?></th>
    <th><?= e(t('action')) ?></th>
  </tr>
  <?php $no = 1; while ($b = mysqli_fetch_assoc($result)): ?>
  <tr>
    <td><?= $no++ ?></td>
    <td><?= e($b['judul']) ?></td>
    <td><?= e($b['penulis']) ?></td>
    <td><?= e($b['penerbit']) ?></td>
    <td><?= (int)$b['tahun'] ?></td>
    <td><?= e(t($b['kategori'])) ?></td>
    <td>
      <a class="btn" href="edit.php?id=<?= (int)$b['id'] ?>"><?= e(t('edit')) ?></a>
      <form class="inline" method="post" action="delete.php" onsubmit="return confirm('<?= e(t('confirm_delete')) ?>')">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
        <button class="btn red" type="submit"><?= e(t('delete')) ?></button>
      </form>
    </td>
  </tr>
  <?php endwhile; if ($no === 1): ?>
  <tr><td colspan="7"><?= e(t('empty')) ?></td></tr>
  <?php endif; ?>
</table>
</body></html>
