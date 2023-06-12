import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/companies',
      name: 'companies',
      component: () => import('../views/CompaniesView.vue')
    },
    {
      path: '/people',
      name: 'people',
      component: () => import('../views/PeopleView.vue')
    },
    {
      path: '/positions',
      name: 'positions',
      component: () => import('../views/PositionsView.vue')
    },
    {
      path: '/position/:id',
      name: 'position',
      component: () => import('../views/PositionView.vue')
    }
  ]
})

export default router
