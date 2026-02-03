import type { StudentDetail } from '~/types/students'

export interface PDFOptions {
  filename?: string
  orientation?: 'portrait' | 'landscape'
  unit?: 'pt' | 'mm' | 'cm' | 'in'
  format?: 'a4' | 'letter' | 'legal'
}

export const usePDF = () => {
  const isGenerating = ref(false)
  const error = ref<string | null>(null)

  const generateStudentPDF = async (
    studentData: StudentDetail,
    options: Partial<PDFOptions> = {}
  ): Promise<void> => {
    isGenerating.value = true
    error.value = null

    try {
      // Importación dinámica de jspdf
      const { default: jsPDF } = await import('jspdf')
      const { default: autoTable } = await import('jspdf-autotable')

      const doc = new jsPDF({
        orientation: options.orientation || 'portrait',
        unit: options.unit || 'mm',
        format: options.format || 'a4'
      })

      const student = studentData.student
      const pageWidth = doc.internal.pageSize.getWidth()
      const pageHeight = doc.internal.pageSize.getHeight()
      const margin = 15
      const contentWidth = pageWidth - (margin * 2)

      // Colores como tuplas de 3 elementos
      const primaryColor: [number, number, number] = [30, 64, 175]
      const textColor: [number, number, number] = [51, 51, 51]
      const lightGray: [number, number, number] = [229, 231, 235]
      const successColor: [number, number, number] = [5, 150, 105]
      const errorColor: [number, number, number] = [220, 38, 38]

      // Header
      doc.setFontSize(20)
      doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2])
      doc.setFont('helvetica', 'bold')
      doc.text('CONSTANCIA DE NOTAS', pageWidth / 2, 25, { align: 'center' })

      doc.setFontSize(12)
      doc.setTextColor(102, 102, 102)
      doc.setFont('helvetica', 'normal')
      doc.text('Sistema de Gestión Académica', pageWidth / 2, 32, { align: 'center' })

      // Línea separadora
      doc.setDrawColor(primaryColor[0], primaryColor[1], primaryColor[2])
      doc.setLineWidth(0.5)
      doc.line(margin, 38, pageWidth - margin, 38)

      // Información del estudiante
      doc.setFontSize(14)
      doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2])
      doc.setFont('helvetica', 'bold')
      doc.text('Información del Estudiante', margin, 50)

      const infoData = [
        ['Nombre:', student.full_name],
        ['Carnet:', student.carnet],
        ['Carrera:', student.career],
        ['Facultad:', student.faculty],
        ['CUM:', student.cum?.toFixed(2) || 'N/A'],
        ['Estado:', student.status || 'N/A']
      ]

      doc.setFontSize(10)
      doc.setTextColor(textColor[0], textColor[1], textColor[2])
      doc.setFont('helvetica', 'normal')

      let yPos = 58
      infoData.forEach(([label, value], index) => {
        const x = margin + (index % 2) * (contentWidth / 2)
        const y = yPos + Math.floor(index / 2) * 8

        doc.setFont('helvetica', 'bold')
        doc.setTextColor(102, 102, 102)
        doc.text(label || '', x, y)

        doc.setFont('helvetica', 'normal')
        doc.setTextColor(textColor[0], textColor[1], textColor[2])
        doc.text(value || '', x + 25, y)
      })

      yPos += Math.ceil(infoData.length / 2) * 8 + 10

      // Progreso académico
      doc.setFontSize(14)
      doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2])
      doc.setFont('helvetica', 'bold')
      doc.text('Progreso Académico', margin, yPos)

      const progressData = [
        ['Materias Aprobadas:', `${student.passed_subjects_count || 0} / ${student.total_career_materials || 0}`],
        ['Progreso:', `${student.progress?.toFixed(1) || 0}%`]
      ]

      doc.setFontSize(10)
      doc.setTextColor(textColor[0], textColor[1], textColor[2])
      doc.setFont('helvetica', 'normal')

      yPos += 8
      progressData.forEach(([label, value], index) => {
        const x = margin + (index % 2) * (contentWidth / 2)
        const y = yPos + Math.floor(index / 2) * 8

        doc.setFont('helvetica', 'bold')
        doc.setTextColor(102, 102, 102)
        doc.text(label || '', x, y)

        doc.setFont('helvetica', 'normal')
        doc.setTextColor(textColor[0], textColor[1], textColor[2])
        doc.text(value || '', x + 50, y)
      })

      yPos += Math.ceil(progressData.length / 2) * 8 + 10

      // Materias cursadas
      if (student.cycles && student.cycles.length > 0) {
        doc.setFontSize(14)
        doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2])
        doc.setFont('helvetica', 'bold')
        doc.text('Materias Cursadas', margin, yPos)

        doc.setFontSize(10)
        doc.setTextColor(102, 102, 102)
        doc.setFont('helvetica', 'normal')
        doc.text(`Total de materias: ${student.subjects_count || 0}`, margin, yPos + 6)

        yPos += 12

        // Preparar datos para la tabla
        const tableData: string[][] = []
        student.cycles.forEach(cycle => {
          cycle.subjects.forEach(subject => {
            tableData.push([
              subject.name,
              subject.uv.toString(),
              subject.grade.toFixed(1),
              cycle.name,
              subject.passed ? 'Aprobado' : 'Reprobado'
            ])
          })
        })

        // Generar tabla
        autoTable(doc, {
          startY: yPos,
          head: [['Materia', 'UV', 'Nota', 'Ciclo', 'Estado']],
          body: tableData,
          headStyles: {
            fillColor: primaryColor,
            textColor: 255,
            fontStyle: 'bold',
            fontSize: 9
          },
          bodyStyles: {
            textColor: textColor,
            fontSize: 9
          },
          alternateRowStyles: {
            fillColor: [249, 250, 251]
          },
          columnStyles: {
            0: { cellWidth: 'auto' },
            1: { cellWidth: 15, halign: 'center' },
            2: { cellWidth: 20, halign: 'center' },
            3: { cellWidth: 30, halign: 'center' },
            4: { cellWidth: 25, halign: 'center' }
          },
          styles: {
            cellPadding: 3,
            overflow: 'linebreak'
          },
          didParseCell: function(data) {
            if (data.section === 'body' && data.column.index === 4) {
              if (data.cell.raw === 'Aprobado') {
                data.cell.styles.textColor = successColor
                data.cell.styles.fontStyle = 'bold'
              } else {
                data.cell.styles.textColor = errorColor
                data.cell.styles.fontStyle = 'bold'
              }
            }
          }
        })

        yPos = (doc as any).lastAutoTable.finalY + 10
      } else {
        doc.setFontSize(10)
        doc.setTextColor(102, 102, 102)
        doc.setFont('helvetica', 'italic')
        doc.text('No hay materias registradas para este estudiante.', margin, yPos + 6)
        yPos += 20
      }

      // Footer
      const footerY = pageHeight - 20
      doc.setDrawColor(lightGray[0], lightGray[1], lightGray[2])
      doc.setLineWidth(0.3)
      doc.line(margin, footerY, pageWidth - margin, footerY)

      doc.setFontSize(9)
      doc.setTextColor(102, 102, 102)
      doc.setFont('helvetica', 'normal')

      const now = new Date()
      const formattedDate = now.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })

      doc.text(`Fecha de emisión: ${formattedDate}`, pageWidth / 2, footerY + 7, { align: 'center' })
      doc.text('Este documento es una constancia oficial de notas.', pageWidth / 2, footerY + 12, { align: 'center' })

      // Guardar PDF
      doc.save(options.filename || `constancia_${student.carnet}.pdf`)

    } catch (e) {
      const errorMessage = e instanceof Error ? e.message : 'Error al generar PDF'
      error.value = errorMessage
      console.error('Error generating PDF:', e)
      throw new Error(errorMessage)
    } finally {
      isGenerating.value = false
    }
  }

  return {
    isGenerating,
    error,
    generateStudentPDF
  }
}
