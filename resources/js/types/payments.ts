interface MinAmount {
    currency_from: string // eth,
    currency_to: string // trx,
    min_amount?: number | undefined//0.0078999,
    fiat_equivalent?: number | undefined// 35.40626584
    is_fixed_rate: boolean | false
    is_fee_paid_by_user: boolean | true
}

interface EstimatePrice {
    currency_from: string // eth,
    currency_to: string // trx,
    amount: number,
    estimated_amount?: number//0.17061637
    amount_from?: number
}

interface CreateOrder {
    price_amount: number // 3999.5,
    price_currency: string //usd,
    pay_currency:  string //btc,
    ipn_callback_url: string // https://nowpayments.io,
    order_id: string // RGDBP-21314,
    order_description: string // Apple Macbook Pro 2019 x 1
}

interface GatewayTransaction {
    payment_id: string //5745459419,
    payment_status: string //waiting,
    pay_address: string //3EZ2uTdVDAMFXTfc6uLDDKR6o8qKBZXVkj,
    price_amount: number //3999.5,
    price_currency: string //usd,
    pay_amount: number //0.17070286,
    pay_currency: string //btc,
    order_id: string ///RGDBP-21314,
    order_description: string //Apple Macbook Pro 2019 x 1,
    ipn_callback_url?: string //https://nowpayments.io,
    created_at: string //2020-12-22T15:00:22.742Z,
    updated_at: string // 2020-12-22T15:00:22.742Z,
    purchase_id: string  //5837122679,
    amount_received?: number,
    payin_extra_id?: number | null,
    smart_contract?: string //,
    network: string //btc,
    network_precision: number //8,
    time_limit?: string | null,
    burning_percent?: number | null,
    expiration_estimate_date: string //2020-12-23T15:00:22.742Z
}

interface TransactionForm {
    payment_id: string // => [required],
    price_amount: number // => [required, decimal],
    pay_amount: number // => [required, decimal],
    price_currency: string // => [required, string],
    pay_currency: string // => [required, string],
    order_description: string | null | undefined // => [string, nullable],
    order_id: string | null | undefined // => [nullable, string],
    sub_id: string | null | undefined // => [nullable, string],
    payment_status: string // => [required, string],
    pay_address: string // => [required, string],
    expiration_estimate_date: string // => [required, string],
    network: string // => [required, string]
}

interface GatewayError {
    message: string
    errors?: Array<string>
    error?: string
}
interface Transaction {
    id: number
    payment_id: string
    price_amount: number
}

export interface Currency {
    name: string
    symbol: string
    address: string
}

export type { MinAmount, EstimatePrice, CreateOrder, GatewayTransaction, TransactionForm, GatewayError, Transaction }