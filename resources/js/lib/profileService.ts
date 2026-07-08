import { useNetwork } from "@/lib/request"
import type { UserProfile, PasswordUpdate, BankInfo, WithdrawChannels, WalletInfo } from '../types/Account'

export const useProfile = () => {

    const network = useNetwork()
    
    const getProfile = (): Promise<UserProfile> => {
        return new Promise<UserProfile>((resolve, reject) => {
            network.fetch<UserProfile, {}>('get_profile').then((profile) => {
                resolve(profile.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const getPayoutInfo = (): Promise<WithdrawChannels> => {
        return new Promise<WithdrawChannels>((resolve, reject) => {
            network.fetch<WithdrawChannels, {}>('get_payout_info').then((profile) => {
                resolve(profile.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const updatePassword = (data: PasswordUpdate): Promise<Boolean> => {
        return new Promise<Boolean>((resolve, reject) => {
            network.push<Boolean, PasswordUpdate>('update_password', data).then((profile) => {
                resolve(profile.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const updateProfile = (data: UserProfile) => {
        return new Promise<Boolean>((resolve, reject) => {
            network.push<Boolean, UserProfile>('update_profile', data).then((profile) => {
                resolve(profile.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const updateBank = (data: BankInfo) => {
        return new Promise<Boolean>((resolve, reject) => {
            network.push<Boolean, BankInfo>('update_bank', data).then((profile) => {
                resolve(profile.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const updateWallet = (data: WalletInfo) => {
        return new Promise<Boolean>((resolve, reject) => {
            network.push<Boolean, WalletInfo>('update_wallet', data).then((profile) => {
                resolve(profile.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    return { getProfile, updatePassword, updateProfile, updateBank, updateWallet, getPayoutInfo }
}