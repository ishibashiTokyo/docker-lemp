# Docker-LEMP

- Nginx - nginx:1.19.0-alpine
- MariaDB - mariadb:latest
- PHP - php:7.4.7-fpm-alpine

シンプルな LEMP 構成  
Docker イメージがないような CMS を構築したり、PHP の開発環境として使用する。  
SSL 未対応

## ファイル構成

```
lemp
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
