import { ref } from "vue"

export function useHandleApiError() {
    const errors = ref({})

    function setError(error) {
        const res = error.response
        const data = res.data
        const resErrors = data.errors

        for (const key in resErrors) {
            if (Object.prototype.hasOwnProperty.call(resErrors, key)) {
                const errs = resErrors[key]
                errors.value[key] = errs[0]
            }
        }
    }

    return {
        setError,
        errors,
    }
}