<template class="main">
    <div :style="{ backgroundColor: auth.backgroundColor}">
    <Header />
    <main>
        <router-view />
    </main>
</div>
</template>

<script setup>
    import Header from '../components/Header.vue'
    import { useAuthStore } from '../stores/auth.js'

    const auth = useAuthStore()
</script>
<style>
.main {
  margin: auto auto;
}
</style>