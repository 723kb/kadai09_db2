<?php
require_once('funcs.php');

// DB接続
$pdo = db_conn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // POSTデータを取得
  $id = $_POST['id'];
  $name = $_POST['name'];
  $message = $_POST['message'];
  $picture = null;

  // ファイルアップロード処理
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $picture = file_get_contents($_FILES['picture']['tmp_name']);
  } elseif ($_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
    exit('写真のアップロードに失敗しました');
  }

  // データベースを更新
  if ($picture !== null) {
    $stmt = $pdo->prepare('UPDATE kadai09_msg_table SET name = :name, message = :message, picture = :picture WHERE id = :id');
    $stmt->bindValue(':picture', $picture, PDO::PARAM_LOB);
  } else {
    $stmt = $pdo->prepare('UPDATE kadai09_msg_table SET name = :name, message = :message WHERE id = :id');
  }
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':message', $message, PDO::PARAM_STR);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  // リダイレクト
  header('Location: index.php');
  exit();
}

// GETリクエスト処理
$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM kadai09_msg_table WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<?php include 'head.php'; ?>

  <div class="min-h-screen w-5/6 flex flex-col items-center bg-[#F1F6F5] rounded-lg">
    <form method="POST" action="edit.php" enctype="multipart/form-data" class="w-full flex flex-col justify-center items-center m-2">
      <input type="hidden" name="id" value="<?= h($row['id']) ?>">
      <div class="w-full flex flex-col justify-center m-2">
        <div class="p-4">
          <label for="name" class="text-sm sm:text-base md:text-lg lg:text-xl">名前：</label>
          <input type="text" name="name" id="name" value="<?= h($row['name']) ?>" class="w-full h-11 p-2 border rounded-md">
        </div>
        <div class="p-4">
          <label for="message" class="text-sm sm:text-base md:text-lg lg:text-xl">内容：</label>
          <textArea name="message" id="message" rows="4" cols="40" class="w-full p-2 border rounded-md"><?= h($row['message']) ?></textArea>
          <div id="messageError" class="text-red-500 text-lg mt-1 hidden">内容は140文字以内で入力してください</div>
        </div>
        <div class="pb-4 px-4">
          <label for="picture" class="text-sm sm:text-base md:text-lg lg:text-xl">写真：</label>
          <div class="flex flex-col sm:flex-row justify-center items-center">
            <input type="file" name="picture" id="picture" accept="image/*" class="w-full h-11 py-2 my-2">
          </div>
        </div>
        <div class="flex justify-center">
          <?php if (!empty($row['picture'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($row['picture']) ?>" alt="写真"  id="preview" class="max-w-100% max-h-[300px]">
          <?php endif; ?>
        </div>
        <div class="flex justify-center">
          <button type="submit" class="w-1/6 border border-slate-200 hover:bg-[#93CCCA] rounded-md p-2 my-2"><i class="fas fa-paper-plane"></i></button>
        </div>
      </div>
    </form>
  </div>
</body>

<?php include 'foot.php'; ?>
