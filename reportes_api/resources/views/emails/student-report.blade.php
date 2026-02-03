<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Notas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .student-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .student-info h2 {
            margin: 0 0 15px 0;
            color: #1e40af;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
            width: 120px;
        }
        .info-value {
            color: #111827;
        }
        .attachment-notice {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .attachment-notice p {
            margin: 0;
            color: #1e40af;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Constancia de Notas</h1>
        <p>Sistema de Gestión Académica</p>
    </div>

    <div class="content">
        <p>Estimado/a estudiante,</p>

        <p>Le informamos que se ha generado su constancia de notas académicas. A continuación encontrará la información de su expediente:</p>

        <div class="student-info">
            <h2>Información del Estudiante</h2>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $student['full_name'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Carnet:</span>
                <span class="info-value">{{ $student['carnet'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Carrera:</span>
                <span class="info-value">{{ $student['career'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Facultad:</span>
                <span class="info-value">{{ $student['faculty'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CUM:</span>
                <span class="info-value">{{ number_format($student['cum'], 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Estado:</span>
                <span class="info-value">{{ ucfirst($student['status']) }}</span>
            </div>
        </div>

        <div class="attachment-notice">
            <p>📎 Su constancia de notas en formato PDF se encuentra adjunta a este correo.</p>
        </div>

        <p style="margin-top: 20px;">Si tiene alguna pregunta o requiere información adicional, no dude en contactarnos.</p>

        <p>Atentamente,<br><strong>Sistema de Gestión Académica</strong></p>
    </div>

    <div class="footer">
        <p>Este correo fue enviado automáticamente. Por favor no responda a este mensaje.</p>
        <p>Fecha de envío: {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
