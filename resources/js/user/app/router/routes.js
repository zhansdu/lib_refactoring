// @ts-nocheck
import Home from '@user/pages/home/Home.vue'
import Search from '@user/pages/search/Search.vue'

const routes = [{
    path: '/',
    name: 'home',
    component: Home
}, {
    path: '/search',
    name: 'search',
    component: Search
}]

export default routes;