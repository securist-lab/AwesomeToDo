#!/bin/bash

DB_PATH="../data/db/todo.db"

# 既存のデータベースファイルを削除
if [ -f "$DB_PATH" ]; then
  rm "$DB_PATH"
  echo "Existing database removed."
fi

# データベースファイルを新規作成してテーブルを作成
mkdir -p "$(dirname "$DB_PATH")"
sqlite3 "$DB_PATH" <<EOF
CREATE TABLE todos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  due_date DATETIME NOT NULL,
  is_complete BOOLEAN DEFAULT 0
);
EOF

echo "Database created successfully: $DB_PATH"
