# 軟體測試與漏洞檢測-RASP

測試 RASP 對 Command Execution 的安全性

## Warning ⚠️

這個專案是用來練習實作 RASP 的安全性，僅用於學校課程作業，存在安全漏洞，請勿在生產環境使用

## Build

```sh
sudo docker compose up --build
```

## Manual Test

```sh
curl -X POST http://127.0.0.1:8080?ip=...
```

or open your Browser and view `http://127.0.0.1:8080`

## Automated Test

```sh
uv venv
uv pip install -r requirements.txt
```

```sh
uv run test.py
```

## Editing

修改 `php/rasp_extension/rasp.c` 來減弱或加強防禦


