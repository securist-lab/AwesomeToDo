const app = Vue.createApp({
  data() {
    return {
      todos: [],
      newTodo: {
        title: '',
        due_date: ''
      }
    };
  },
  created() {
    this.fetchTodos();
  },
  methods: {
    fetchTodos() {
      fetch('api/todo.php?action=fetch')
        .then(response => response.json())
        .then(data => this.todos = data);
    },
    addTodo() {
      fetch('api/todo.php?action=add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.newTodo)
      }).then(() => {
        this.newTodo = { title: '', due_date: '' };
        this.fetchTodos();
      });
    },
    completeTodo(id) {
      fetch(`api/todo.php?action=delete&id=${id}`, { method: 'GET' })
        .then(() => {
          this.fetchTodos();
        });
    },
    isOverdue(todo) {
      return new Date(todo.due_date) < new Date();
    }
  }
});

app.mount('#app');