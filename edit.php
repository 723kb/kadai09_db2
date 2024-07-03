<?php
require_once('funcs.php');
require_once('db_conn.php');

// DB接続
$pdo = db_conn();

// ユーザーが編集ページにアクセスした時に実行
// GETでidを取得
$id = isset($_GET['id']) ? $_GET['id'] : null;

// idが指定されていない場合のエラーハンドリング
if ($id === null) {
  exit('編集対象のIDが指定されていません。');
}
// 編集したい内容をデータベースから取得
$stmt = $pdo->prepare('SELECT * FROM kadai09_msg_table WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

// 編集対象のデータが見つからない場合のエラーハンドリング
if (!$row) {
  exit('指定されたIDのデータが見つかりません。'); // または他の適切なエラーメッセージを表示
}

$error_message = ''; // エラーメッセージ初期化

// POSTリクエスト処理 ユーザーが編集フォームを送信した時に実行
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (
    !isset($_POST['name']) || $_POST['name'] === '' ||
    !isset($_POST['message']) || $_POST['message'] === ''
  ) {
    $error_message = '名前または内容が入力されていません';
  } elseif (mb_strlen($_POST['message']) > 140) {
    $error_message = '内容は140文字以内で入力してください';
  }

  // エラーがなければ更新処理などを実行する
  if (empty($error_message)) {
    // POSTデータを取得
    $id = $_POST['id'];
    $name = $_POST['name'];
    $message = $_POST['message'];
    $picture = null;  // デフォルト値 条件分岐で画像の更新があるか対応

    // ファイルアップロード処理
    $picture = handleFileUpload('picture');

    // 更新SQL作成
    if ($picture !== null) {  // 画像が新たにアップされた場合 名前、メッセージ、画像、更新日時を更新
      $stmt = $pdo->prepare('UPDATE kadai09_msg_table SET name = :name, message = :message, picture = :picture, updated_at = now() WHERE id = :id');
      $stmt->bindValue(':picture', $picture, PDO::PARAM_LOB);
    } else {  // 画像がアップされなかった場合 名前、メッセージ、更新日時を更新 既存の画像を保持
      $stmt = $pdo->prepare('UPDATE kadai09_msg_table SET name = :name, message = :message, updated_at = now() WHERE id = :id');
    }  // 共通の処理
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // リダイレクト
    redirect('index.php');
  }
}
?>

<!-- 以下HTMLの表示 -->

<!-- header -->
<?php include 'head.php'; ?>

<!-- Main[Start] -->
<div class="min-h-fit w-5/6 flex flex-col flex-1 items-center bg-[#F1F6F5] rounded-lg">
  <!-- Form[Start] -->
  <form method="POST" action="" enctype="multipart/form-data" class="w-full flex flex-col justify-center items-center m-2">
    <input type="hidden" name="id" value="<?= h($row['id']) ?>">
    <div class="w-full flex flex-col justify-center m-2">
      <div class="p-4">
        <label for="name" class="text-sm sm:text-base md:text-lg lg:text-xl">名前：</label>
        <input type="text" name="name" id="name" value="<?= h($row['name']) ?>" class="w-full h-11 p-2 border rounded-md">
      </div>
      <div class="p-4">
        <label for="message" class="text-sm sm:text-base md:text-lg lg:text-xl">内容：</label>
        <textArea name="message" id="message" rows="4" cols="40" class="w-full p-2 border rounded-md"><?= h($row['message']) ?></textArea>
        <div id="messageError" class="text-red-500 mt-1 hidden">内容は140文字以内で入力してください</div>
        <!-- エラーメッセージ表示 -->
        <?php if (isset($error_message)) : ?>
          <div class="error-message mt-auto text-red-500">
            <?= h($error_message) ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="pb-4 px-4">
        <label for="picture" class="text-sm sm:text-base md:text-lg lg:text-xl">写真：</label>
        <div class="flex flex-col sm:flex-row justify-center items-center">
          <input type="file" name="picture" id="picture" accept="image/*" class="w-full h-11 py-2 my-2">
        </div>
      </div>
      <div class="flex justify-center">
        <?php if (!empty($row['picture'])) : ?>  <!-- データに画像があればエンコードしたものを表示 -->
          <img src="data:image/jpeg;base64,<?= base64_encode($row['picture']) ?>" alt="写真" id="preview" class="max-w-100% max-h-[300px]">
        <?php else : ?>  <!-- データに画像がなければ空のsrcを持つimg要素を作成 -->
          <img src="" id="preview" class="hidden max-w-100% max-h-[300px]" alt="選択した画像のプレビュー">
        <?php endif; ?>
        <!-- else追加→既存画像なしでもimg要素を作成→jsでpreviewを操作できる -->
      </div>
      <div class="w-full mt-4 flex justify-around">
        <button type="button" onclick="location.href='index.php'" class="w-1/4 border border-slate-200 rounded-md py-3 px-6 bg-[#D1D1D1] md:bg-transparent md:hover:bg-[#D1D1D1] p-2 m-2"><i class="fas fa-long-arrow-alt-left"></i></button>
        <button type="submit" class="w-1/4 border border-slate-200 rounded-md py-3 px-6 bg-[#93CCCA] md:bg-transparent md:hover:bg-[#93CCCA] p-2 m-2"><i class="fas fa-check-circle"></i></button>
      </div>
    </div>
  </form>
  <!-- Form[End] -->
</div>
<!-- Main[End] -->
</body>

<!-- edit.php用のjsファイルを読み込み -->
<script src="js/edit.js"></script>

<!-- footer -->
<?php include 'foot.php'; ?>