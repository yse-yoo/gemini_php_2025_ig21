<?php
require_once 'env.php';
require_once 'service/Gemini.php';

$uploadedImagePath = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 画像がアップロード
    $uploadedImagePath = uploadImage();

    // GeminiAPIに画像を送信
    if ($uploadedImagePath && file_exists($uploadedImagePath)) {
        $gemini = new Gemini();
        $results = $gemini->image($uploadedImagePath);
    }
}

/**
 * 画像をアップロードする関数
 * @return string|null アップロードした画像のパス
 */
function uploadImage()
{
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return;
    }
    $uploadDir = 'uploads/';
    // 画像保存先ディレクトリがなければ作成
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // TODO: $_FILES からアップロードファイル名を取得
    $fileName = $_FILES['image']['name'];
    // 拡張子を取得
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    // アップロードファイル名
    $fileName = uniqid() . ".{$extension}";
    // アップロード画像の保存先
    $uploadedImagePath = "uploads/{$fileName}";

    // TODO: $_FILES からアップロード画像の一時ファイルパス取得
    $filePath = $_FILES['image']['tmp_name'];
    // アップロード画像を保存
    move_uploaded_file($filePath, $uploadedImagePath);

    return $uploadedImagePath;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's Image</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <main class="container mx-auto mt-10">
        <div class="flex justify-center mb-8">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" required class="border border-gray-300 p-2">
                <input type="hidden" name="MAX_FILE_SIZE" value="30000">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    送信
                </button>
            </form>
        </div>

        <?php if (isset($results['text'])): ?>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-center text-3xl mb-2">解析結果</h3>

                <?php if (!empty($uploadedImagePath) && file_exists($uploadedImagePath)): ?>
                    <div class="flex justify-center mb-6">
                        <img src="<?= htmlspecialchars($uploadedImagePath) ?>" alt="アップロード画像" class="w-96 border rounded shadow">
                    </div>
                <?php endif; ?>

                <div class="text-lg p-4 bg-gray-50 border border-gray-300 rounded">
                    <?= $results['text'] ?>
                </div>
            </div>
        <?php endif ?>
    </main>
</body>

</html>