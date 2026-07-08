interface BankWithdraw {
    beneficiary_bank: string
    beneficiary_account: string
    beneficiary_name: string
    amount: number
    description?: string
    routing_no?: string
    sort_code?: string
}

interface WalletWithdraw {
    address: string
    symbol: string
    network: string
    amount: number
}

interface WithdrawalRequest {

}

export interface WithdrawResponse {
    twoFactor: boolean
    amount: number
    payment_id?: string
    checkpoint?: boolean
}

export interface Checkpiont {
    code_type: string
}

export type { BankWithdraw, WalletWithdraw, WithdrawalRequest }