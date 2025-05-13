<?php
require_once '../env.php';
require_once '../service/Gemini.php';
require_once '../lib/Lang.php';

// CORS設定: ワイルドカード(Access-Control-Allow-Origin: *)を使用
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// TODO: file_get_contents() リクエストデータ取得: php://input
$input = "";
// TODO: JSON形式のデータを配列にデコード
$posts = [];

// origin, fromLang, toLangの値の検証
if (!isset($posts['origin']) || !isset($posts['fromLang']) || !isset($posts['toLang'])) {
    $data = [
        'status' => 'error',
        'message' => 'Invalid input data'
    ];
    echo json_encode($data);
    exit;
}

// 翻訳
$gemini = new Gemini();
$posts['translate']  = $gemini->translate($posts['origin'], $posts['fromLang'], $posts['toLang']);

// テストデータの場合
// $posts['translate'] = "Hello";

// JSON形式でレスポンス
$json = json_encode($posts);
echo $json;