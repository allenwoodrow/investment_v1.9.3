<template>
    <div class="card">
        <TabView class="tabview-custom">
            <TabPanel>
                <template #header>
                    <div class="flex align-items-center gap-2">
                        <!-- <Avatar image="/images/avatar/amyelsner.png" shape="circle" /> -->
                        <i class="pi pi-user"></i>
                        <span class="font-bold white-space-nowrap">{{ $t('app.accountSettings.profileSettings') }}</span>
                    </div>
                </template>
                <div class="card p-fluid">
                    <h5>{{ $t('app.accountSettings.userInformation') }}</h5>
                    <div class="field grid">
                        <label for="name3" class="col-12 mb-2 md:col-2 md:mb-0">{{ $t('auth.fullName') }}</label>
                        <div class="col-12 md:col-10">
                            <InputText v-model="account_form.fullname" id="name3" type="text" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.fullname.$error"> {{ a$.fullname.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>
                    <div class="field grid">
                        <label for="email3" class="col-12 mb-2 md:col-2 md:mb-0">{{ $t('auth.email') }}</label>
                        <div class="col-12 md:col-10">
                            <InputText id="email3" type="text" v-model="account_form.email" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.email.$error"> {{ p$.new_password.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>

                    <div class="field grid">
                        <label for="email3" class="col-12 mb-2 md:col-2 md:mb-0">{{ $t('auth.phoneNumber') }}</label>
                        <div class="col-12 md:col-10">
                            <InputText id="email3" type="text" v-model="account_form.phone" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.phone.$error"> {{ a$.phone.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>

                    <div class="field grid">
                        <label for="email3" class="col-12 mb-2 md:col-2 md:mb-0">{{ $t('auth.zipCode') }}</label>
                        <div class="col-12 md:col-10">
                            <InputText id="email3" type="text" v-model="account_form.zip"/>
                            <InlineMessage class="w-full" severity="warn" v-if="a$.zip.$error"> {{ a$.zip.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>

                    <div class="field grid">
                        <label for="email3" class="col-12 mb-2 md:col-2 md:mb-0">{{ $t('auth.country') }}</label>
                        <div class="col-12 md:col-10">
                            <country-select data-pc-name="dropdown" className="p-dropdown p-component p-inputwrapper w-full" v-model="account_form.country" :country="account_form.country" topCountry="US" />
                            <InlineMessage class="w-full" severity="warn" v-if="a$.email.$error"> {{ p$.new_password.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>

                    <Divider />
                    <div class="field grid justify-content-right">
                        <Button :label="$t('common.submit')" @click="updateProfile" />
                    </div>
                </div>
            </TabPanel>
            <TabPanel>
                <template #header>
                    <div class="flex align-items-center gap-2">
                        <!-- <Avatar image="/images/avatar/amyelsner.png" shape="circle" /> -->
                        <i class="pi pi-lock"></i>
                        <span class="font-bold white-space-nowrap">{{ $t('app.accountSettings.changePassword') }}</span>
                    </div>
                </template>
                <div class="card p-fluid">
                    <h5>{{ $t('app.accountSettings.updatePassword') }}</h5>
                    <div class="field grid">
                        <label for="name3" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('auth.oldPassword') }}</label>
                        <div class="col-12 md:col-8">
                            <Password id="old_password" v-model="password_form.old_password" toggleMask />
                        </div>
                    </div>
                    <div class="field grid">
                        <label for="new_password" class="col-12 mb-2 md:col-4 md:mb-0">{{ $t('auth.newPassword') }}</label>
                        <div class="col-12 md:col-8">
                            <Password id="new_password" v-bind-value="password_form.new_password" v-model="password_form.new_password" :feedback="true" toggleMask />
                            <InlineMessage class="w-full" severity="warn" v-if="p$.new_password.$error"> {{ p$.new_password.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div>
                    <!-- <div class="field grid">
                        <label for="repeat_password" class="col-12 mb-2 md:col-4 md:mb-0">Confirm Password</label>
                        <div class="col-12 md:col-8">
                            <Password id="repeat_password" v-bind-value="password_form.repeat_password" v-model="password_form.repeat_password" toggleMask />
                            <InlineMessage class="w-full" severity="warn" v-if="p$.repeat_password.$error"> {{ p$.repeat_password.$errors[0].$message }} </InlineMessage>
                        </div>
                    </div> -->
                    <Divider />
                    <div class="field grid justify-content-right">
                        <Button :label="$t('common.submit')" @click="updatePassword" />
                        
                    </div>
                </div>
            </TabPanel>
        </TabView>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, reactive } from 'vue'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Password from 'primevue/password';
import Divider from 'primevue/divider';

// import { useProfile, toast } from '../../lib'
import { toast } from '@/lib/notifications'
import { useProfile } from '@/lib/profileService'
import { useRouter } from 'vue-router';
import { required, email, minLength } from '@vuelidate/validators';
import { useGlobalLoader } from 'vue-global-loader'
import { useVuelidate } from '@vuelidate/core'
import type { APIError } from '../../types/response';

export default defineComponent({
    setup() {
        
        const service = useProfile()
        const router = useRouter()
        const { displayLoader, destroyLoader, isLoading } = useGlobalLoader()
        const password_form = reactive({
            old_password: '',
            new_password: '',
            repeat_password: ''
        })

        const account_form = reactive({
            username: '',
            fullname: '',
            phone: '',
            dob: '',
            zip: '',
            country: '',
            email: ''
        })
        const p$ = useVuelidate(
            {
                old_password: { required },
                new_password: { required, minLength: minLength(6) }
            }, 
            password_form
        )

        const a$ = useVuelidate(
            {
                username: { required },
                fullname: { required},
                phone: { required },
                dob: { required},
                zip: { required},
                country: { required },
                email: { required, email }
            }, 
            account_form
        )

        const updatePassword = async () => {
            const validation = await p$.value.$validate()
            if(!validation) {
                // destroyLoader()
                toast.info('Input Error', 'Please enter correct password')
                // notify({
                //     title: 'Input Error',
                //     text: 'Please enter correct password'
                // })
                return
            } else {
                displayLoader()
                const data = {
                    old_password: password_form.old_password,
                    new_password: password_form.new_password
                }
                service.updatePassword(data).then(() => {
                    destroyLoader()
                    toast.success('Password updated successfully')
                    
                    setTimeout(() => {
                        router.push({name: 'logout'});
                    }, 4000)
                }).catch((error: APIError) => {
                    destroyLoader()
                    toast.info('Error', error.message!)
                    
                })
            }
        }

        const updateProfile = async () => {
            const validation = await a$.value.$validate()
            if(!validation) {
                toast.info('Input Error', 'Please enter a valid information')
                
                return
            } else {
                displayLoader()
                service.updateProfile(account_form).then((resp) => {
                    destroyLoader()
                    toast.success('Profile Updated Successfully')
                    
                    loadProfile()
                }).catch((error: APIError) => {
                    destroyLoader()
                    toast.info('Error', error.message!)
                })
            }
        }

        const loadProfile = () => {
            service.getProfile().then((profile) => {
                account_form.username = profile.username,
                account_form.fullname = profile.fullname,
                account_form.phone = profile.phone,
                account_form.dob = profile.dob,
                account_form.zip = profile.zip,
                account_form.country = profile.country,
                account_form.email = profile.email
            }).catch((error: APIError) => {
                toast.info('Error', error.message!)
                // notify({
                //     title: 'Error',
                //     text: error.message,
                //     type: 'info'
                // })
            })
        }

        onMounted(() => {
            loadProfile()
        })
        return { p$, a$, password_form, updatePassword, updateProfile, account_form }
    },
    components: {
        TabView,
        TabPanel,
        Password
    }
})
</script>