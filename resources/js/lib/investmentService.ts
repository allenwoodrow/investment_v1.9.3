// import { defineStore } from "pinia";
import { computed, ref } from 'vue'
import { useNetwork } from "@/lib/request";
import type { InvestmentPlan, InvestmentBooking, Investment, ProfitMetrics } from '../types/investments'
import type { APIError, APIResponse } from '../types/response'
// import { error } from "console";

export const investmentService =  () => {
    const plans = ref<InvestmentPlan[]>([])
    const active_plans = computed((): InvestmentPlan[] => {
        return plans.value
    })
    const network = useNetwork()

    const getPlans = (): Promise<InvestmentPlan[]> => {
        return new Promise((resolve, reject) => {
            network.fetch<InvestmentPlan[], {}>('active_plans').then((result: APIResponse<InvestmentPlan[]>) => {
                // console.log(plans)
                plans.value = result.data!
                resolve(result.data!)
            }).catch((error: Error) => {
                const err = {
                    status: false,
                    message: error.message
                }
                console.log(error)
                reject(err)
            })
        })
    }

    const getPlan = (id: number | string): InvestmentPlan | undefined => {
        const planId = Number(id)
        return active_plans.value.find((item) => item.id === planId)
    }

    const bookInvestment = (data: InvestmentBooking): Promise<Boolean> => {
        return new Promise<Boolean>((resolve, reject) => {
            network.push<Boolean, InvestmentBooking>('book_investment', data).then((response) => {
                resolve(response.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const getInvestments = (): Promise<Investment[]> => {
        return new Promise<Investment[]>((resolve, reject) => {
            network.fetch<Investment[], {}>('get_investments').then((response) => {
                console.log(response.data!)
                // @ts-ignore
                resolve(response.data!.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }

    const getMetrics = (): Promise<ProfitMetrics> => {
        return new Promise<ProfitMetrics>((resolve, reject)=> {
            network.fetch<ProfitMetrics, {}>('get_metrics').then((response) => {
                console.log(response.data!)
                // @ts-ignore
                resolve(response.data!)
            }).catch((error) => {
                reject(error)
            })
        })
    }
    
    return { getPlans, active_plans, getPlan, bookInvestment, getInvestments, getMetrics }
}