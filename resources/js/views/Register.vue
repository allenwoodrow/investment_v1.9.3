<template>
  <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logo" alt="logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <img src="@/assets/images/avatar.png" alt="Image" height="50" class="mb-3" />
                        <div class="text-900 text-3xl font-medium mb-3">{{ $t('auth.welcome') }}</div>
                        <span class="text-600 font-medium">{{ $t('auth.signUpToContinue') }}</span>
                    </div>

                    <form @submit.prevent="handleSubmit">
                      <div class="grid w-full justify-content-center">

                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="username" class="block text-900 text-xl font-medium mb-2">{{ $t('auth.username') }}</label>
                          <InputText id="username" type="text" :placeholder="$t('auth.username')" class="w-full" style="padding: 1rem" v-model="form.username" inputClass="w-full"/>
                          <InlineMessage class="w-full" severity="warn" v-if="v$.username.$error"> {{ v$.username.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="fullname" class="block text-900 text-xl font-medium mb-2">{{ $t('auth.fullName') }}</label>
                          <InputText id="fullname" type="text" :placeholder="$t('auth.fullName')" class="w-full" style="padding: 1rem" v-model="form.fullname" inputClass="w-full" />
                          <InlineMessage class="w-full" severity="warn" v-if="v$.fullname.$error"> {{ v$.fullname.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="email1" class="block text-900 text-xl font-medium">{{ $t('auth.email') }}</label>
                          <InputText id="email1" type="text" :placeholder="$t('auth.email')" class="w-full mb-2" style="padding: 1rem" v-model="form.email" inputClass="w-full" />
                          <InlineMessage class="w-full" severity="warn" v-if="v$.email.$error"> {{ v$.email.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="password1" class="block text-900 font-medium text-xl">{{ $t('auth.password') }}</label>
                          <Password id="password1" v-model="form.password" :placeholder="$t('auth.password')" :toggleMask="true" class="w-full mb-2" inputClass="w-full" :inputStyle="{ padding: '1rem' }" :feedback="true"></Password>
                          <InlineMessage class="w-full" severity="warn" v-if="v$.password.$error"> {{ v$.password.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="confirm_password" class="block text-900 font-medium text-xl mb-2">{{ $t('auth.confirmPassword') }}</label>
                          <Password id="confirm_password" v-model="form.confirm_password" :placeholder="$t('auth.confirmPassword')" :toggleMask="true" class="w-full mb-2" inputClass="w-full" :inputStyle="{ padding: '1rem' }" :feedback="false"></Password>
                          <InlineMessage class="w-full" severity="warn" v-if="v$.confirm_password.$error"> {{ v$.confirm_password.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="phone" class="block text-900 text-xl font-medium mb-2">{{ $t('auth.phoneNumber') }}</label>
                          <InputText id="phone" type="text" :placeholder="$t('auth.phoneNumber')" class="w-full mb-2" style="padding: 1rem" v-model="form.phone" inputClass="w-full"/>
                          <InlineMessage class="w-full" severity="warn" v-if="v$.phone.$error"> {{ v$.phone.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="country" class="block text-900 text-xl font-medium mb-2">{{ $t('auth.country') }}</label>
                          <Dropdown v-model="form.country" :options="countries" optionValue="countryShortCode" optionLabel="countryName" :filter="true" :showClear="true" :placeholder="$t('auth.selectCountry')" class="w-full" inputClass="w-full" />
                          <InlineMessage class="w-full" severity="warn" v-if="v$.country.$error"> {{ v$.country.$errors[0].$message }} </InlineMessage> 
                        </div>

                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <label for="dob" class="block text-900 text-xl font-medium mb-2">{{ $t('auth.dateOfBirth') }}</label>
                          <Calendar v-model="form.dob" :maxDate="maxDate" :manualInput="false" class="w-full" inputClass="w-full" />
                          <InlineMessage class="w-full" severity="warn" v-if="v$.dob.$error"> {{ v$.dob.$errors[0].$message }} </InlineMessage> 
                        </div>
                        
                        <!-- ZIP CODE FIELD REMOVED -->

                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <div class="flex align-items-left justify-content-between mb-5 gap-5">
                            <div class="flex align-items-center">
                              <Checkbox id="rememberme1" :binary="true" class="mr-2" v-model="form.accept"></Checkbox>
                              <label for="rememberme1">{{ $t('auth.acceptTerms') }} <router-link style="color: green;" :to="{name: 'Terms'}">{{ $t('auth.termsConditions') }}</router-link></label>
                              <InlineMessage class="w-full" severity="warn" v-if="v$.accept.$error"> {{ v$.accept.$errors[0].$message }} </InlineMessage> 
                            </div>
                          </div> 
                        </div>
                        
                        <div class="col-12 mb-3 md:col-6 lg:col-6">
                          <Button :label="$t('auth.register')" class="align-center justify-center p-3 text-xl"
                            type="button" @click="handleSubmit">
                          </Button>
                        </div>
                      </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</template>
  
<script lang="ts">
import { defineComponent, reactive, computed, ref } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email, minLength, maxLength, alphaNum, sameAs, numeric, helpers } from '@vuelidate/validators'
import { toast } from '@/lib/notifications'
import { useAuth } from '@/lib/auth'
import type { Register } from '../types/Auth'
import type { APIError } from '../types/response'
import { useGlobalLoader } from 'vue-global-loader'
import { useRouter  } from 'vue-router'
import { CountryData } from '@/lib'
import { useConfig } from '@/lib/appConfig'
import type { Country } from '../types/Country'
import DropDown from 'primevue/dropdown'
import Calendar from 'primevue/calendar';
import { useI18n } from 'vue-i18n'

export default defineComponent({
  setup() {
      const router = useRouter()
      const auth = useAuth()
      const { logo } = useConfig()
      const { t } = useI18n()

      const form = reactive({
          fullname: '',
          username: '',
          email: '',
          phone: '',
          password: '',
          confirm_password: '',
          country: '',
          dob: new Date(),
          accept: false,
          // zip REMOVED
      })

      const rules = computed(() => {
          return {
              fullname: { required, minLength: minLength(6) },
              username: { required, minLength: minLength(6) },
              email: { required, email },
              phone: { required, numeric },
              password: { required, minLength: minLength(8), alphaNum },
              confirm_password: { 
                required, 
                sameAs: helpers.withMessage(t('auth.passwordsDoNotMatch'), sameAs(form.password)) 
              },
              country: { required },
              dob: { required },
              accept: { 
                required: helpers.withMessage(t('auth.mustAcceptTerms'), required), 
                sameAs: helpers.withMessage(t('auth.mustAcceptTerms'), sameAs(true))  
              },
          }
      })

      const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
      const v$ = useVuelidate(rules, form)

      const handleSubmit = async () => {
          const validate = await v$.value.$validate()
          if(!validate) {
            console.log('validation failed')
              return
          }
          displayLoader()
          const data: Register = {
              username: form.username,
              email: form.email,
              password: form.password,
              fullname: form.fullname,
              phone: form.phone,
              dob: form.dob.toDateString(),
              country: form.country,
              // zip REMOVED
          }

          auth.signup(data).then((data) => {
              destroyLoader()
              toast.success(t('auth.registrationSuccessful'))
              router.push({name: 'Login'})
          }).catch((error) => {
              destroyLoader()
              const message = error as APIError
              toast.info(t('auth.registrationFailed'), message.message!)
          }).finally(() => {
              destroyLoader()
          })
      };

      const countries = computed(() => {
        let countryList = CountryData.map((country) => {
          const item: Country = {
            countryName: country.countryName,
            countryShortCode: country.countryShortCode
          }
          return item
        })
        return countryList
      })
      
      let today = new Date();
      let month = today.getMonth();
      let prevMonth = (month === 0) ? 11 : month - 1;
      const maxDate = ref(new Date());
      maxDate.value.setMonth(prevMonth);
      
      return { form, v$, handleSubmit, isLoading, logo, countries, maxDate }
  },
  components: {
    DropDown,
    Calendar
  }
})
</script>