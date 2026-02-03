import type { Student, StudentResponse, StudentFilters } from '~/types/students'

export const useStudents = () => {
    const config = useRuntimeConfig()


    const students = ref<Student[]>([])
    const isLoading = ref(false)
    const error = ref<string | null>(null)
    const meta = ref({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
        from: 0,
        to: 0
    })


    const fetchStudents = async (filters: StudentFilters = {}) => {
        isLoading.value = true
        error.value = null

        try {
            console.log('API Base URL:', config.public.apiBase)
            console.log('Fetch parameters:', {
                page: filters.page || 1,
                perPage: filters.perPage || 15,
                searchTerm: filters.searchTerm || ''
            })

            const data = await $fetch<StudentResponse>('/students/minimal', {
                baseURL: config.public.apiBase as string | undefined,
                query: {
                    page: filters.page || 1,
                    perPage: filters.perPage || 15,
                    searchTerm: filters.searchTerm || ''
                }
            })

            console.log('Fetched student data:', data)
            if (data) {
                students.value = data.data
                meta.value = data.meta
            }
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'Error al cargar estudiantes'
            console.error('Error fetching students:', e)
        } finally {
            isLoading.value = false
        }
    }


    const sendByEmail = async (carnet: string, pdfBase64: string, filename: string) => {
        try {
            await $fetch(`/students/${carnet}/email-report`, {
                baseURL: config.public.apiBase as string | undefined,
                method: 'POST',
                body: {
                    pdf_base64: pdfBase64,
                    filename: filename
                }
            })
        } catch (e) {
            console.error('Error sending email:', e)
            throw e
        }
    }

    return {
        students,
        isLoading,
        error,
        meta,
        fetchStudents,
        sendByEmail
    }
}
