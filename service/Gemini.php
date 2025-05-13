<?php
class Gemini
{
    // APIのベースURL
    public $baseURL = 'https://generativelanguage.googleapis.com/v1beta/models/';
    // リクエストヘッダーのオプション
    // TODO: POSTリクエストのメソッドを設定
    public $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json",
            'ignore_errors' => true
        ]
    ];

    /*
     * GeminiAPIにリクエストを送信し、レスポンスを取得するメソッド
     * @param string $prompt リクエストのプロンプト
     * @param string $model モデル名（デフォルトは 'gemini-2.0-flash'）
     * @return string|null レスポンスのテキストデータ
     */
    function chat(string $prompt, string $model = 'gemini-2.0-flash')
    {
        $url = "{$this->baseURL}{$model}:generateContent?key=" . GEMINI_API_KEY;

        // リクエストデータを作成
        $data = [
            'contents' => [
                [
                    'parts' => [['text' => $prompt]]
                ]
            ]
        ];

        // TODO: リクエストヘッダーに content 設定：json形式
        $this->options['http']['content'] = json_encode($data);

        // ストリームコンテキストを作成
        $context = stream_context_create($this->options);
        // GeminiAPIにリクエストを送信し、レスポンスを取得
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        // TODO:レスポンス(JSON)を配列にデコード
        $json = json_decode($response, true);
        // var_dump($json);
        // テキストデータを返す
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    /*
     * GeminiAPIに画像を送信し、解析結果を取得するメソッド
     * @param string $image_path 画像ファイルのパス
     * @param string $model モデル名（デフォルトは 'gemini-2.0-flash'）
     * @return array 解析結果
     */
    function image($image_path, $model = "gemini-2.0-flash")
    {
        if (!file_exists($image_path)) {
            return ['error' => '画像ファイルが見つかりません'];
        }

        $url = "{$this->baseURL}{$model}:generateContent?key=" . GEMINI_API_KEY;

        // TODO: 画像パスから画像データを取得: file_get_contents()
        $image = file_get_contents($image_path);
        // TODO: Base64エンコード: base64_encode()
        $image_base64 = base64_encode($image);
        // TODO: プロンプトを設定
        $prompt = "この写真は何ですか？";

        // リクエストデータを作成
        $data = [
            'contents' => [[
                'parts' => [
                    ['text' => $prompt],
                    [
                        'inline_data' => [
                            'mime_type' => 'image/jpeg',
                            'data' => $image_base64
                        ]
                    ]
                ]
            ]]
        ];

        // リクエストヘッダーを設定
        $this->options['http']['content'] = json_encode($data);

        // ストリームコンテキストを作成
        $context = stream_context_create($this->options);
        // GeminiAPIにリクエストを送信し、レスポンスを取得
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            $results['error'] = 'APIリクエストに失敗';
        } else {
            $json = json_decode($response, true);
            if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                $results['text'] = nl2br(htmlspecialchars($json['candidates'][0]['content']['parts'][0]['text']));
            } else {
                $results['error'] = '画像解析失敗';
            }
        }
        return $results;
    }

    /*
     * GeminiAPIを使用して翻訳を行うメソッド
     * @param string $origin 翻訳元のテキスト
     * @param string $fromLang 翻訳元の言語コード
     * @param string $toLang 翻訳先の言語コード
     * @return string|null 翻訳結果
     */
    function translate($origin, $fromLang, $toLang)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . GEMINI_API_KEY;

        $fromLang = Lang::getByCode($fromLang);
        $toLang = Lang::getByCode($toLang);

        $prompt = "Please translate from {$fromLang} to {$toLang} 
        without bracket character.
        If it cannot be translated, 
        please return it as it cannot be translated in {$toLang}.
        \n {$origin}";

        // TODO: リクエストデータを作成: parts に キー：text、値：$prompt を設定
        $data = [
            'contents' => [
                [
                    'parts' => []
                ]
            ]
        ];

        // リクエストヘッダーを設定
        $this->options['http']['content'] = json_encode($data);

        // ストリームコンテキストを作成
        $context = stream_context_create($this->options);
        // GeminiAPIにリクエストを送信し、レスポンスを取得
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        // レスポンスをデコード
        $json = json_decode($response, true);
        // テキストデータを返す
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }
}
