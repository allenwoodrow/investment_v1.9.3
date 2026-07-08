interface Account {
    get balance() : string
    get name(): string
}

interface Balances {
  trading_balance: number
  active_trades: ActiveTrading
  commission: number
  wallet?: number
}

interface ActiveTrading {
  count: number
  net_gain: number
}

interface BankInfo {
  bank_name : string
  account_name : string
  account_number : string
  routing_no? : string
  sort_code?: string
}

interface WalletInfo {
  address : string
  network : string
  symbol : string
}

interface WithdrawChannels {
  bank?: BankInfo
  wallet?: WalletInfo
}

interface UserProfile {
  username: string
  email: string
  fullname: string
  dob: string
  phone: string
  zip: string
  country: string
}

interface PasswordUpdate {
  old_password: string
  new_password: string
}

export interface Analytics {
  users: number
  pending_deposits: number
  pending_bank: number
  pending_wallet: number
}

export type { Account, Balances, BankInfo, WalletInfo, WithdrawChannels, UserProfile, PasswordUpdate, ActiveTrading }