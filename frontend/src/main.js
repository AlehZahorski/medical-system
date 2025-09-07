import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.css'
import axios from 'axios'

import router from './router'
import { createPinia } from 'pinia'

const token = localStorage.getItem('token')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
