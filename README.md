## 下載必要軟體

### 下載Docker Desktop

- For windows OS 在這裡下載 https://hub.docker.com/editions/community/docker-ce-desktop-windows/
- For Mac OS 在這裡下載 https://hub.docker.com/editions/community/docker-ce-desktop-mac/

## 環境建立

- For Windows請打開PowerShell指令工具
- For Mac請打開Terminal指令工具

以指令前往一個空的路徑, 用來下載作業檔案, ex: cd D:/

以git指令下載作業檔案

`git clone https://github.com/soju-sai/symphoxHW.git symphox`

啟動docker虛擬環境

```
cd symphox
docker-compose build
docker-compose up -d
```

## 測試服務

- 測試產生縮網址服務

以瀏覽器開啟服務首頁

`http://127.0.0.1:8080/`

輸入任意網址，按下submit

畫面上即會產生縮網址


- 測試縮網址的原網址轉址服務

將產生的縮網址和服務首頁結合，即可導向原網址

`http://127.0.0.1:8080/{縮網址}`


## 使用unit test

在symphox路徑下, 輸入docker指令查詢容器的container id

`docker ps`

輸出範例如下：

```
CONTAINER ID        IMAGE                 COMMAND                  CREATED             STATUS              PORTS                                NAMES
e7b9ce94f174        symphox_symphox-app   "docker-php-entrypoi…"   4 minutes ago       Up 4 minutes        0.0.0.0:8080->80/tcp                 symphox-app
903c88888c59        symphox_symphox-db    "docker-entrypoint.s…"   9 minutes ago       Up 4 minutes        33060/tcp, 0.0.0.0:13306->3306/tcp   symphox-db
```

將下方指令的{CONTAINER ID}以symphox_symphox-app的CONTAINER ID替換並執行

`docker exec -it {CONTAINER ID} bash`

進入專案路徑

`cd /var/www/html`

分別執行以下unit test

```
./vendor/bin/phpunit tests/DatabaseTest.php
./vendor/bin/phpunit tests/URLConvertorTest.php
./vendor/bin/phpunit tests/ValidationTest.php
```
