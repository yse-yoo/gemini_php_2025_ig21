// 現在のホストとプロトコルを取得
const HOST = `${window.location.protocol}//${window.location.host}`;

// 現在のパスからベースディレクトリを抽出（例: /myapp）
const BASE_PATH = window.location.pathname.split('/').slice(0, -1).join('/');

// TRANSLATION_URIを設定
const TRANSLATION_URI = `${HOST}${BASE_PATH}/api/ai_translate.php`;
// Custom
// const TRANSLATION_URI = `http://localhost/api/ai_translate.php`;

// CHAT_URI を設定
const CHAT_URI = `http://localhost:3000`;

console.log("TRANSLATION_URI: ", TRANSLATION_URI)
console.log("CHAT_URI: ", CHAT_URI)