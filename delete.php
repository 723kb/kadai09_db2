<?php
require_once('funcs.php');

// DB接続
$pdo = db_conn();

// GETパラメータからidを取得
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('IDが不正です');
}
$id = $_GET['id'];

// データ取得
$stmt = $pdo->prepare('SELECT * FROM kadai09_msg_table WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    exit('該当するデータがありません');
}

// POSTデータを受け取った場合に削除処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 削除SQLを作成
    $stmt = $pdo->prepare('DELETE FROM kadai09_msg_table WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $stmt->execute();

    // データ登録処理後
    if ($status) {
        header('Location: index.php'); // 削除後、メインページにリダイレクト
        exit();
    } else {
        exit('削除に失敗しました。');
    }
}
?>

<?php include 'head.php'; ?>

<div class="min-h-screen w-5/6 flex flex-col items-center bg-[#F1F6F5] rounded-lg">
    <div class="p-4 mt-4 border rounded-md bg-white">
        <h2 class="text-lg font-semibold mb-2">以下の内容を削除しますか？</h2>
        <p><strong>名前：</strong><?= htmlspecialchars($row['name']) ?></p>
        <p><strong>内容：</strong><?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <?php if (!empty($row['picture'])): ?>
            <div class="mt-2">
                <img src="data:image/jpeg;base64,<?= base64_encode($row['picture']) ?>" alt="写真" class="max-w-full h-auto">
            </div>
        <?php endif; ?>
        <p class="mt-2"><strong>日付：</strong><?= htmlspecialchars($row['date']) ?></p>
    </div>
    <form action="" method="POST" class="mt-4">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">削除する</button>
        <button type="button" onclick="location.href='index.php'" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">キャンセル</button>
    </form>
</div>

<?php include 'foot.php'; ?>
