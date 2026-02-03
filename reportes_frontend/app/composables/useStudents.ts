import type { Student, StudentResponse, StudentFilters } from '~/types/students'

export const useStudents = () => {
    const config = useRuntimeConfig()

    // Estado reactivo
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

    // Fetch students con filtros
    const fetchStudents = async (filters: StudentFilters = {}) => {
        isLoading.value = true
        error.value = null

        try {
            const { data, error: fetchError } = await useFetch<StudentResponse>('/students/minimal', {
                baseURL: config.public.apiBase as string | undefined,
                query: {
                    page: filters.page || 1,
                    perPage: filters.perPage || 15,
                    searchTerm: filters.searchTerm || undefined
                },
                // Evitar cache en desarrollo
                key: `students-${filters.page || 1}-${filters.perPage || 15}-${filters.searchTerm || ''}`
            })

            if (fetchError.value) {
                const errorMessage = fetchError.value.message || 'Error desconocido al cargar estudiantes'
                throw new Error(errorMessage)
            }
            console.log('Fetched student data:', data.value)
            if (data.value) {
                students.value = data.value.data
                meta.value = data.value.meta
            }
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'Error al cargar estudiantes'
            console.error('Error fetching students:', e)
        } finally {
            isLoading.value = false
        }
    }

    // Exportar por página
    const exportToPDF = async (carnet: string) => {
        try {
            const response = await $fetch<Blob>(`/students/${carnet}/report`, {
                baseURL: config.public.apiBase as string | undefined,
                method: 'GET',
                responseType: 'blob'
            })

            // Crear link de descarga
            const url = window.URL.createObjectURL(response)
            const link = document.createElement('a')
            link.href = url
            link.download = `constancia_${carnet}.pdf`
            link.click()
            window.URL.revokeObjectURL(url)
        } catch (e) {
            console.error('Error exporting PDF:', e)
            throw e
        }
    }

    // Enviar por email
    const sendByEmail = async (carnet: string) => {
        try {
            await $fetch(`/students/${carnet}/email-report`, {
                baseURL: config.public.apiBase as string | undefined,
                method: 'POST'
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
        exportToPDF,
        sendByEmail
    }
}
