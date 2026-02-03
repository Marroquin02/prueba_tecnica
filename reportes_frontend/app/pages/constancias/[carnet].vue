<script setup lang="ts">
import type { StudentDetail } from "~/types/students";

const route = useRoute();
const carnet = route.params.carnet as string;

const config = useRuntimeConfig();

const student = ref<StudentDetail | null>(null);
const isLoading = ref(true);
const error = ref<string | null>(null);
const isExporting = ref(false);
const isSendingEmail = ref(false);

const { sendByEmail } = useStudents();
const { generateStudentPDF, generateStudentPDFAsBase64, isGenerating: isPDFGenerating } = usePDF();

onMounted(async () => {
  await fetchStudentDetail();
});

const fetchStudentDetail = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    console.log("Fetching student detail for carnet:", carnet);
    console.log("API Base URL:", config.public.apiBase);

    const data = await $fetch<StudentDetail>(`/students/${carnet}/report`, {
      baseURL: config.public.apiBase as string | undefined,
    });

    console.log("Fetched student detail:", data);

    if (data) {
      student.value = data;
    }
  } catch (e) {
    error.value = e instanceof Error ? e.message : "Error al cargar estudiante";
    console.error("Error fetching student detail:", e);
  } finally {
    isLoading.value = false;
  }
};

const handleExportPDF = async () => {
  if (!student.value) return;

  isExporting.value = true;
  try {
    await generateStudentPDF(student.value, {
      filename: `constancia_${student.value.student.carnet}.pdf`
    });
  } catch (e) {
    console.error("Error exporting PDF:", e);
    error.value = "Error al exportar PDF";
  } finally {
    isExporting.value = false;
  }
};

const handleSendEmail = async () => {
  if (!student.value) return;

  isSendingEmail.value = true;
  try {
    // Generar PDF como base64
    const { base64, filename } = await generateStudentPDFAsBase64(student.value, {
      filename: `constancia_${student.value.student.carnet}.pdf`
    });

    // Enviar PDF a la API
    await sendByEmail(student.value.student.carnet, base64, filename);
    alert("Constancia enviada por correo exitosamente");
  } catch (e) {
    console.error("Error sending email:", e);
    error.value = "Error al enviar correo";
  } finally {
    isSendingEmail.value = false;
  }
};

const goBack = () => {
  navigateTo("/constancias");
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8 text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header con botón de regresar -->
      <div class="mb-6">
        <button @click="goBack" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Volver a la lista
        </button>
      </div>

      <!-- Error message -->
      <div v-if="error" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-sm text-red-700">{{ error }}</p>
      </div>

      <!-- Loading state -->
      <div v-if="isLoading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Student detail -->
      <div v-else-if="student" class="space-y-6">
        <!-- Student info card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">
                {{ student.student.full_name }}
              </h1>
              <p class="mt-1 text-sm text-gray-600">
                Carnet: {{ student.student.carnet }}
              </p>
            </div>
            <div class="flex flex-wrap gap-3">
              <button @click="handleExportPDF" :disabled="isExporting"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <svg v-if="!isExporting" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <svg v-else class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                {{ isExporting ? "Exportando..." : "Descargar PDF" }}
              </button>
              <button @click="handleSendEmail" :disabled="isSendingEmail"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <svg v-if="!isSendingEmail" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <svg v-else class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                {{ isSendingEmail ? "Enviando..." : "Enviar por correo" }}
              </button>
            </div>
          </div>

          <!-- Student details grid -->
          <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                Carrera
              </p>
              <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ student.student.career }}
              </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                Facultad
              </p>
              <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ student.student.faculty }}
              </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                CUM
              </p>
              <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ student.student.cum?.toFixed(2) }}
              </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                Materias Aprobadas
              </p>
              <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ student.student.passed_subjects_count || 0 }} /
                {{ student.student.total_career_materials || 0 }}
              </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                Progreso
              </p>
              <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ student.student.progress?.toFixed(1) }}%
              </p>
              <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                  :style="{ width: `${student.student.progress || 0}%` }"></div>
              </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estado
              </p>
              <p class="mt-1">
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  student.student.status === 'activo'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800',
                ]">
                  {{ student.student.status }}
                </span>
              </p>
            </div>
          </div>
        </div>

         <!-- Grades table -->
         <div v-if="student.student.cycles && student.student.cycles.length > 0"
           class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
           <div class="px-6 py-4 border-b border-gray-200">
             <h2 class="text-lg font-semibold text-gray-900">Materias Cursadas</h2>
             <p class="mt-1 text-sm text-gray-600">
               Total de materias: {{ student.student.subjects_count }}
             </p>
           </div>
           <div class="overflow-x-auto">
             <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                 <tr>
                   <th scope="col"
                     class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Materia
                   </th>
                   <th scope="col"
                     class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     UV
                   </th>
                   <th scope="col"
                     class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Nota
                   </th>
                   <th scope="col"
                     class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Ciclo
                   </th>
                   <th scope="col"
                     class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Estado
                   </th>
                 </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                 <template v-for="cycle in student.student.cycles" :key="cycle.id">
                   <tr v-for="subject in cycle.subjects" :key="subject.id" class="hover:bg-gray-50 transition-colors">
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                       {{ subject.name }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                       {{ subject.uv }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                       {{ subject.grade.toFixed(1) }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                       {{ cycle.name }}
                     </td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                       <span :class="[
                         'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                         subject.passed
                           ? 'bg-green-100 text-green-800'
                           : 'bg-red-100 text-red-800',
                       ]">
                         {{ subject.passed ? "Aprobado" : "Reprobado" }}
                       </span>
                     </td>
                   </tr>
                 </template>
                 <tr v-if="!student.student.cycles || student.student.cycles.length === 0">
                   <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                     No hay materias registradas
                   </td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>

        <!-- No cycles message -->
        <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">
            No hay ciclos registrados
          </h3>
          <p class="mt-1 text-sm text-gray-500">
            No se encontraron ciclos con materias para este estudiante
          </p>
        </div>
      </div>

      <!-- Student not found -->
      <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Estudiante no encontrado</h3>
        <p class="mt-1 text-sm text-gray-500">
          No se encontró información para el carnet {{ carnet }}
        </p>
        <div class="mt-6">
          <button @click="goBack"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
            Volver a la lista
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
