export interface Student {
    carnet: string
    full_name: string
    cum: number
    career: string
    faculty: string
    subjects_count: number
    email?: string
    status?: 'activo' | 'inactivo'
}

export interface PaginationMeta {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
}

export interface StudentResponse {
    data: Student[]
    meta: PaginationMeta
}

export interface StudentFilters {
    searchTerm?: string
    page?: number
    perPage?: number
}
