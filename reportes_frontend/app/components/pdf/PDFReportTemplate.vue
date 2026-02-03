<script setup lang="ts">
import type { StudentDetail } from '~/types/students'

interface Props {
  student: StudentDetail
}

const props = defineProps<Props>()

const formatDate = () => {
  const now = new Date()
  return now.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<template>
  <div class="pdf-template">
    <div class="pdf-container">
      <!-- Header -->
      <div class="pdf-header">
        <div class="pdf-header-content">
          <h1 class="pdf-title">CONSTANCIA DE NOTAS</h1>
          <p class="pdf-subtitle">Sistema de Gestión Académica</p>
        </div>
      </div>

      <!-- Student Info -->
      <div class="pdf-section">
        <h2 class="pdf-section-title">Información del Estudiante</h2>
        <div class="pdf-info-grid">
          <div class="pdf-info-item">
            <span class="pdf-info-label">Nombre:</span>
            <span class="pdf-info-value">{{ student.student.full_name }}</span>
          </div>
          <div class="pdf-info-item">
            <span class="pdf-info-label">Carnet:</span>
            <span class="pdf-info-value">{{ student.student.carnet }}</span>
          </div>
          <div class="pdf-info-item">
            <span class="pdf-info-label">Carrera:</span>
            <span class="pdf-info-value">{{ student.student.career }}</span>
          </div>
          <div class="pdf-info-item">
            <span class="pdf-info-label">Facultad:</span>
            <span class="pdf-info-value">{{ student.student.faculty }}</span>
          </div>
          <div class="pdf-info-item">
            <span class="pdf-info-label">CUM:</span>
            <span class="pdf-info-value">{{ student.student.cum?.toFixed(2) }}</span>
          </div>
          <div class="pdf-info-item">
            <span class="pdf-info-label">Estado:</span>
            <span class="pdf-info-value" :class="{
              'status-active': student.student.status === 'activo',
              'status-inactive': student.student.status !== 'activo'
            }">
              {{ student.student.status }}
            </span>
          </div>
        </div>
      </div>

      <!-- Progress Info -->
      <div class="pdf-section">
        <h2 class="pdf-section-title">Progreso Académico</h2>
        <div class="pdf-progress-grid">
          <div class="pdf-progress-item">
            <span class="pdf-progress-label">Materias Aprobadas:</span>
            <span class="pdf-progress-value">
              {{ student.student.passed_subjects_count || 0 }} /
              {{ student.student.total_career_materials || 0 }}
            </span>
          </div>
          <div class="pdf-progress-item">
            <span class="pdf-progress-label">Progreso:</span>
            <span class="pdf-progress-value">
              {{ student.student.progress?.toFixed(1) }}%
            </span>
          </div>
        </div>
      </div>

      <!-- Grades Table -->
      <div v-if="student.student.cycles && student.student.cycles.length > 0" class="pdf-section">
        <h2 class="pdf-section-title">Materias Cursadas</h2>
        <div class="pdf-table-container">
          <table class="pdf-table">
            <thead>
              <tr>
                <th class="pdf-table-header">Materia</th>
                <th class="pdf-table-header">UV</th>
                <th class="pdf-table-header">Nota</th>
                <th class="pdf-table-header">Ciclo</th>
                <th class="pdf-table-header">Estado</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="cycle in student.student.cycles" :key="cycle.id">
                <tr v-for="subject in cycle.subjects" :key="subject.id">
                  <td class="pdf-table-cell">{{ subject.name }}</td>
                  <td class="pdf-table-cell">{{ subject.uv }}</td>
                  <td class="pdf-table-cell">{{ subject.grade.toFixed(1) }}</td>
                  <td class="pdf-table-cell">{{ cycle.name }}</td>
                  <td class="pdf-table-cell">
                    <span :class="{
                      'status-passed': subject.passed,
                      'status-failed': !subject.passed
                    }">
                      {{ subject.passed ? 'Aprobado' : 'Reprobado' }}
                    </span>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      <!-- No cycles message -->
      <div v-else class="pdf-section">
        <p class="pdf-no-data">No hay materias registradas para este estudiante.</p>
      </div>

      <!-- Footer -->
      <div class="pdf-footer">
        <p class="pdf-footer-text">Fecha de emisión: {{ formatDate() }}</p>
        <p class="pdf-footer-text">Este documento es una constancia oficial de notas.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Ocultar elemento en la UI normal */
.pdf-template {
  position: fixed;
  left: -9999px;
  top: 0;
  z-index: -1;
}

.pdf-container {
  width: 210mm;
  min-height: 297mm;
  padding: 20mm;
  background: white;
  font-family: 'Arial', 'Helvetica', sans-serif;
  color: #333;
}

/* Header */
.pdf-header {
  text-align: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid rgb(30, 64, 175);
}

.pdf-header-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.pdf-title {
  font-size: 24px;
  font-weight: bold;
  color: rgb(30, 64, 175);
  margin: 0 0 10px 0;
  text-transform: uppercase;
}

.pdf-subtitle {
  font-size: 14px;
  color: rgb(102, 102, 102);
  margin: 0;
}

/* Sections */
.pdf-section {
  margin-bottom: 25px;
}

.pdf-section-title {
  font-size: 16px;
  font-weight: bold;
  color: rgb(30, 64, 175);
  margin: 0 0 15px 0;
  padding-bottom: 8px;
  border-bottom: 1px solid rgb(229, 231, 235);
}

/* Info Grid */
.pdf-info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

.pdf-info-item {
  display: flex;
  flex-direction: column;
}

.pdf-info-label {
  font-size: 11px;
  font-weight: bold;
  color: rgb(102, 102, 102);
  text-transform: uppercase;
  margin-bottom: 4px;
}

.pdf-info-value {
  font-size: 13px;
  color: rgb(51, 51, 51);
  font-weight: 500;
}

.status-active {
  color: rgb(5, 150, 105);
  font-weight: bold;
}

.status-inactive {
  color: rgb(220, 38, 38);
  font-weight: bold;
}

/* Progress Grid */
.pdf-progress-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

.pdf-progress-item {
  display: flex;
  flex-direction: column;
}

.pdf-progress-label {
  font-size: 11px;
  font-weight: bold;
  color: rgb(102, 102, 102);
  text-transform: uppercase;
  margin-bottom: 4px;
}

.pdf-progress-value {
  font-size: 13px;
  color: rgb(51, 51, 51);
  font-weight: bold;
}

/* Table */
.pdf-table-container {
  overflow: hidden;
  border: 1px solid rgb(229, 231, 235);
  border-radius: 4px;
}

.pdf-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 11px;
}

.pdf-table-header {
  background-color: rgb(30, 64, 175);
  color: white;
  font-weight: bold;
  padding: 8px 12px;
  text-align: left;
  text-transform: uppercase;
  font-size: 10px;
}

.pdf-table-cell {
  padding: 8px 12px;
  border-bottom: 1px solid rgb(229, 231, 235);
}

.pdf-table tbody tr:last-child .pdf-table-cell {
  border-bottom: none;
}

.pdf-table tbody tr:nth-child(even) {
  background-color: rgb(249, 250, 251);
}

.status-passed {
  color: rgb(5, 150, 105);
  font-weight: bold;
}

.status-failed {
  color: rgb(220, 38, 38);
  font-weight: bold;
}

/* No Data */
.pdf-no-data {
  text-align: center;
  color: rgb(102, 102, 102);
  font-style: italic;
  padding: 20px;
}

/* Footer */
.pdf-footer {
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid rgb(229, 231, 235);
  text-align: center;
}

.pdf-footer-text {
  font-size: 10px;
  color: rgb(102, 102, 102);
  margin: 4px 0;
}

/* Print styles */
@media print {
  .pdf-template {
    position: static;
    left: auto;
    top: auto;
    z-index: auto;
  }
}
</style>
