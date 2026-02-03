export interface Subject {
    id: string
    name: string
    uv: number
    grade: number
    passed: boolean
}

export interface Cycle {
    id: string
    name: string
    subjects: Subject[]
}

export interface Grade {
    id: number
    grade: number
    cycle: string
    material: {
        id: number
        name: string
        code: string
        uv: number
    }
}

export interface Student {
    carnet: string
    full_name: string
    cum: number
    career: string
    faculty: string
    subjects_count: number
    passed_subjects_count?: number
    total_career_materials?: number
    email?: string
    status?: 'activo' | 'inactivo'
    grades?: Grade[]
    progress?: number
    cycles?: Cycle[]
}

export interface StudentDetail {
    student: Student
    total_grades: number
    progress: number
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
