//IMPORT SECTION
import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import store from './store.js'

import Indexgudang from './pages/gudang/Index.vue'
import AddGudang from './pages/gudang/Add.vue'
import DataGudang from './pages/gudang/Gudang.vue'
import EditGudang from './pages/gudang/Edit.vue'


import Setting from './pages/setting/Index.vue'
import SetPermission from './pages/setting/roles/SetPermission.vue'

Vue.use(Router)

//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: { requiresAuth: true }
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/gudang',
            component: Indexgudang,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'gudang.data',
                    component: DataGudang,
                    meta: { title: 'Manage gudang' }
                },
                {
                    path: 'add',
                    name: 'gudang.add',
                    component: AddGudang,
                    meta: { title: 'Add New gudang' }
                },
                {
                    path: 'edit/:id',
                    name: 'gudang.edit',
                    component: EditGudang,
                    meta: { title: 'Edit gudang' }
                }
            ]
        },
        {
            path: '/setting',
            component: Setting,
            meta: { requiresAuth: true },
            children: [
                {
                    path: 'role-permission',
                    name: 'role.permissions',
                    component: SetPermission,
                    meta: { title: 'Set Permissions' }
                },
            ]
        }
    ]
});

//Navigation Guards
router.beforeEach((to, from, next) => {
    store.commit('CLEAR_ERRORS') //TAMBAHKAN BARIS INI
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let auth = store.getters.isAuth
        if (!auth) {
            next({ name: 'login' })
        } else {
            next()
        }
    } else {
        next()
    }
})
export default router