<template>
  <header class="header">
    <nav class="nav">
      <router-link to="/results" class="nav-link">Moje wyniki</router-link>
      <button class="logout-button" @click="logout">Wyloguj się</button>
    </nav>
  </header>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

function logout() {
  auth.logout().then(() => {
    router.push('/login')
  })
}
</script>

<style scoped>
.header {
  background-color: #f0f0f0;
  padding: 1rem;
}

.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-link {
  font-weight: bold;
  text-decoration: none;
  color: #000;
}

.logout-button {
  padding: 0.5rem 1rem;
  background-color: #ff4d4d;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.logout-button:hover {
  background-color: #cc0000;
}
</style>
