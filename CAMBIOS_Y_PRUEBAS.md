# Correcciones de EDESSI

La revisión toma como referencia los requerimientos RF-001 a RF-008 y los casos de uso de `janet proyecto.docx`. Se conserva Laravel 12, PHP, Blade y el soporte de MariaDB.

## Funciones corregidas

| Área | Resultado |
| --- | --- |
| Acceso | Autenticación con `usuarios`, correo o C.I.; recuperación compatible con el modelo real; cuentas inactivas pierden también sesiones abiertas. |
| Roles | Solo el administrador crea, modifica y desactiva cuentas. El técnico puede recibir clientes y equipos, crear órdenes, tomar órdenes libres y trabajar en las asignadas. |
| Privacidad | El cliente solo ve sus equipos y órdenes. El técnico no puede abrir ni alterar órdenes asignadas a otro cambiando la URL. |
| Recepción | Accesorios, estado físico, falla, diagnóstico inicial, responsable y fecha estimada. No se permiten dos órdenes abiertas para el mismo equipo. |
| Reparación | Diagnóstico, costo manual en bolivianos, observaciones y repuestos. Historial de quién cambió el estado y cuándo. |
| Entrega | Se exige marcar el equipo como listo antes de entregarlo. La fecha de entrega se registra una vez. Servicios posteriores usan una nueva orden. |
| Seguimiento | Consulta con número de orden y C.I., o enlace aleatorio del QR. No muestra teléfono, dirección ni C.I. en la página pública. |
| Reportes | Comprobante PDF con QR, historial del cliente y reporte por fechas, estado y técnico; vista previa de resultados y validación de rangos. |
| Historial | Se evita borrar registros con actividad vinculada. Se pueden desactivar cuentas. Las órdenes solo se eliminan si no tienen actividad técnica. |
| Interfaz | Navegación lateral, indicadores, búsqueda, filtros, paginación, estados consistentes, tema claro/oscuro y adaptación a teléfonos. |

## Decisiones frente a diferencias del documento

- RF-003 menciona actualización por técnicos, pero CU03 reserva modificación y eliminación al administrador. Se aplica la restricción de CU03; los técnicos registran y consultan clientes.
- RF-008/CU08 mencionan C.I. **o** número de orden. Para evitar consultas de equipos ajenos por enumeración, la consulta pública exige **ambos datos**, o el enlace aleatorio del comprobante. Dentro de una cuenta, la búsqueda permite consultar órdenes propias.
- La eliminación queda restringida a registros sin actividad, para conservar un historial confiable. No se incorporaron pagos en línea ni cálculo automático de costos.

## Comprobación

La suite cubre autenticación, perfil, recuperación, roles, aislamiento entre clientes y técnicos, recepción, asignación, autoasignación, estados, entregas, repuestos, consulta pública y los tres PDF. Se ejecuta sobre SQLite en memoria y una instancia MariaDB 10.4 temporal independiente.

Se revisaron en navegador el acceso, panel, búsqueda, detalle y formularios; se comprobó un ancho de teléfono de 390 px, menú móvil y tema oscuro. Los PDF de comprobante, reporte e historial se renderizaron e inspeccionaron visualmente.

El correo requiere SMTP y un proceso de cola configurados. Las pruebas verifican generación y encolado; no se enviaron mensajes a clientes reales. La demo usa datos ficticios y `MAIL_MAILER=log`.

Resultado final: **37 pruebas y 196 comprobaciones aprobadas** tanto en SQLite como en MariaDB. Compilación Vite y caché de vistas Blade completadas sin errores.
