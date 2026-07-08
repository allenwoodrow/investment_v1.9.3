<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img  :src="logo" alt="logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <div class="text-900 text-3xl font-medium mb-3">{{ $t('password.resetTitle') }}</div>
                        <span class="text-600 font-medium">{{ $t('password.enterNewPassword') }}</span>
                    </div>

                    <form @submit.prevent="handleSubmit">
                      <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">{{ $t('password.newPassword') }}</label>
                        <InputText id="email1" type="text" :placeholder="$t('password.newPassword')" class="w-full mb-5" style="padding: 1rem" v-model="form.password" />
                        <InlineMessage class="w-full" severity="warn" v-if="v$.password.$error"> {{ v$.password.$errors[0].$message }} </InlineMessage> 

                        <label for="email1" class="block text-900 text-xl font-medium mb-2">{{ $t('auth.confirmPassword') }}</label>
                        <InputText id="email1" type="text" :placeholder="$t('auth.confirmPassword')" class="w-full mb-5" style="padding: 1rem" v-model="form.password_confirmation" />
                        <InlineMessage class="w-full" severity="warn" v-if="v$.password_confirmation.$error"> {{ v$.password_confirmation.$errors[0].$message }} </InlineMessage> 

                                                <Button :label="$t('password.resetAction')"
                          class="w-full p-3 text-xl"
                          type="submit" :disabled="isLoading">
                        </Button>
                      </div>
                    </form>
                </div>

                <div class="w-full mt-2 justify-content-center text-center">
                    <router-link :to="{name: 'Login'}" style="color: var(--primary-color)" class="font-medium no-underline ml-2 text-right cursor-pointer">
                        {{ $t('common.backToLogin') }}
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
// @ts-ignore
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import { required, email, sameAs, helpers, minLength } from '@vuelidate/validators'
import { toast } from '@/lib/notifications'
import { useAuth } from '@/lib/auth'
import type { APIError } from '../types/response'
import { useConfig } from '../lib/appConfig'
import type { PasswordReset } from '../types/Auth'

export default defineComponent({
    props: {
        email: {
            type: String,
            require: true
        },
        token: {
            type: String,
            require: true
        }
    },
    setup($props) {
      const router = useRouter()
    const { logo } = useConfig()
    const { t } = useI18n()
      const authHandler = useAuth()

      const form = reactive({
        email: $props.email,
        token: $props.token,
        password: '',
        password_confirmation: ''
      })

      const rules = computed(() => {
        return {
          email: { required, email },
          password: { required, minLength: minLength(8)},
                    password_confirmation: { 
                        required, 
                        sameAs: helpers.withMessage(t('auth.passwordsDoNotMatch'), sameAs(form.password))}
        }
      })

      const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
      const v$ = useVuelidate(rules, form)

      const handleSubmit = async () => {
        const validate = await v$.value.$validate()

        if(!validate) {
            return
        }
        displayLoader()
        
        const data: PasswordReset = {
            email: form.email!,
            token: form.token!,
            password: form.password!
        }

        authHandler.reset_password(data).then(() => {
            destroyLoader()
            toast.success('Password reset successfully')
            
            setTimeout(() => {
                router.push({name: 'ResetSuccess'})
            }, 3000)
        }).catch((error: APIError) => {
            destroyLoader()
            toast.info('Error', error.message!)
        })
      };

      return { form, v$, isLoading, handleSubmit, logo }
    },
})
</script>