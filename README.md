# AwesomeToDo

## 演習用ToDoアプリ 
AwesomeToDoは以下の機能を備えたシンプルなToDoアプリです。
- 日時を指定してToDoを登録
- チェックボックスをチェックするとToDoが完了
- 期日を過ぎたToDoは赤文字で表示

演習用のため、いくつかのバグや脆弱性を内包しています。

## DB
```sql
CREATE TABLE todos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  due_date DATETIME NOT NULL,
  is_complete BOOLEAN DEFAULT 0
);
```