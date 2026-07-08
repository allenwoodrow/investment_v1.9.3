// interfaces.ts

interface Register {
    username: string
    email: string
    password: string
    fullname: string
    phone: string
    dob: string
    country: string
    zip: string
}

interface LoginForm {
    email: string
    password: string
}

interface AuthState {
    user: User | null;
    token: string | null | undefined
    error?: Error | null | undefined;
    refreshTokenTimeout?: NodeJS.Timeout | null;
}

interface User {
    fullname: string
    phone: string
    email: string
    dob: string
    country: string
    zip: string
    // json(): { fullname: string; phone: string; dob: string, country: string }; 
}


interface RefreshToken {
    token: string
}

interface PasswordResetRequest {
    email: string
}

interface PasswordReset {
    email: string
    token: string
    password: string
}

export interface Profile {
    id: number
    created_at: string
    email: string
    email_verified_at: string
    tradingBalance: number
    username: string
    wallet: number
}

export type { Register, User, AuthState, LoginForm, RefreshToken, PasswordResetRequest, PasswordReset }
// export type { AuthError, SignupData, UserProfile }
