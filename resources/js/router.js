//IMPORT SECTION
import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Action from './pages/Action.vue'
import post_Action from './pages/post-Action.vue'
import store from './store.js'

import konfirmasi from './pages/konfirmasi/index.vue'
import konfirmasiView from './pages/konfirmasi/View.vue'

import Indexgudang from './pages/gudang/Index.vue'
import AddGudang from './pages/gudang/Add.vue'
import DataGudang from './pages/gudang/Gudang.vue'
import EditGudang from './pages/gudang/Edit.vue'
import ViewGudang from './pages/gudang/View.vue'


import Setting from './pages/setting/Index.vue'
import SetPermission from './pages/setting/roles/SetPermission.vue'

import IndexCoil from './pages/coil/Index.vue'
import DataCoil from './pages/coil/Coil.vue'
import EditCoil from './pages/coil/Edit.vue'


import IndexEmployee from './pages/Employee/Index.vue'
import DataEmployee from './pages/Employee/Employee.vue'
import AddEmployee from './pages/Employee/Add.vue'
import EditEmployee from './pages/Employee/Edit.vue'


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
            path: '/action/:id',
            name: 'action',
            component: Action
        },
        {
            path: '/post-action',
            name: 'post_Action',
            component: post_Action
        },
        {
            path: '/konfirmasi',
            component: konfirmasi,
            meta: { requiresAuth: true },
            children: [
                /*{
                    path: 'data',
                    name: 'konfirmasi.data',
                    component: konfirmasiData,
                    meta: { title: 'konfirmasi history' }
                },*/
                {
                    path: 'view/:id',
                    name: 'konfirmasi.view',
                    component: konfirmasiView,
                    meta: { title: 'konfirmasi aksi' }
                },
            ]
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
                },
                {
                    path: 'view',
                    name: 'gudang.view',
                    component: ViewGudang,
                    meta: { title: 'View gudang'}
                },
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
        },
        {
            path: '/coil',
            component: IndexCoil,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'coil.data',
                    component: DataCoil,
                    meta: { title: 'Coil data' }
                },
                {
                    path: 'edit/:id',
                    name: 'coil.edit',
                    component: EditCoil,
                    meta: { title: 'Edit Coil' }
                }
            ]
        },
        {
            path: '/employee',
            component: IndexEmployee,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'employee.data',
                    component: DataEmployee,
                    meta: { title: 'employee data' }
                },
                {
                    path: 'add',
                    name: 'employee.add',
                    component: AddEmployee,
                    meta: { title: 'Add New Employee' }
                },
                {
                    path: 'edit/:id',
                    name: 'employee.edit',
                    component: EditEmployee,
                    meta: { title: 'Edit Employee' }
                }
            ]
        },

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