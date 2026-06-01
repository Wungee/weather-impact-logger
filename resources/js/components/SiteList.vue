<template>
  <div>
    <h1>Construction Sites</h1>

    <form @submit.prevent="createSite">
      <input v-model="newSite.name" placeholder="Site name" required />
      <input v-model="newSite.location" placeholder="Location (e.g. Atlanta, GA)" required />
      <button type="submit">Add Site</button>
    </form>

    <ul v-if="sites.length">
      <li v-for="site in sites" :key="site.id">
        <router-link :to="'/sites/' + site.id">
          {{ site.name }} — {{ site.location }}
        </router-link>
      </li>
    </ul>
    <p v-else>No sites yet. Add one above.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sites: [],
      newSite: { name: '', location: '' }
    }
  },
  async mounted() {
    const res = await fetch('/api/sites')
    this.sites = await res.json()
  },
  methods: {
    async createSite() {
      const res = await fetch('/api/sites', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(this.newSite)
      })
      const site = await res.json()
      this.sites.push(site)
      this.newSite = { name: '', location: '' }
    }
  }
}
</script>

<style scoped>
h1 { margin-bottom: 20px; }
form { display: flex; gap: 10px; margin-bottom: 24px; }
input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; flex: 1; }
button { padding: 8px 16px; background: #1a1a2e; color: white; border: none; border-radius: 4px; cursor: pointer; }
ul { list-style: none; }
li { padding: 12px; background: white; margin-bottom: 8px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
a { text-decoration: none; color: #1a1a2e; font-weight: bold; }
</style>