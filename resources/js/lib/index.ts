export { default as CountryData } from './countryData'
export { useAIService } from './aiService'
export { toast as notify } from './notifications'  // ADD THIS LINE

export const formatCurrency = (value: number) => {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 2 });   
}

export const formatDate = (value: any) => {
    const date = new Date(value)
    return date.toLocaleDateString('en-UK', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};