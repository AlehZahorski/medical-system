<template>
  <div class="login-container">
    <h1 class="login-title">Logowanie</h1>

    <form @submit.prevent="handleLogin" class="login-form">
      <input
          v-model="login"
          placeholder="Login"
          required
          class="login-input"
      />
      <input
          v-model="password"
          type="password"
          placeholder="Hasło"
          required
          class="login-input"
      />
      <button type="submit" class="login-button">Zaloguj</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth.js'
import { useRouter } from 'vue-router'

const login = ref('')
const password = ref('')
const error = ref(null)

const auth = useAuthStore()
const router = useRouter()

const handleLogin = async () => {
  try {
    await auth.login(login.value, password.value)
    if (auth.token) {
      router.push('/results')
    }
  } catch (err) {
    error.value = 'Błędny login lub hasło'
  }
}
</script>

<style scoped>
.login-container {
  max-width: 420px;
  margin: 100px auto;
  padding: 2rem;
  background-color: #f8f8f8;
  border: 1px solid #ccc;
  border-radius: 8px;
  color: #000;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.login-title {
  margin-bottom: 1.5rem;
  text-align: center;
  font-size: 1.5rem;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.login-input {
  padding: 0.75rem 1rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
}

.login-button {
  padding: 0.75rem 1rem;
  background-color: #4caf50; /* zielony */
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s;
}

.login-button:hover {
  background-color: #388e3c;
}

.error {
  margin-top: 1rem;
  color: #d32f2f;
  font-weight: bold;
  text-align: center;
}
</style>
