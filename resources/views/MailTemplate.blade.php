<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
</head>
<body style="margin:0;padding:24px;background-color:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:600px;margin:0 auto;background-color:#ffffff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.1);overflow:hidden;">
    
    <!-- Header -->
    <div style="background-color:#2563eb;color:#ffffff;text-align:center;padding:16px;">
      <h2 style="margin:0;font-size:20px;font-weight:bold;">Confirmación de Reserva</h2>
      <p style="margin:4px 0 0;font-size:14px;opacity:0.9;">La Farga</p>
    </div>

    <!-- Body -->
    <div style="padding:24px;color:#374151;">
      <p style="margin:0 0 16px;text-align:center;font-size:15px;line-height:1.5;">
        Se ha creado una <span style="font-weight:600;">reserva a su nombre</span> para efectuar 
        una descarga de materiales. A continuación encontrará los detalles:
      </p>

      <!-- Datos en bloques -->
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Hora Inicio</span>
        <span style="color:#111827;">{{$reserva->inicio}}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Hora Final</span>
        <span style="color:#111827;">{{$reserva->fin}}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Cantidad</span>
        <span style="color:#111827;">{{$reserva->cantidad1}}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Tipo de Camión</span>
        <span style="color:#111827;">{{$reserva->tipoCamion?->nombre}}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Matrícula</span>
        <span style="color:#111827;">{{$reserva->matricula_camion}}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Muelle</span>
        <span style="color:#111827;">{{$reserva->muelle?->nombre}}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Material</span>
        <span style="color:#111827;">{{$reserva->material1?->nombre}}</span>
      </div>
      @if($reserva->material2)
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Material 2</span>
        <span style="color:#111827;">{{$reserva->material2?->nombre}} ({{$reserva->cantidad2}})</span>
      </div>
      @endif
      <div style="display:flex;justify-content:space-between;align-items:center;background-color:#f9fafb;padding:10px 16px;border-radius:8px;margin-bottom:8px;">
        <span style="font-weight:500;color:#6b7280;">Notas</span>
        <span style="color:#111827;">{{$reserva->notas}}</span>
      </div>

      <!-- Firma -->
      <div style="margin-top:24px;text-align:center;">
        <p style="margin:0;font-weight:600;color:#111827;">Atentamente,<br>La Farga</p>
        <p style="margin:0;font-weight:300;font-size:10px;color:#111827;">Este es un correo generado automaticamente. En caso de incidencia, por favor, llamar al telefono 938594286 - 938504100 ext.329</p>
      </div>
    </div>

  </div>
</body>
</html>
