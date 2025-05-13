<?php
require_once 'env.php';
require_once 'service/Gemini.php';

$result = '';
// POSTリクエストが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: POSTリクエストからプロンプトを取得
    $prompt = $_POST['prompt'] ?? '';
    // デバッグ用
    // var_dump($prompt);
    
    // TODO: Geminiクラスのインスタンスを作成
    $gemini = new Gemini();
    // TODO: chat() を実行
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Gemini Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">Gemini API Chat</h1>
        <form action="" method="POST" class="space-y-4">
            <textarea name="prompt" rows="4" class="w-full p-2 border rounded" placeholder="質問を入力してください..."><?= htmlspecialchars($_POST['prompt'] ?? '') ?></textarea>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">送信</button>
        </form>

        <?php if ($result): ?>
            <div class="mt-6 bg-gray-50 p-4 rounded border">
                <h2 class="text-lg font-semibold">Geminiの回答</h2>
                <p class="mt-2 whitespace-pre-wrap"><?= nl2br(htmlspecialchars($result)) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>