import type { RouteRecordRaw } from "vue-router";
import {
    Login,  
    Register,
    RegistrationSuccess,
    VerificationStatus,
    ForgotPassword,
    ForgotPasswordSuccess,
    ResetPassword,
    ErrorPage,
    ResetSuccess,
    VerificationFailed,
    VerificationSuccess,
    ResendSuccess
} from '../views'
import { useAuth } from '@/lib/auth'

export const authRoutes: RouteRecordRaw = {
    path: '/auth',
    name: 'Auth',
    meta: { requiresAuth: false, isAdmin: false },
    children: [
      {
        path: 'login',
        name: 'Login',
        component: Login
      },
      {
        path: 'register',
        name: 'Register',
        component: Register
      },
      {
        path: 'logout',
        name: 'logout',
        beforeEnter: (to, from, next) => {
          const authHandler = useAuth()
          authHandler.logout()
          next({name: 'Login'})
        },
        redirect: {name: 'Login'}
      },
      {
        path: 'register-successful',
        name: 'RegistrationSuccess',
        component: RegistrationSuccess
      },
      {
        path: 'email/verification-status/:email',
        name: 'VerificationStatus',
        component: VerificationStatus
      },
      {
        path: 'verification-success',
        name: 'VerificationSuccess',
        component: VerificationSuccess
      },
      {
        path: 'email-resend',
        name: 'ResendSuccess',
        component: ResendSuccess
      },
      {
        path: 'verification-failed',
        name: 'VerificationFailed',
        component: VerificationFailed
      },
      {
        path: 'forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword
      },
      {
        path: 'request-reset-success',
        name: 'ForgotPasswordSuccess',
        component: ForgotPasswordSuccess
      },
      {
        path: 'reset-password/:email/:token',
        name: 'ResetPassword',
        component: ResetPassword,
        props: true
      },
      {
        path: 'reset-failed',
        name: 'ResetFailed',
        component: ErrorPage
      },
      {
        path: 'reset-success',
        name: 'ResetSuccess',
        component: ResetSuccess
      }
    ]
}