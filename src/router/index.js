import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/Customer',
    name: 'Customer',
   
    component: () => import(/* webpackChunkName: "about" */ '../views/Customer.vue')
  },
  {
    path: '/contact',
    name: 'Contact',
   
    component: () => import(/* webpackChunkName: "about" */ '../views/Contact.vue')
  },
  {
    path: '/Type',
    name: 'Type',
   
    component: () => import(/* webpackChunkName: "about" */ '../views/type.vue')
  },
  {
    path: '/employee',
    name: 'employee',
   
    component: () => import(/* webpackChunkName: "about" */ '../views/employee.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
