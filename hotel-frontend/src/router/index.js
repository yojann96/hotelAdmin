import { createRouter, createWebHistory } from 'vue-router'
import HotelList from '@/views/HotelList.vue'
import HabitacionList from '@/views/HabitacionList.vue'

const routes = [{
        path: '/',
        name: 'HotelList',
        component: HotelList,
    },
    {
        path: '/habitaciones',
        name: 'habitaciones',
        component: HabitacionList,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router