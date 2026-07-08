import { createRouter, createWebHistory, useRoute, type RouteRecordRaw } from 'vue-router'
import { useAdmin, useAuth } from '@/lib/auth'

import { web as FrontPageRoutes } from './web'
import { clientRoutes as ClientRoutes } from './client'
import { authRoutes as AuthRoutes } from './auth'
import { NotFound } from '@/views'
import { adminAuth as AdminAuth, adminRoutes as Admin } from './admin'
const routes: Array<RouteRecordRaw> = [
  FrontPageRoutes,
  ClientRoutes,
  AuthRoutes,
  AdminAuth,
  Admin,
  { path: '/:pathMatch(.*)*', component: NotFound }
]
const env = import.meta.env.VITE_APP_ENV
// const base = (env === 'production') ? '' : '/enhanced-broker/'
const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

router.beforeEach((to, from, next) => {
    propagateMetadata(routes, {})
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    
    if (requiresAuth) {
      const isAdmin = to.matched.some((record) => {
        return record.meta.isAdmin === true
      })
      to.meta.isAdmin = isAdmin
      to.meta.requiresAuth = requiresAuth
      
      if(isAdmin) {
        const authHandler = useAdmin()
        if (!authHandler.user || !authHandler.token) {
          console.log('dashboard prevented at route level')
          authHandler.logout()
          next({name: 'AdminLogin'});
        } else {
          next();
        }
      } else {
        const authHandler = useAuth()
        if (!authHandler.user || !authHandler.token) {
          console.log('dashboard prevented at route level')
          authHandler.logout()
          next({name: 'Login'});
        } else {
          next();
        }
      }
      
    } else {
      next();
    }
  });
function propagateMetadata(routes: any[], meta: {}) {
  routes.forEach(route => {
      if (route.meta === undefined) {
          route.meta = meta
      }
      if (route.children !== undefined) {
          propagateMetadata(route.children, route.meta)
      }
  })
}

export default router