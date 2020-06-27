# Docker-LEMP

| ミドルウェア | サービス名 | 使用イメージ          |
| ------------ | ---------- | --------------------- |
| Nginx        | web        | nginx:1.19.0-alpine   |
| PHP          | app        | php:7.3.19-fpm-alpine |
| MariaDB      | db         | mariadb:latest        |

概要

- シンプルな LEMP 構成
- alpine Linux でサイズを小さく
- Docker イメージがないような CMS を構築したり、PHP の開発環境として使用する。
- SSL 未対応

## TODO

- xdebug の導入

## ファイル構成

```
docker-lemp
├── .env # データベースの初期設定
├── .gitignore
├── README.md # 今読んでるこれ
├── app
│   └── Dockerfile # php-fpmの設定ファイル
├── conf
│   └── nginx
│       └── default.conf # Nginxの初期設定
├── data
│   ├── html # ドキュメントルート
│   │   └── index.php # サンプルプログラム
│   └── mysql # DBの物理ファイルが格納（.gitignore）
│       ├── .gitkeep
│       └── ...
├── db
│   └── initial.sql # 初期実行されるSQL文
└── docker-compose.yml
```

## Mac に mysql クライアントの導入

あまり環境を汚したくないけど Docker のデータベースに接続したいそんな感じ。

インストール

```shell
% brew install mysql-client
```

シェルのプロファイル（~/.zshrc など）に以下のパスを追加する

```
export PATH="/usr/local/opt/mysql-client/bin:$PATH"
```

設定ファイルを作成

```shell
% touch ~/.my.cnf
```

デフォルトの設定では localhost を指定すると Unix ソケットで接続を試みるので、
プロトコルを TCP に変更して、Docker 上のコンテナと接続できるようにする。

```
[client]
protocol=TCP
```

実際に接続してみると.env で指定されたユーザとデータベースが作成されていることが確認できる。

```shell
% mysql -u docker -pdocker
...
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| docker_db          |
| information_schema |
+--------------------+
2 rows in set (0.01 sec)
mysql> show grants;
+---------------------------------------------------------------+
| Grants for docker@%                                           |
+---------------------------------------------------------------+
| GRANT USAGE ON *.* TO `docker`@`%` IDENTIFIED BY PASSWORD '*' |
| GRANT ALL PRIVILEGES ON `docker\_db`.* TO `docker`@`%`        |
+---------------------------------------------------------------+
2 rows in set (0.00 sec)
```

## PHP モジュール

```shell
[PHP Modules]
Core
ctype
curl
date
dom
fileinfo
filter
ftp
gd
hash
iconv
json
libxml
mbstring
mysqli
mysqlnd
openssl
pcre
PDO
pdo_mysql
pdo_sqlite
Phar
posix
readline
Reflection
session
SimpleXML
sodium
SPL
sqlite3
standard
tokenizer
xml
xmlreader
xmlwriter
zlib
```

## メモ

alpine Linux への接続  
bash を指定すると存在しないというエラーが表示されるので ash を指定

```shell
% docker exec -it {CONTAINER ID} ash
```

Dockerfile の編集時はイメージを削除して再構築する

```shell
% docker images
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
docker-lemp_app     latest              19182eeb031d        26 minutes ago      126MB
...
% docker rmi docker-lemp_app
```

php7.4 を導入しようとしたが oniguruma でエラーを吐いて断念。
