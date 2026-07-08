import type { 
  Register, AuthState, LoginForm, RefreshToken, PasswordResetRequest, PasswordReset } from "../types/Auth";
import type { APIResponse, APIError } from "../types/response";
import { useNetwork } from "@/lib/request"
import { StorageModule } from "@/lib/store";

// @ts-ignore
import { computed, ref, watch } from "vue";

// import { Supabase } from ".";
import { useRouter, useRoute } from "vue-router";
// import { defineStore, type StoreDefinition } from "pinia";
export const useAdmin = () => {
  const network = useNetwork(true)

  const user = computed(() => {
    return state.value.user
  })

  const token = computed(() => {
    return state.value.token
  })

  const state = ref<AuthState>({
    user: null,
    error: null,
    refreshTokenTimeout: null,
    token: null
  });

  const router = useRouter();
  const local = StorageModule.getInstance()

  const login = (credentials: LoginForm): Promise<APIError | null> => {
    return new Promise<APIError | null>((resolve, reject) => {
      network.push<AuthState, LoginForm>('office/login', credentials).then((response) => {
        if(response.status === true && response.data) {
          state.value = response.data
          state.value.error = null,
          state.value.refreshTokenTimeout = null
        }
        local.save('broker_admin', response.data)
        startRefreshTokenTimer()
        resolve(null)
      }).catch((error: any) => {
        reject(error as APIError)
      })
    })
  }

  const updateToken = (newToken: string) => {
    state.value.token = newToken
  }

  const refreshToken = async () => {
    network.fetch<RefreshToken, {}>('refresh_token')
      .then((token) => {
        state.value.token = token.data?.token
        startRefreshTokenTimer()
      }).catch((error) => {
        console.log(error)
        stopRefreshTokenTimer()
    })
  }

  const startRefreshTokenTimer = () => {
    stopRefreshTokenTimer()
    // parse json object from base64 encoded jwt token
    const jwtBase64 = state.value.token?.split('.')[1];
    if(jwtBase64) {
      const jwtToken = JSON.parse(atob(jwtBase64));
      const expires = new Date(jwtToken.exp * 1000);
      const timeout = expires.getTime() - Date.now() - (60 * 1000);
      state.value.refreshTokenTimeout = setTimeout(refreshToken, timeout);
    }
  }

  const stopRefreshTokenTimer = () => {
    clearTimeout(state.value.refreshTokenTimeout ?? 'refreshToken')
}

const logout = () => {
  local.remove('broker_admin');
}

const loadSession = () => {
  try {
    // const local = uselocal()
    const client = local.get('broker_admin')
    
    const authUser = client ? JSON.parse(client) : null
    if(authUser) {
      state.value.user = authUser
    }
    if(authUser.token){
      state.value.token = authUser.token
      refreshToken()
    }
  } catch (err) {
    console.log(err)
    // router.replace({name: 'Login'})
  } finally {
    return
  }
};

watch(user, (newVal) => {
  if(newVal !== null) {
    const data: AuthState = {
      user: newVal,
      token: token.value,
    }
    local.save('broker_admin', data);
  } else {
    router.replace({name: 'AdminLogin'})
  }
})

watch(token, (newVal) => {
  if(newVal !== null) {
    const data: AuthState = {
      user: user.value,
      token: newVal,
    }
    local.save('broker_admin', data);
  }
})
loadSession()
return { login, logout, user, token, updateToken }
}

export const useAuth = () => {
  // const static instance: Auth;

  // const handler: StoreDefinition;

  const network = useNetwork(false)

  const user = computed(() => {
    return state.value.user
  })
  const token = computed(() => {
    return state.value.token
  })

  const state = ref<AuthState>({
    user: null,
    error: null,
    refreshTokenTimeout: null,
    token: null
  });

  const router = useRouter();
  const local = StorageModule.getInstance()

  const login = (credentials: LoginForm): Promise<APIError | null> => {
    return new Promise<APIError | null>((resolve, reject) => {
      network.push<AuthState, LoginForm>('login', credentials).then((response) => {
        if(response.status === true && response.data) {
          state.value = response.data
          state.value.error = null,
          state.value.refreshTokenTimeout = null
        }
        local.save('broker_user', response.data)
        startRefreshTokenTimer()
        resolve(null)
      }).catch((error: any) => {
        reject(error as APIError)
      })
    })
  }

  const refreshToken = async () => {
    network.fetch<RefreshToken, {}>('refresh_token')
      .then((token) => {
        state.value.token = token.data?.token
        startRefreshTokenTimer()
      }).catch((error) => {
        console.log(error)
        stopRefreshTokenTimer()
        // const requiresAuth = route.matched.some(record => record.meta.requiresAuth);
        // if(requiresAuth) {
          // router.replace({name: 'Login'})
        // }
    })
  }

  const startRefreshTokenTimer = () => {
    stopRefreshTokenTimer()
    // parse json object from base64 encoded jwt token
    const jwtBase64 = state.value.token?.split('.')[1];
    if(jwtBase64) {
      const jwtToken = JSON.parse(atob(jwtBase64));
      const expires = new Date(jwtToken.exp * 1000);
      const timeout = expires.getTime() - Date.now() - (60 * 1000);
      state.value.refreshTokenTimeout = setTimeout(refreshToken, timeout);
    }
  }

  const signup = (userData: Register): Promise<APIError | null> => {
    return new Promise<APIError | null>((resolve, reject) => {
      // console.log(userData)
      network.push('register', userData).then((response) => {
          resolve(null)
      }).catch((error) => {
        reject(error as APIError)
      })
    })
  }

  const forgot_password = (email: PasswordResetRequest): Promise<string> => {
    return new Promise<string>((resolve, reject) => {
      network.push<string, PasswordResetRequest>('forgot-password', email).then((response) => {
          resolve(response.data!)
      }).catch((error) => {
        reject(error as APIError)
      })
    })
  }

  const reset_password = (data: PasswordReset): Promise<Boolean> => {
    return new Promise<Boolean>((resolve, reject) => {
      network.push<string, PasswordReset>('reset-password', data).then((response) => {
        resolve(true)
      }).catch((error) => {
        reject(error as APIError)
      })
    })
  }

  const stopRefreshTokenTimer = () => {
      clearTimeout(state.value.refreshTokenTimeout ?? 'refreshToken')
  }

  const logout = () => {
    local.remove('broker_admin');
    local.remove('broker_user');
  }

  const loadSession = () => {
    try {
      // const local = uselocal()
      const client = local.get('broker_user')
      
      const authUser = client ? JSON.parse(client) : null
      if(authUser) {
        state.value.user = authUser
      }
      if(authUser.token){
        state.value.token = authUser.token
        refreshToken()
      }
    } catch (err) {
      console.log(err)
      // router.replace({name: 'Login'})
    } finally {
      return
    }
  };

  watch(user, (newVal) => {
    if(newVal !== null) {
      const data: AuthState = {
        user: newVal,
        token: token.value,
      }
      local.save('broker_user', data);
    } else {
      router.replace({name: 'Login'})
    }
  })

  watch(token, (newVal) => {
    if(newVal !== null) {
      const data: AuthState = {
        user: user.value,
        token: newVal,
      }
      local.save('broker_user', data);
    }
  })
  loadSession()
  return { login, logout, user, token, signup, forgot_password, reset_password}


  // const static getInstance(): Auth {
  //     if (!Auth.instance) {
  //       Auth.instance = new Auth();
  //     }
  //     return Auth.instance;
  // }
}