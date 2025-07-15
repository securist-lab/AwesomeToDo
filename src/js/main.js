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
    async fetchTodos() {
      try {
        const response = await fetch('/api/todo.php/fetch');
        if (!response.ok) throw new Error('Failed to fetch todos');
        this.todos = await response.json();
      } catch (error) {
        console.error(error);
      }
    },
    async addTodo() {
      try {
        const response = await fetch('/api/todo.php/add', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.newTodo)
        });
        if (!response.ok) throw new Error('Failed to add todo');
        this.newTodo = { title: '', due_date: '' };
        this.fetchTodos();
      } catch (error) {
        console.error(error);
      }
    },
    async completeTodo(id) {
      try {
        const response = await fetch(`/api/todo.php/delete?id=${id}`, { method: 'GET' });
        if (!response.ok) throw new Error('Failed to delete todo');
        this.fetchTodos();
      } catch (error) {
        console.error(error);
      }
    },
    isOverdue(todo) {
      return new Date(todo.due_date) < new Date();
    }
  }
});

app.mount('#app');
