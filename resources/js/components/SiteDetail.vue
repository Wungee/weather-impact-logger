<template>
  <div v-if="site">
    <h1>{{ site.name }} <span>{{ site.location }}</span></h1>

    <div class="forms">
      <!-- Weather Form -->
      <div class="card">
        <h2>Log Weather</h2>
        <form @submit.prevent="logWeather">
          <input type="date" v-model="weather.date" required />
          <select v-model="weather.condition" required>
            <option value="">Select condition</option>
            <option value="rainy">Rainy</option>
            <option value="sunny">Sunny</option>
            <option value="windy">Windy</option>
            <option value="overcast">Overcast</option>
            <option value="cloudy">Cloudy</option>
          </select>
          <input type="number" v-model="weather.temperature" placeholder="Temp (°F)" step="0.1" />
          <input type="number" v-model="weather.precipitation" placeholder="Precipitation (mm)" step="0.1" />
          <button type="submit">Log Weather</button>
        </form>
      </div>

      <!-- Delay Form -->
      <div class="card">
        <h2>Log Delay</h2>
        <form @submit.prevent="logDelay">
          <input type="date" v-model="delay.date" required />
          <input type="number" v-model="delay.hours_delayed" placeholder="Hours delayed" step="0.5" required />
          <select v-model="delay.reason">
            <option value="">Select reason</option>
            <option value="weather">Weather</option>
            <option value="supply">Supply</option>
            <option value="labor">Labor</option>
            <option value="equipment">Equipment</option>
          </select>
          <textarea v-model="delay.notes" placeholder="Notes (optional)"></textarea>
          <button type="submit">Log Delay</button>
        </form>
      </div>
    </div>

    <!-- Correlation Stats -->
    <div class="card" v-if="stats">
      <h2>Weather Impact Analysis</h2>
      <div class="stats">
        <div class="stat rainy">
          <h3>🌧 Rainy Days: {{ stats.rainy_days_total }}</h3>
          <p>{{ stats.rainy_days_with_delays }} had delays ({{ stats.rainy_correlation_percentage }}%)</p>
        </div>
        <div class="stat sunny">
          <h3>☀️ Sunny Days: {{ stats.sunny_days_total }}</h3>
          <p>{{ stats.sunny_days_with_delays }} had delays</p>
        </div>
      </div>
    </div>

    <!-- Timeline Table -->
    <div class="card">
      <h2>Timeline</h2>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Weather</th>
            <th>Delay (hrs)</th>
            <th>Reason</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in timeline" :key="log.date + log.type" :class="log.condition">
            <td>{{ log.date }}</td>
            <td>{{ log.condition || '—' }}</td>
            <td>{{ log.hours_delayed || '—' }}</td>
            <td>{{ log.reason || '—' }}</td>
            <td>{{ log.notes || '—' }}</td>
          </tr>
        </tbody>
      </table>
      <p v-if="!timeline.length">No logs yet.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      site: null,
      stats: null,
      weather: { date: '', condition: '', temperature: '', precipitation: '' },
      delay: { date: '', hours_delayed: '', reason: '', notes: '' },
    }
  },
 computed: {
    timeline() {
      if (!this.site) return []
      const map = {}
      for (const w of this.site.weather_logs || []) {
        map[w.date] = { date: w.date, condition: w.condition }
      }
      for (const d of this.site.delay_logs || []) {
        if (!map[d.date]) map[d.date] = { date: d.date }
        map[d.date].hours_delayed = d.hours_delayed
        map[d.date].reason = d.reason
        map[d.date].notes = d.notes
      }
      return Object.values(map).sort((a, b) => a.date.localeCompare(b.date))
    }
  },
  async mounted() {
    await this.loadSite()
    await this.loadStats()
  },
  methods: {
    async loadSite() {
      const res = await fetch(`/api/sites/${this.$route.params.id}`)
      this.site = await res.json()
    },
    async loadStats() {
      const res = await fetch(`/api/sites/${this.$route.params.id}/correlation`)
      this.stats = await res.json()
    },
    async logWeather() {
      await fetch(`/api/sites/${this.$route.params.id}/weather`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(this.weather)
      })
      this.weather = { date: '', condition: '', temperature: '', precipitation: '' }
      await this.loadSite()
      await this.loadStats()
    },
    async logDelay() {
      await fetch(`/api/sites/${this.$route.params.id}/delays`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(this.delay)
      })
      this.delay = { date: '', hours_delayed: '', reason: '', notes: '' }
      await this.loadSite()
      await this.loadStats()
    }
  }
}
</script>

<style scoped>
h1 { margin-bottom: 4px; }
h1 span { font-size: 16px; font-weight: normal; color: #666; }
.forms { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 20px 0; }
.card { background: white; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 16px; }
h2 { margin-bottom: 12px; font-size: 16px; }
form { display: flex; flex-direction: column; gap: 8px; }
input, select, textarea { padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
textarea { height: 60px; resize: vertical; }
button { padding: 8px; background: #1a1a2e; color: white; border: none; border-radius: 4px; cursor: pointer; }
.stats { display: flex; gap: 16px; }
.stat { flex: 1; padding: 12px; border-radius: 4px; }
.stat.rainy { background: #e8f4fd; }
.stat.sunny { background: #fffde7; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
th { text-align: left; padding: 8px; border-bottom: 2px solid #eee; }
td { padding: 8px; border-bottom: 1px solid #eee; }
tr.rainy td:nth-child(2) { color: #1565c0; font-weight: bold; }
tr.sunny td:nth-child(2) { color: #f57f17; font-weight: bold; }
</style>