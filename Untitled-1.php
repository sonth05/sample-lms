<a href="admin.php?mo=book&act=addadd" class="btn btn-success float-end">Thêm sách mới</a>
          <h2 class="my-3">Sách</h2>
          
          <?php if (isset ($_SESSION['alert'])): ?>
            <div class="alert alert-success mt-3">
              <?= $_SESSION['alert'] ?>
            </div>
          <?php endif;
          unset($_SESSION['alert']) ?>  

          <table class="table text-center align-middle">
            <thead>
              <tr>
                <th>STT</th>
                <th>Hình ảnh</th>
                <th class="text-start">Tựa sách</th>
                <th>Tác giả</th>
                <th class="text-end">Giá trị</th>
                <th>Số lượng</th>
                <th>Chủ đề</th>
                <th>Số cảm nghĩ</th>
                <th>Lượt đọc</th>
                <th>Lượt xem</th>
                <th class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; 
              foreach ($bookList as $book):?>
                <tr>
                  <td><?= $i ?></td>
                  <td><img src="<?$baseUrl?>upload/product/<?= $book['HinhAnh'] ?>" class="rounded-3" style="width:64px"></td>
                  <td class="text-start"><?= $book['TuaSach'] ?></td>
                  <td><?= $book['TacGia'] ?></td>
                  <td class="text-end"><?= number_format(book['GiaTri']) ?></td>
                  <td><?= $book['SoLuong'] ?></td>
                  <td><?= $book['TenChuDe'] ?></td>
                  <td><?= $book['SoCamNghi'] ?></td>
                  <td><?= $book['LuotDoc'] ?></td>
                  <td><?= $book['LuotXem'] ?></td>
                  <td class="text-end">
                    <a class="btn btn-warning" href="?mod=book&act=update&id=<?=$book['MaSach']?>"><i class="fa-solid fa-gear"></i></a>
                    <button class="btn btn-danger btn-delete" data-id="<?= $book['MaSach'] ?>"data-name="<?=$book['TuaSach']?>"><i class="fa-solid fa-trash-can"></i></button>
                  </td>
                </tr>
              <?php $i++;
              endforeach; ?>
            </tbody>
          </table>
<script>
  document.querySelectorAll('.btn-delete').forEach((btn)=>{
    btn.addEventListener('click', (event) => {
      let it = btn.getAttribute('data-id');
      let name = btn.getAttribute('data-name');
      let ok = confirm('Bạn đang thực hiện việc xóa sách ${name}. Bấm OK để xóa!');
      if(ok){
        location.search = "?mod=book&act=delete&id=${id}";
      }
    })
  })
</script>