import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null
    }),
    getters: {
        isAuthenticated: state => !!state.token,
        backgroundColor: (state) => {
            if (!state.user) return '#ffffff'
            return state.user.sex === 'male' ? '#e6f5e6' : '#fceeee'
        }
    },
    actions: {
        async login(login, password) {
            const response = await axios.post('http://127.0.0.1:8000/api/login', { login, password })
            this.token = response.data.token
            this.user = response.data.user
            localStorage.setItem('token', this.token)
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        },
        async logout() {
            await axios.post('http://127.0.0.1:8000/api/logout')
            this.token = null
            this.user = null
            localStorage.removeItem('token')
            delete axios.defaults.headers.common['Authorization']
        }
    }
})
