import type { BankWithdraw, WalletWithdraw, WithdrawalRequest, WithdrawResponse } from '../types/Withdrawal'
import { useNetwork } from '@/lib/request'

export const useWithdrawal = () => {
    const network = useNetwork()

    const submitBankRequest = (data: BankWithdraw): Promise<WithdrawResponse> => {
        return new Promise<WithdrawResponse>((resolve, reject) => {
            network.push<WithdrawResponse, BankWithdraw>('bank_withdraw', data)
            .then((response) => {
                resolve(response.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const validateWithdrawOtp = (amount: number, code: string, withdraw_type: string): Promise<WithdrawResponse> => {
        return new Promise((resolve, reject) => {
            network.push<WithdrawResponse, {}>('validate_withdraw_otp', {amount: amount, code: code, withdraw_type: withdraw_type})
            .then((response) => {
                resolve(response.data!)
            })
            .catch((error) => reject(error.message))
        })
    }

    const submitWalletRequest = (data: WalletWithdraw): Promise<Boolean> => {
        return new Promise<Boolean>((resolve, reject) => {
            network.push<Boolean, WalletWithdraw>('wallet_withdraw', data)
            .then((response) => {
                resolve(response.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const recentTransactions = (): Promise<WithdrawalRequest[]> => {
        return new Promise<WithdrawalRequest[]>((resolve, reject) => {
            network.fetch<WithdrawalRequest[], {}>('withdraw_history')
            .then((response) => {
                resolve(response.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }
    return { validateWithdrawOtp, submitBankRequest, submitWalletRequest,recentTransactions }
}