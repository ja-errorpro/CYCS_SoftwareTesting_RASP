<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vulnerable App for RASP Test - Refined</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&family=Inconsolata:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* 定義配色變數 */
            --primary-color: #007bff; /* 藍色 - 主要行動 */
            --primary-dark: #0056b3;
            --background-color: #f8f9fa; /* 淺灰色 - 背景 */
            --card-background: #ffffff; /* 白色 - 卡片/容器背景 */
            --border-color: #ced4da; /* 邊框顏色 */
            --text-color: #343a40; /* 深灰色 - 主要文字 */
            --code-bg: #e9ecef; /* 淺灰藍 - 代碼區塊背景 */
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            margin: 0;
            padding: 2em 1em;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .main-content {
            width: 100%;
            max-width: 650px; /* 略微放大容器 */
            background-color: var(--card-background);
            padding: 30px;
            border-radius: 8px; /* 圓角效果 */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* 柔和陰影 */
        }

        h1 {
            color: var(--primary-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--code-bg);
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        h3 {
            color: var(--primary-dark);
            font-weight: 400;
            margin-top: 25px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary-color); /* 突出標題 */
            padding-left: 10px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        code {
            font-family: 'Inconsolata', monospace;
            background-color: var(--code-bg);
            padding: 2px 4px;
            border-radius: 3px;
        }

        /* --- Form Elements --- */

        textarea {
            width: 100%;
            height: 120px; /* 略微增加高度 */
            padding: 10px;
            margin-bottom: 15px;
            resize: vertical; /* 允許垂直拖曳 */
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: 'Inconsolata', monospace;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        textarea:focus {
            border-color: var(--primary-color);
            outline: none; /* 移除預設的焦點外框 */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* 輕微焦點光暈 */
        }

        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        button:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        /* --- Result Display --- */

        pre {
            background-color: #212529; /* 深色背景，模擬終端機 */
            color: #28a745; /* 綠色文字，模擬成功輸出 */
            padding: 15px;
            border: 1px solid #1c1f23;
            border-radius: 4px;
            white-space: pre-wrap; /* 允許換行 */
            word-wrap: break-word; /* 長字串自動換行 */
            font-family: 'Inconsolata', monospace;
            font-size: 14px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Ping Service 🧪</h1>
        <p>Enter an IP address to ping.</p>

        <form method="POST">
            <textarea name="ip" placeholder="127.0.0.1"></textarea>
            <button type="submit">Execute</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
            $ip = $_POST['ip'];
            $command = 'ping -c 4 ' . $ip;
            echo "<h3>Executing Command:</h3>";
            // 使用更醒目的標籤來顯示命令
            echo "<pre style='color: white; background-color: #495057; font-weight: 600;'>" . htmlspecialchars($command) . "</pre>"; 
            
            echo "<h3>Execution Result:</h3>";
            echo "<pre>";
            

            var_dump(shell_exec($command));

            echo "</pre>";
        }
        ?>
    </div>
</body>
</html>
