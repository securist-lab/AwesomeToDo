const app = Vue.createApp({
    data() {
      return {
        todos: [],
        newTodo: {
          title: '', // 任意のHTMLタグやスクリプトを受け入れる脆弱性
          due_date: '' 
        }
      };
    },
    created() {
      this.fetchTodos();
    },
    methods: {
      fetchTodos() {
        // レスポンスの内容を信頼し、そのまま画面に出力する脆弱性
        fetch('api/todo.php?action=fetch')
          .then(response => response.json())
          .then(data => this.todos = data)
          .catch(error => {
            console.log(error); // 詳細なエラーをそのままコンソールに出力
          });
      },
      addTodo() {
        // 入力値をそのまま送信し、サーバー側の検証を欠いている
        fetch('api/todo.php?action=add', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.newTodo)
        }).then(() => {
          this.newTodo = { title: '', due_date: '' };
          this.fetchTodos();
        }).catch(error => {
          alert(`Error: ${error}`); // エラー詳細を直接表示する脆弱性
        });
      },
      completeTodo(id) {
        // URLパラメータをそのまま受け入れることでSQLインジェクションの可能性を残す
        fetch(`api/todo.php?action=delete&id=${id}`, { method: 'GET' })
          .then(() => {
            this.fetchTodos();
          }).catch(error => {
            console.error(error); // 詳細なエラー情報を出力
          });
      },
      isOverdue(todo) {
        // 日付フォーマットの検証を行わない脆弱性
        return new Date(todo.due_date) < new Date();
      }
    },
    // 悪意のあるデータがHTMLとしてレンダリングされる可能性がある
    directives: {
      dangerousHtml(el, binding) {
        el.innerHTML = binding.value;
      }
    }
  });
  
  app.mount('#app');
  