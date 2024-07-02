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
    // ファイルが選択されていない場合、既存の画像を維持
    if (preview.src === '') {
      preview.classList.add('hidden'); // 既存の画像もない場合は隠す
    }
  }
}

// ページ読み込み時に初期化するために呼び出し
window.addEventListener('load', initialize);

function initialize() {
  const preview = document.getElementById('preview');
  if (preview.src !== '') {
    preview.classList.remove('hidden');
  }
}

document.getElementById('picture').addEventListener('change', previewFile);