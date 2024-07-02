function previewFile() {
  const fileInput = document.getElementById('picture');
  const preview = document.getElementById('preview');

  // 選択されたファイルを取得
  const file = fileInput.files[0];

  // FileReaderを使用して内容を読み込み
  if (file) {
    const reader = new FileReader();

    reader.onload = function (event) {
      preview.src = event.target.result;
      preview.classList.remove('hidden'); // プレビューを表示する
    }

    reader.readAsDataURL(file);
  } else {
// ファイルが選択されていない場合、プレビューを隠す
preview.src = ''; // プレビュー画像を空にする
preview.classList.add('hidden'); // プレビューを隠す
}
}

// ページ読み込み時に初期化するために呼び出し
window.addEventListener('load', initialize);

function initialize() {
  const preview = document.getElementById('preview');
  if (preview.src !== '' && !preview.classList.contains('hidden')) {
    preview.classList.remove('hidden'); // プレビューを表示する
  } else {
    preview.classList.add('hidden'); // プレビューを隠す
  }
}

document.getElementById('picture').addEventListener('change', previewFile);