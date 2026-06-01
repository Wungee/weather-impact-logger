import { createRouter, createWebHistory } from 'vue-router'
import SiteList from './components/SiteList.vue'
import SiteDetail from './components/SiteDetail.vue'

const routes = [
    { path: '/', component: SiteList },
    { path: '/sites/:id', component: SiteDetail },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})