<template>
  <div
      class="orders-container"
      :style="{ backgroundColor: backgroundColor }"
  >
    <h1>Moje wyniki</h1>

    <div v-if="loading">Ładowanie danych pacjenta i wyników...</div>

    <template v-else>
      <div class="patient-info">
        <p><strong>Imię i nazwisko:</strong> {{ patient.name }} {{ patient.surname }}</p>
        <p><strong>Płeć:</strong> {{ patient.sex === 'male' ? 'Mężczyzna' : 'Kobieta' }}</p>
        <p><strong>Data urodzenia:</strong> {{ patient.birthDate }}</p>
      </div>

      <div v-if="orders.length === 0">Brak zamówień.</div>
      <div v-else>
        <div v-for="order in orders" :key="order.orderId" class="order-box">
          <h2>Zamówienie #{{ order.orderId }}</h2>

          <table class="tests-table">
            <thead>
            <tr>
              <th>Badanie</th>
              <th>Wynik</th>
              <th>Zakres referencyjny</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(result, index) in order.results" :key="index">
              <td>{{ result.name }}</td>
              <td>{{ result.value ?? '—' }}</td>
              <td>{{ result.reference ?? '—' }}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue'
import axios from 'axios'

const patient = ref({})
const orders = ref([])
const loading = ref(true)

const backgroundColor = computed(() => {
  if (patient.value.sex === 'male') return '#e6f5e6'
  if (patient.value.sex === 'female') return '#fceeee'
  return '#ffffff'
})

onMounted(async () => {
  function handleTokenExpired() {
    alert('Twoja sesja wygasła. Zaloguj się ponownie.')
    localStorage.removeItem('token')
    window.location.href = '/login'
  }

  try {
    const token = localStorage.getItem('token')

    const response = await axios.get('http://127.0.0.1:8000/api/results', {
      headers: {
        Authorization: `Bearer ${token}`,
      }
    })

    patient.value = response.data.patient
    orders.value = response.data.orders
  } catch (error) {
    if (error.response && error.response.status === 401) {
      handleTokenExpired()
    } else {
      console.error('Błąd ładowania wyników:', error)
    }
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.orders-container {
  padding: 2rem;
  color: #000000;
  transition: background-color 0.4s ease;
}

.patient-info {
  margin-bottom: 2rem;
  background: #f5f5f5;
  padding: 1rem;
  border-radius: 6px;
}

.order-box {
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  background: #fafafa;
}

.tests-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

.tests-table th,
.tests-table td {
  border: 1px solid #ddd;
  padding: 0.75rem;
  text-align: left;
}
</style>
