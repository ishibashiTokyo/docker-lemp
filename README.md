# Docker-LEMP

| ミドルウェア | サービス名 | ポート     | 使用イメージ          |
| ------------ | ---------- | ---------- | --------------------- |
| Nginx        | web        | 80         | nginx:1.19.0-alpine   |
| PHP          | app        | 9000, 9001 | php:7.3.19-fpm-alpine |
| MariaDB      | db         | 3306       | mariadb:latest        |
| phpMyAdmin   | phpmyadmin | 8080       | phpmyadmin/phpmyadmin |

概要

- シンプルな LEMP 構成
- alpine Linux でサイズを小さく
- Docker イメージがないような CMS を構築したり、PHP の開発環境として使用する。
- SSL 未対応
- phpMyAdmin http://localhost:8080/
- composer を初期導入

## TODO

- 設定ファイルの構成を整理

## ファイル構成

```
docker-lemp
├── .env # データベースの初期設定
├── .gitignore
├── README.md # 今読んでるこれ
├── app
│   ├── Dockerfile # php-fpmの設定ファイル
│   └── docker-xdebug.ini # xdebugの設定ファイル
├── conf
│   └── nginx
│       └── default.conf # Nginxの初期設定
├── data
│   ├── html # ドキュメントルート
│   │   └── index.php # サンプルプログラム
│   └── mysql # DBの物理ファイルが格納（.gitignore）
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
bcmath
bz2
calendar
Core
ctype
curl
date
dba
dom
exif
fileinfo
filter
ftp
gd
hash
iconv
json
ldap
libxml
mbstring
mysqli
mysqlnd
openssl
pcre
PDO
pdo_mysql
pdo_pgsql
pdo_sqlite
pgsql
Phar
posix
readline
Reflection
session
shmop
SimpleXML
snmp
soap
sockets
SPL
sqlite3
standard
sysvmsg
sysvsem
sysvshm
tidy
tokenizer
wddx
xml
xmlreader
xmlrpc
xmlwriter
xsl
zlib
```

## Xdebug

```shell
# php -v
PHP 7.3.19 (cli) (built: Jun 11 2020 21:05:09) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.3.19, Copyright (c) 1998-2018 Zend Technologies
    with Xdebug v2.9.6, Copyright (c) 2002-2020, by Derick Rethans
```

設定（./app/docker-xdebug.ini）

```ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_autostart=1
;xdebug.remote_handler=dbgp
xdebug.remote_host=host.docker.internal
xdebug.remote_port=9001
```

## メモ

- alpine Linux への接続  
  bash を指定すると存在しないというエラーが表示されるので ash を指定

  ```shell
  % docker exec -it {CONTAINER ID} ash
  ```

- Dockerfile の編集時はイメージを再構築する

  ```shell
  % docker-compose build --no-cache
  ```

  またはイメージの削除

  ```shell
  % docker images
  REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
  docker-lemp_app     latest              19182eeb031d        26 minutes ago      126MB
  ...
  % docker rmi docker-lemp_app
  ```

- php7.4 を導入しようとしたが oniguruma でエラーを吐いて断念。

- Laravel の新規プロジェクト作成  
  PHP のイメージに繋いでから以下のコマンドを実行

  ```shell
  cd /var/www/html
  composer create-project laravel/laravel プロジェクト名 --prefer-dist
  ```
