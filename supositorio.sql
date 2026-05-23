-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql300.infinityfree.com
-- Tiempo de generación: 15-05-2026 a las 06:47:08
-- Versión del servidor: 11.4.10-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_41157324_repositorio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supositorio`
--

CREATE TABLE `supositorio` (
  `id_sistema` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `ID` varchar(50) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `consulta` varchar(1000) DEFAULT NULL,
  `solucion` varchar(2000) DEFAULT NULL,
  `adjunto` varchar(255) DEFAULT NULL,
  `jira_url` text DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `categoria` varchar(100) DEFAULT 'general',
  `importante` tinyint(1) DEFAULT 0,
  `ultima_modificacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL,
  `vista_privada` varchar(50) DEFAULT 'general',
  `es_favorito` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `supositorio`
--

INSERT INTO `supositorio` (`id_sistema`, `tipo`, `ID`, `nombre`, `telefono`, `consulta`, `solucion`, `adjunto`, `jira_url`, `fecha_registro`, `categoria`, `importante`, `ultima_modificacion`, `fecha_actualizacion`, `vista_privada`, `es_favorito`) VALUES
(2, NULL, '110941', 'Néstor - México', '525585248445', 'No puede subir el certificado .cer, error F5', 'El antivirus lo estaba bloqueando, desactivó el antivirus y pudo subir el certificado sin problema.', '', '', '2026-02-16 09:20:30', '', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(3, NULL, '115175', 'Aby', '985394511', 'No se muestra documento para conciliar en bancos', 'No tiene ninguna forma de pago asociada.', '', '', '2026-02-20 08:22:51', '', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(4, NULL, '118543', 'Jose Vicente', '610226369', 'Cuanto emite factura-e al validar le dice DNI emisor incorrecto', 'No tenía marcada la opción autónomo o empresa en datos empresa.', '', '', '2026-02-16 09:48:48', 'general', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(6, NULL, '37654', 'Diana', '56989617565', 'decimales en chile', 'En chile no se usan decimales en la configuración de la cuenta.', '', '', '2026-02-16 15:45:42', '', 0, '2026-02-28 22:30:08', NULL, 'general', 0),
(7, NULL, '61685', 'ASED INTEGRALIS SL', '695335253', 'No llega ningún aviso si se crea una incidencia desde el perfil de Shop de un cliente', 'Se han hecho pruebas y efectivamente es así, si se crea un pedido desde shop sí llega el mail pero si crean una incidencia, no llega ningún mail o aviso.', '', '', '2026-02-16 10:44:09', 'general', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(8, NULL, '77027', 'Maria', '954643519', 'En traspaso de contabilidad no se muestran algunas facturas que si eligió', 'En el txt si está, pero la fecha de vencimiento sale de la franja que se ha elegido para realizar el traspaso.', '', '', '2026-02-19 14:26:39', '', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(9, NULL, '82182', 'Nutriger Solutions', '672385827', 'Bancos - Tiene que seleccionar la forma de pago cada vez que vincula un recibo', 'Solamente se mostrará la forma de pago en estos casos: 1. Que la factura tenga una forma de pago asociada a cuenta bancaria. 2. Que el recibo esté cobrado/pagado.', '', '', '2026-02-18 12:47:59', '', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(10, NULL, '85585', 'Raúl', '659526612', 'Error VALUE(numeros) en TicketBai', 'Debe acceder a Configuración  >Mi empresa > Datos empresa, pestaña Ticket BAI e indicar el código de epígrafe correcto.', '1771574170_0_image (11).png,1771574170_2_image (9).png,1771612153_0_image(3).png', '', '2026-02-19 09:17:26', '', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(30, NULL, 'NOTA-132946', 'VERIFACTU', 'NOTA', 'Verifactu Sujeto Pasivo', 'Debe añadir una línea de producto/servicio, con el importe correspondiente, en le campo Impuesto, indicar No aplicar. Al emitir Verifactu podrá indicar la exención S2- (Operación sujeta y no extenta- con inversión del sujeto pasivo), en todo caso recomendamos que lo consulte con su asesor. ', '', '', '2026-02-20 18:29:46', '', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(31, NULL, 'NOTA-133009', 'VERIFACTU', 'NOTA', 'Verifactu Gasto Suplido', 'Gastos que un profesional o empresa paga en nombre del cliente (ejemplo: un abogado que paga las tasas judiciales de su cliente).  ---> En este caso puede añadir un Capítulo, donde indicará.', '', '', '2026-02-20 18:30:09', '', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(81, NULL, 'NOTA-140438', 'TICKET BAI', 'NOTA', 'TicketBai - Sujeto Pasivo', 'Debe marcar la factura como Sujeto a IVA y los productos indicar No Aplica, de esta forma le aparecerá de nuevo el motivo de exención y sí podrá marcar la casilla de Sujeto pasivo.', '', '', '2026-02-21 19:04:37', 'General', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(421, NULL, '103864', 'Jonathan', '+52 2281711812', 'Error de F5 a la hora de timbrar una factura', 'En el apartado CFDI de la factura generada, no tenía indicado el mismo Uso CFDI que el que tiene indicado en Configuración.\r\nEn este caso, tenía indicado \"Por definir\", se ha cambiado el uso al que tiene indicado y ha permitido timbrar la factura.', '', '', '2026-02-23 17:00:45', 'General', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(432, NULL, 'NOTA-051003', 'RECURSOS', 'NOTA', 'Consultoría Premium', 'La consultoría consiste en 6 horas extra de puestas en marchas con un precio de 300 € (+ IVA).\r\nSe tiene que enviar el pedido de forma manual (lo hace Ana) y el cliente tiene que enviar el justificante, se hará siempre por transferencia bancaria.\r\nEn principio no tiene fecha de caducidad.', '1771928217_0_Captura de pantalla 2026-02-24 111325.png', '', '2026-02-24 10:10:03', 'General', 0, '2026-03-09 21:45:55', '2026-03-09 22:45:55', 'general', 0),
(433, NULL, 'NOTA-070203', 'VERIFACTU', 'NOTA', 'Si el cliente solicita modificar los datos fiscales de una factura que ya le hemos facilitado y que ya está emitida en Verifactu', 'El panel igualmente te deja modificarlos, pero no se puede modificar una factura con Verifactu emitido. Me ha comentado @Narci Puertas que tendría que hacerle administración una factura rectificativa. ', '', '', '2026-02-24 12:02:03', 'General', 0, '2026-03-03 20:51:17', NULL, 'general', 0),
(434, NULL, '113404', 'Lola', '654566245', 'No puede guardar tickets en compras', 'Debe elegir categoría de gasto para poder guardar.', '', '', '2026-02-24 12:21:56', 'General', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(444, NULL, 'NOTA-114936', 'LATAM', 'NOTA', 'No coinciden valores totales de libro de facturas con exportado en excel', 'Revisar SIEMPRE la tasa de moneda.', '', '', '2026-02-25 16:49:35', 'General', 0, '2026-03-03 19:59:22', NULL, 'general', 0),
(451, NULL, 'NOTA-114956', 'PROYECTOS', 'NOTA', 'ANALIZAR A TRAVÉS DE DOCUMENTOS DE COMPRAS', 'Albaranes relacionados con facturas\r\n-Si generamos las facturas a partir de los albaranes, pero quitamos el botón de \"Analizar\"  de estas facturas, el programa no analizará los gastos de esos albaranes, ya que detecta que se ha generado una factura posterior.\r\n-Para que el programa analice solamente los gastos de los albaranes, debemos generarlos de forma independiente, esto es, no crear a partir de ellos las facturas de los proveedores.\r\nUna opción que puede aplicar es duplicar los albaranes de proveedor y crear un estado personalizado que sea \"Relacionado a Proyecto\" o algo parecido, para que sepa que ese albarán no necesita factura.\r\n-En caso de que la factura de proveedor solo tenga líneas que pertenezcan a un solo proyecto, puede generar la factura a partir del albarán y analizar ambos documentos.\r\nEl programa cogerá el total de la factura de proveedor para analizar los gastos.', '', '', '2026-02-26 16:49:57', 'General', 0, '2026-03-27 12:10:00', '2026-03-27 13:10:00', 'general', 0),
(452, NULL, 'NOTA-115009', 'PROYECTOS', 'NOTA', 'ANALIZAR A TRAVÉS DE UN ALBARÁN DE TRABAJO', 'Tendría que incluirse el precio de compra en la línea del albarán.\r\nSi no está incluido el precio de compra en la línea, lo tomará del precio de compra incluido en la ficha del producto o servicio.', '', '', '2026-02-26 16:50:10', 'General', 0, '2026-03-27 12:09:43', '2026-03-27 13:09:43', 'general', 0),
(453, NULL, 'NOTA-120831', 'RECURSOS', 'NOTA', 'FÓRMULA PARA PONER FORMATO TEXTO A LA HORA DE IMPORTAR/ACTUALIZAR FICHERO PARA FECHAS', '=TEXTO(C2:C695;\"dd/mm/aaaa\")\r\nEn “C2:C695” tenemos que indicar la columna donde aparece la fecha que nos da el cliente\r\n\r\nEn el fichero Excel puede aplicar la fórmula de fechas, al lado de la columna de Fechas, debe generar dos nuevas columnas y aplique la siguiente fórmula en una de ellas: =TEXTO(A2;\"dd/mm/aaaa\"), una vez aplicada copie la información y pégala en la segunda columna, debe pegarla Sin Valores.\r\nSeguidamente eliminar la primera y segunda columna, debe quedarse solo con la que ha pegado Sin Valores y verificar si ya puede actualizar masivamente.', '', '', '2026-02-26 17:08:32', 'General', 0, '2026-03-09 21:45:30', '2026-03-09 22:45:30', 'general', 0),
(454, NULL, 'NOTA-121045', 'LATAM', 'NOTA', 'SII = Suministro Inmediato de Información', 'Si se refiere al Suministro Inmediato de Información (SII), desde STEL Order no tenemos conexión directa\r\nPor lo que no sería posible desde nuestro software\r\nEl Suministro Inmediato de Información (SII) es el nuevo sistema de suministro electrónico de los Libros Registro del IVA desarrollado por la Agencia Tributaria (AEAT), surgido a partir de la necesidad de agilizar la tramitación del IVA y de mejorar la lucha contra el fraude fiscal.', '', '', '2026-02-26 17:10:45', 'General', 0, '2026-03-03 19:57:29', NULL, 'general', 0),
(455, NULL, 'NOTA-121408', 'RECURSOS', 'NOTA', 'GENERAR UN MAYOR', 'STEL Order no es un software contable, por lo que generar un mayor como tal no es posible, aunque puede consultar sus datos de facturación en el apartado Facturación > Facturas y filtrar por cliente y estado (Pendiente o Cobrada)\r\nEsta información es posible exportarla, seleccionando las facturas y pulsando en Más > Exportar > Excel.', '', '', '2026-02-26 17:14:09', 'General', 0, '2026-03-09 21:45:12', '2026-03-09 22:45:12', 'general', 0),
(456, NULL, 'NOTA-122724', 'SHOP', 'NOTA', 'dominio propio EN SHOP', 'Para utilizar su domino en STEL Shop lo deben realizar por medio de su gestor de dominio.\r\nDesde su página web, tienen que utilizar la etiqueta HTML <iframe> para encapsular la página creada en Shop, con ello, se mantendrá la URL de su página y se mostrará su STEL Shop con su dominio.', '', '', '2026-02-26 17:27:25', 'General', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(457, NULL, 'NOTA-122824', 'BANCOS', 'NOTA', 'VINCULACIÓN CUENTAS BANCARIAS A STEL ORDER', 'Adicional, para poder vincular la cuenta al programa, primero es necesario que compruebe los detalles que le indico:\r\nTiene que figurar como propietario de la cuenta que desea vincular, si solo figura como apoderado, no podrá vincularla\r\nEs necesario que la cuenta que desea vincular sea la principal, es decir, si posee varias cuentas dentro del mismo banco, tiene que configurar una de ellas como la principal\r\nCompruebe los datos que le indico y vuelva a intentar vincularlo. En caso de que no le permita, si le aparece algún mensaje de error, por favor, realice una captura de pantalla y vuelva a ponerse en contacto con nosotros.\r\nSi no le aparece ningún aviso y no le permite vincularla, le aconsejamos que contacte con su banco y pregunte si disponen de los permisos para compartir datos con otras aplicaciones.', '', '', '2026-02-26 17:28:24', 'General', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(468, NULL, 'NOTA-041501', 'VERIFACTU', 'NOTA', 'info sobre verifactu', 'PDF con información en adjuntos.', '1772183701_0_SO-Sistema Verifactu-270226-083522.pdf', '', '2026-02-27 09:15:01', 'General', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(469, NULL, 'NOTA-042120', 'TAREAS AUT.', 'NOTA', 'tareas automáticas', 'No permite seleccionar una fecha de inicio superior a un año.', '', '', '2026-02-27 09:21:21', 'General', 0, '2026-03-03 20:49:55', NULL, 'general', 0),
(472, NULL, '85270', 'Mexicana pesada', '+524424675616', 'A qué hace referencia ', 'Una forma de comprobarlo es Descargar resumen de las facturas de proveedor.\r\nHay una columna llamada Tipo que lo va a indicar:\r\n-Servicios: facturas de proveedor de los servicios del catálogo > En informes \"Compras de servicios\"\r\n-Productos: facturas de proveedor de los productos del catálogo > En informes \"Compras de productos\"\r\n-Gasto e inversión \"\" \"\" > En Informes aparecerá la categoría del gasto indicada para cada gasto e inversión.', '1772214171_0_Captura de pantalla 2026-02-27 183742.png,1772214171_1_Captura de pantalla 2026-02-27 183650.png', '', '2026-02-27 17:42:51', 'General', 0, '2026-02-28 23:42:02', NULL, 'general', 0),
(488, NULL, '108447', 'Elisabert', '613282211', 'problema al descargar resumen en facturas venta y compra', 'La cuenta está de baja y no permite exportar resumen en facturación.', '', 'https://stelorder.atlassian.net/browse/SUP-623?actionerId=6033cd57f032740068520327&sourceType=assign&atlOrigin=eyJpIjoiNzA0ZjRiZDg1Zjc5NDFiM2E2OTcxMTUzZDE4YWQ4NmMiLCJwIjoiaiJ9', '2026-02-28 15:44:16', 'General', 0, '2026-02-28 23:04:03', NULL, 'general', 0),
(537, NULL, 'NOTA-182107', 'RECURSOS', 'NOTA', 'Solicitud de STEL Order desde SOLO Tempo:', 'Existen 3 situaciones diferentes y se crea 3 Tareas diferentes:\r\n\r\nTarea 1: Tiene pagado Solo Tempo y quiere contratar STEL Order: \r\nEn observaciones sale: la empresa xxxxxx tiene pagado solo tempo y quiere contratar stel order\r\n\r\nTarea 2: Tiene registro en Solo Tempo y ha intentado registrarse en STEL Order. \r\nEn observaciones la empresa xxxxxx tiene registro en solo tempo y ha intentado registrarse en stel order\r\n\r\nTarea 3: Tiene registro en STEL Order sin el módulo tempo y ha intentado registrarse en Solo Tempo. \r\nEn observaciones la empresa xxxx tiene registro en STEL Order sin el módulo tempo y ha intentado registrarse en solo tempo\r\n\r\nIMPORTANTE: La única tarea que se crea porque el cliente ha rellenado el formulario interno de Solo Tempo dentro del Software es la Tarea 1.', '', '', '2026-02-28 23:21:06', 'General', 0, '2026-03-05 18:38:21', NULL, 'general', 0),
(538, NULL, 'NOTA-182143', 'REMESAS', 'NOTA', 'remesas', 'La remesa se cobra SIEMPRE a los 3 días después de la fecha de emisión de la remesa, independientemente de la fecha de vencimiento de los recibos, la fecha de factura, etc. En el .xml que se genera, el parámetro \"ReqdColltnDt\" es el que indica la fecha de cobro de la remesa..', '', '', '2026-02-28 23:21:43', 'General', 0, '2026-03-05 21:16:38', NULL, 'general', 0),
(539, NULL, 'NOTA-182206', 'RECURSOS', 'NOTA', 'VÍAS DE CONTACTO españa', 'Si tiene alguna duda o consulta, no dude en comunicarse con nosotros a través de:\r\n \r\n - Teléfono: 968 39 35 98 / Opción 2 (Soporte) / Horario de lunes a viernes de 09:00 a 14:00 y de 16:00 a 19:00.\r\n - Chat: En la parte inferior izquierda de su STEL Order, en el apartado \"Catálogo\", por ejemplo, puede pulsar el botón de CHAT.\r\n - Correo: info@stelorder.com', '', '', '2026-02-28 23:22:06', 'General', 0, '2026-03-05 18:29:53', NULL, 'general', 0),
(541, NULL, '52365', 'Fernando', '+34655862494', 'La imagen del producto se visualiza en la segunda página', 'Desmarcar  \"Lineas compactas\" en la plantilla del documento.', '', '', '2026-02-28 23:26:30', 'General', 0, '2026-02-28 23:42:02', NULL, 'general', 0),
(564, NULL, 'NOTA-101433', 'RECURSOS', 'NOTA', 'gastos aduaneros', 'En la factura tiene ponerlo por ejemplo en un capitulo o en el campo observaciones\r\nY si quiere registrarlo, lo puede hacer en tickets y otros gastos como un gasto de empresa (en el caso que esos gastos aduaneros que entiendo que si los pague el emisor de la factura).', '', '', '2026-03-02 15:14:33', 'General', 0, '2026-03-05 21:16:38', NULL, 'general', 0),
(568, NULL, 'NOTA-030840', 'BAJAS', 'NOTA', 'funcionamiento bajas extendida', 'https://stelorder.atlassian.net/wiki/external/OTE4OTM4ODU1YzgxNGFjNmI0NTRlNWIzMTRhMDdlN2E', '', '', '2026-03-03 08:08:39', 'General', 0, '2026-03-03 22:08:45', NULL, 'general', 0),
(569, NULL, 'NOTA-091117', 'RECURSOS', 'NOTA', 'taller de los martes', 'Enlace de la llamada por ZOOM: https://zoom.us/signin#/login\r\n\r\nCorreo: marketing@stelorder.com\r\nContraseña: Modulo20Modulo21\r\n\r\nEnlace para ver los que se han registrado al taller y rellenar la info: \r\n\r\nhttps://docs.google.com/spreadsheets/d/1Dmplw4G2-fS_2xPaXGks3bjngZDo0mkUpt5ZX-PYBmA/edit?gid=1972797494#gid=1972797494.\r\n\r\nCuenta profesor: profesor@stelorder.com\r\nContraseña: Profesor_25\r\n\r\nEnlace usuarios:   https://zoom.us/j/95495564848\r\n\r\nOcultar marcadores: Ctrl+shift+B', '', '', '2026-03-03 14:11:17', 'General', 0, '2026-05-14 14:22:39', '2026-05-05 13:57:44', 'general', 1),
(579, NULL, 'NOTA-151150', 'BANCOS', 'NOTA', 'PRUEBA Y CONFLUENCE', 'https://stelorder.atlassian.net/wiki/external/MmNjYjE2YzBkYjIxNGQzN2I2YTE4NDA2YmM3OTU1OGY\r\n\r\nCuenta de prueba\r\nsoporte.bancos@stelorder.com\r\nContraseña; 12345678', '', '', '2026-03-03 20:11:50', 'General', 0, '2026-03-03 22:08:45', NULL, 'general', 0),
(580, NULL, 'NOTA-151227', 'BANCOS', 'NOTA', 'Conciliar devoluciones de remesa  		', 'De momento no es posible, debe primero sacar el recibo de la remesa (si esta conciliado eliminar el recibo de la conciliación) para que luego pueda volver a incluir ese recibo en la remesa. Si tiene varios recibos conciliados en un solo movimiento, sólo se mostrarán 4 registros, no se pueden mostrar más, debe ir eliminando uno a uno. 		', '', '', '2026-03-03 20:12:27', 'General', 0, '2026-03-03 20:12:27', NULL, 'general', 0),
(581, NULL, 'NOTA-151254', 'BANCOS', 'NOTA', 'Conciliar devoluciones de facturas de proveedor		', 'Si la devolución del proveedor es en un sólo movimiendo en su banco lo normal es que sea un movimiento positiivo (pues esta recibiendo el cliente dinero de regreso) por lo tanto podrá conciliarlo con una factura en negativo. No se podrá conciliar si en un mismo movimineto bancario tiene movimientos en positivo y negativo.		', '', '', '2026-03-03 20:12:53', 'General', 0, '2026-03-03 20:12:53', NULL, 'general', 0),
(583, NULL, 'NOTA-151348', 'BANCOS', 'NOTA', 'Cómo editar recibos que esten dentro de un movimiento conciliado ', 'Deben entrar en bancos buscar el movimiento desvincularlo, editar el recibo y volver a conciliar. \r\n', '', '', '2026-03-03 20:13:47', 'General', 0, '2026-03-03 20:13:47', NULL, 'general', 0),
(584, NULL, 'NOTA-151531', 'BANCOS', 'NOTA', 'No se muestra la cuenta que quiere vincular, puede ser por tres opciones: ', '-Se esta equivocando de cuenta de acceso, solucion, que entre con la cuent acorrecta\r\n-Es apoderado de la cuenta que quiere ver, solucion, que el usuario que es dueño de la cuenta de el acceso al banco\r\n-El banco no tiene permiso para compartir esa cuenta, hablar con el banco para ver porque esa cuenta no aparece.', '', '', '2026-03-03 20:15:31', 'General', 0, '2026-03-03 20:15:31', NULL, 'general', 0),
(585, NULL, 'NOTA-152146', 'TICKET BAI', 'NOTA', 'STG TICKET BAI', 'GUIPUZKOA\r\nCuenta: juanjo.miranda+espv@stelsolutions.com\r\nContraseña: a\r\n\r\nÁLABA				\r\nCuenta: juanjo.miranda+espv2@stelsolutions.com				\r\nContraseña: a	\r\n			\r\nEs persona física o autónomo (por eso de lo del IAE que tienen en conf. de empresa) 				\r\nBIZKAIA				\r\nCuenta: juanjo.miranda+espv3@stelsolutions.com				\r\nContraseña: a		\r\n		\r\n empresa o persona jurídica (que no tienen lo del IAE en conf. empresa)				\r\nBIZKAIA				\r\nCuenta: juanjo.miranda+espv4@stelsolutions.com				\r\nContraseña: a				', '', '', '2026-03-03 20:21:45', 'General', 0, '2026-03-11 17:05:27', '2026-03-11 18:05:27', 'general', 0),
(586, NULL, 'NOTA-152340', 'TICKET BAI', 'NOTA', 'Todo ticket BAI que se emita fuera de españa, pero en país de la UE					', 'Tienen que poner en el NIF del cliente el código de país por delante por ejemplo si es Portugal, \"PT458989872\".', '', '', '2026-03-03 20:23:40', 'General', 0, '2026-03-03 20:23:40', NULL, 'general', 0),
(587, NULL, 'NOTA-152413', 'TICKET BAI', 'NOTA', '¿Cuando se muestra el campo \"Epígrafe IAE\"?			', 'Solamente se mostrará este campo (Configuración> Datos empresa > TicketBAI) cuando la empresa tiene registrado en el campo NIF, un NIF de autónomo o persona física.										\r\nPara NIF de persona jurídica no se muestra este campo disponible.										', '', '', '2026-03-03 20:24:12', 'General', 0, '2026-03-03 20:24:12', NULL, 'general', 0),
(588, NULL, 'NOTA-152505', 'TICKET BAI', 'NOTA', 'REGISTRO DISPOSITIVO EN HACIENDA FORAL GIPUZKOA				', 'Para registrar el dispositivo desde el que se emite el Ticketbai, el cliente debe de acceder a este enlace y aceptar permisos.									\r\nhttps://www.gipuzkoa.eus/es/web/ogasuna/ticketbai/dispositivos									\r\nDeben de hacer click en el Tick Verde que les muestra, ya que para que se quede guardada la configuración del registro, tiene que pulsar ese tick verde.									', '', '', '2026-03-03 20:25:04', 'General', 0, '2026-03-03 20:25:04', NULL, 'general', 0),
(589, NULL, 'NOTA-152533', 'TICKET BAI', 'NOTA', 'REGISTRO DISPOSITIVO EN HACIENDA FORAL BIZKAIA				', 'Para registrar el dispositivo desde el que se emite el Ticketbai, el cliente debe de acceder a este enlace y aceptar permisos.							\r\nhttps://ataria.ebizkaia.eus/es/catalogo-de-tramites-y-servicios?procID=1740', '', '', '2026-03-03 20:25:33', 'General', 0, '2026-03-03 22:08:45', NULL, 'general', 0),
(590, NULL, 'NOTA-152624', 'TICKET BAI', 'NOTA', 'FECHAS A TENER EN CUENTA A LA HORA DE EMITIR UNA FACTURA', 'FechaExpedicionFactura -->Fecha en la que se emite -->se timbra-->se registra en hacienda						\r\nFechaOperacion-->La fecha de la factura						\r\n						\r\nHacienda toma cómo referencia la fechaExpediciónFactura, porque es la fecha en la que se emite.						', '', '', '2026-03-03 20:26:23', 'General', 0, '2026-03-03 20:26:23', NULL, 'general', 0),
(591, NULL, 'NOTA-152823', 'TICKET BAI', 'NOTA', 'Servicio Zuzendu	', 'Sólo se puede usar cuando hacienda detecta un error en los datos de facturación, se mostrará el botón para corregir y podrá el cliente corregir, nunca va a ser utilizado para corregir importes. 									', '', '', '2026-03-03 20:28:22', 'General', 0, '2026-03-03 20:28:22', NULL, 'general', 0),
(592, NULL, 'NOTA-152910', 'TICKET BAI', 'NOTA', 'Certificado digital ', 'No tiene duración mayor a 4 años. 				', '', '', '2026-03-03 20:29:09', 'General', 0, '2026-03-03 20:29:09', NULL, 'general', 0),
(593, NULL, 'NOTA-153033', 'VERIFACTU', 'NOTA', 'Cuentas', '\"Si Verifactu\":       soporte.stelorder.verifactu@gmail.com		\r\n			\r\n\"No Verifactu\":	soporte.stelorder.noverifactu@gmail.com		\r\n			\r\nContraseña: 12345678', '', '', '2026-03-03 20:30:33', 'General', 0, '2026-03-05 21:16:38', NULL, 'general', 0),
(594, NULL, 'NOTA-153155', 'PARTNER', 'NOTA', 'ENLACE PARA REGISTRARSE CÓMO PARTNER			', 'https://www.stelorder.com/hazte-partner/', '', '', '2026-03-03 20:31:54', 'General', 0, '2026-03-03 22:08:45', NULL, 'general', 0),
(595, NULL, 'NOTA-153221', 'PARTNER', 'NOTA', 'FECHA PAGO FACTURA COMISIONES POR PARTE DEL PARTNER				', 'Cuando el partner envía la factura, es cuando comienza a contar el periodo hasta 2 meses, que será cuando Alberto haga el pago.									\r\nNo se tiene en cuenta la fecha de la factura generada, sino que es cuando el partner la envía a pagos@.', '', '', '2026-03-03 20:32:20', 'General', 0, '2026-03-03 20:32:20', NULL, 'general', 0),
(596, NULL, 'NOTA-153617', 'API', 'NOTA', 'API URL', 'API PRODUCCIÓN			\r\nhttps://app.stelorder.com/app/indexapi.html\r\n\r\nAPI STG			\r\nhttps://app-stg.stelorder.com/app/indexapi.html	', '', '', '2026-03-03 20:36:17', 'General', 0, '2026-03-09 17:38:49', '2026-03-09 18:38:49', 'general', 0),
(606, NULL, '44158 ', 'Joaquín Balaguer', '618780116', 'Indica lentitud en web. Ticket 146129', 'Extensión Bitwarden', '', 'https://stelorder.atlassian.net/browse/SUP-631?focusedCommentId=44068&sourceType=mention', '2026-03-04 08:35:01', 'General', 0, '2026-03-31 16:24:35', '2026-03-31 17:24:35', 'general', 0),
(607, NULL, 'NOTA-042544', 'RECURSOS', 'NOTA', 'Procedimiento Tareas Fase 0 y Fase 1', 'https://docs.google.com/document/d/1bvzx58tAQMWlR9h1XAuLGHMSbFvQlt2z5LuDn7Dv3PE/edit?tab=t.0', '', '', '2026-03-04 09:25:44', 'General', 0, '2026-05-14 14:22:30', NULL, 'general', 1),
(608, NULL, 'NOTA-061118', 'RECURSOS', 'NOTA', 'Contenido confluence', 'https://stelorder.atlassian.net/wiki/spaces/SO/overview?homepageId=131529', '', '', '2026-03-04 11:11:17', 'General', 0, '2026-03-05 18:29:53', NULL, 'general', 0),
(609, NULL, '103643', 'Magda', '602591247', 'Revisar Ticket Bai cancelado', 'Debe acceder a su hacienda foral para poder ver el estado de la factura\r\n\r\nBIZKAIA\r\nhttps://ataria.ebizkaia.eus/es/catalogo-de-tramites-y-servicios?procID=1740\r\n\r\nGIPUZKOA\r\nhttps://www.gipuzkoa.eus/es/web/ogasuna/ticketbai/dispositivos\r\n\r\n\r\n\r\n', '', '', '2026-03-04 11:27:09', 'General', 0, '2026-03-04 20:56:37', NULL, 'general', 0),
(611, NULL, 'NOTA-100906', 'RECURSOS', 'NOTA', 'CONTACTO MÉXICO', 'Con gusto nos puede contactar al +52 55 85 26 64 77', '', '', '2026-03-04 15:09:06', 'General', 0, '2026-03-05 21:18:23', NULL, 'general', 0),
(612, NULL, 'NOTA-100925', 'RECURSOS', 'NOTA', 'CONTACTO CHILE', 'Con gusto nos puede contactar al +56 23 21 08 236', '', '', '2026-03-04 15:09:25', 'General', 0, '2026-03-05 21:18:23', NULL, 'general', 0),
(613, NULL, 'NOTA-103022', 'BAJAS', 'NOTA', 'Confluence BAJAS', 'https://stelorder.atlassian.net/wiki/external/NDVkZGUwNjAzNWUzNDk4MWI2ZDVhZDBmYmZlMTc0ZjE', '', '', '2026-03-04 15:30:23', 'General', 0, '2026-03-04 20:56:37', NULL, 'general', 0),
(614, NULL, 'NOTA-103120', 'BAJAS', 'NOTA', 'Tareas departamento de bajas', 'https://docs.google.com/spreadsheets/d/19_dTXPm_tXtFb4INllWae9Wdk-beEvnvTI2iIjExqYE/edit?gid=0#gid=0', '', '', '2026-03-04 15:31:20', 'General', 0, '2026-03-04 20:56:37', NULL, 'general', 0),
(617, NULL, 'NOTA-055036', 'RECURSOS', 'NOTA', 'VALIDADOR FACTURA-E', 'https://proveedores.face.gob.es/proveedores/validar-factura', '', '', '2026-03-05 10:50:36', 'General', 0, '2026-03-05 18:29:53', NULL, 'general', 0),
(618, NULL, 'NOTA-055528', 'RECURSOS', 'NOTA', 'PRIVACIDAD STEL ORDER', 'https://www.stelorder.com/politica-de-privacidad-stel-order/', '', '', '2026-03-05 10:55:28', 'General', 0, '2026-03-05 18:29:53', NULL, 'general', 0),
(619, NULL, 'NOTA-055616', 'RECURSOS', 'NOTA', 'ASIGNACIONES', 'https://docs.google.com/spreadsheets/d/1-Ai161LGielFbCBiO0O3x72FSMmYRWa0jhYTY1kE5QQ/edit?gid=0#gid=0', '', '', '2026-03-05 10:56:16', 'General', 0, '2026-03-05 18:29:53', NULL, 'general', 0),
(620, NULL, 'NOTA-055809', 'RECURSOS', 'NOTA', 'VACACIONES SOPORTE', 'https://docs.google.com/spreadsheets/d/13iFRdx4NgUem5ovfxCZaFtxcflEmqGZpUZ2d_H5XZus/edit?gid=167344266#gid=167344266', '', '', '2026-03-05 10:58:09', 'General', 0, '2026-05-14 14:22:20', NULL, 'general', 1),
(621, NULL, 'NOTA-055910', 'RECURSOS', 'NOTA', 'TARDES SOPORTE', 'https://docs.google.com/spreadsheets/d/1BZLBOOjsxoquvEiPnGBrcRZbzAE94jR-1CbTr_fWToA/edit?gid=2043239317#gid=2043239317', '', '', '2026-03-05 10:59:09', 'General', 0, '2026-05-14 14:22:16', NULL, 'general', 1),
(622, NULL, 'NOTA-060216', 'RECURSOS', 'NOTA', 'CODIGO DE BARRAS', 'https://barcode.tec-it.com/es', '', '', '2026-03-05 11:02:16', 'General', 0, '2026-03-05 11:02:16', NULL, 'general', 0),
(623, NULL, 'NOTA-060323', 'RECURSOS', 'NOTA', 'VACACIONES VENTAS', 'https://docs.google.com/spreadsheets/d/1B7I-NPdEgKApjYnV5jbP20NRLvxVXLCojVTVLFodF5U/edit?gid=0#gid=0', '', '', '2026-03-05 11:03:22', 'General', 0, '2026-03-05 11:03:22', NULL, 'general', 0),
(624, NULL, '109523', 'Soledad', '691430025', 'Al pulsar un evento de la agenda de incidencias no abre el evento', 'Corregido en producción', '', 'https://stelorder.atlassian.net/browse/SUP-634?atlOrigin=eyJpIjoiMGQyZGJiZWU4MDNkNGI2OGIyYjg3MTU1YjYxYzcxNWYiLCJwIjoiaiJ9', '2026-03-05 11:27:02', 'General', 0, '2026-03-06 09:05:43', '2026-03-06 10:05:43', 'general', 0),
(625, NULL, 'NOTA-113510', 'RECURSOS', 'NOTA', 'STEL ASSISTANT', 'https://stelorder.atlassian.net/wiki/spaces/SO/pages/470712321/Qu+puede+hacer+y+qu+no+puede+hacer+STEL+Assistant+-+Una+gu+a+r+pida\r\n\r\n', '', '', '2026-03-05 16:35:10', 'General', 0, '2026-05-14 15:09:39', '2026-05-14 16:09:39', 'general', 0),
(626, NULL, '84877', 'Raul', '+50589943116', 'Factura en papelera de reciclaje con numeración \"Pendiente\"', '', '', 'https://stelorder.atlassian.net/browse/SUP-234?search_id=fa983edd-e555-41f0-8d9b-9301f231362e&referrer=quick-find', '2026-03-05 17:10:15', 'General', 0, '2026-03-05 17:10:15', NULL, 'general', 0),
(654, NULL, 'NOTA-044657', 'STEL ASSISTANT', 'NOTA', 'Sesión Celia grabada', 'https://zoom.us/clips/share/Axpwfci7SEmZCGZrP5_dfw', '', '', '2026-03-06 09:46:56', 'General', 0, '2026-03-06 09:48:34', '2026-03-06 10:48:34', 'general', 0),
(657, NULL, 'NOTA-095803', 'RECURSOS', 'NOTA', 'SEGUIMIENTO PARTNERS', 'https://docs.google.com/spreadsheets/d/1a2isfkXmtAfTTWMnxc6QI3PgtWU16xQStDrTsP6amTw/edit?gid=0#gid=0', '', '', '2026-03-09 13:58:04', 'General', 0, '2026-03-09 13:58:04', NULL, 'general', 0),
(658, NULL, 'NOTA-113422', 'RECURSOS', 'NOTA', 'VIDEOS', 'https://docs.google.com/spreadsheets/d/1nLmYu2poXcKIqQqGil_2czQPR8BJmAeW7r_7DOYdztU/edit?gid=0#gid=0', '', '', '2026-03-09 15:34:22', 'General', 0, '2026-03-09 15:34:22', NULL, 'general', 0),
(659, NULL, '101217', 'Sebastián ', '659536784', 'ID 101217 Problema acceso API, error API KEY', 'El cliente está usando una APIKEY incorrecta o indicándola en un entorno incorrecto. El error que nos daba a nosotros era porque estábamos entrando desde STG no desde producción.', '', 'https://stelorder.atlassian.net/browse/SUP-639', '2026-03-09 17:00:54', 'General', 0, '2026-03-09 18:30:16', '2026-03-09 19:30:16', 'general', 0),
(660, NULL, 'NOTA-133147', 'RECURSOS', 'NOTA', 'TALLERES', 'https://www.stelorder.com/talleres/', '', '', '2026-03-09 17:31:46', 'General', 0, '2026-03-09 17:32:03', '2026-03-09 18:32:03', 'general', 0),
(661, NULL, 'NOTA-133745', 'RECURSOS', 'NOTA', 'Grupos de trabajo', 'https://docs.google.com/spreadsheets/d/1F_HqrBnQdvInpHECoD8-JBn439YKTS60TKoU5GPSvps/edit?gid=0#gid=0', '', '', '2026-03-10 17:37:45', 'General', 0, '2026-03-10 17:37:45', NULL, 'general', 0),
(662, NULL, 'NOTA-102718', 'LATAM', 'NOTA', 'NOMENCLATURAS DOCUMENTOS LATAM - ESPAÑA', '', '1773239238_0_image (12).png', '', '2026-03-11 14:27:19', 'General', 0, '2026-03-12 15:14:23', '2026-03-12 16:14:23', 'general', 0),
(663, NULL, 'NOTA-130434', 'LATAM', 'NOTA', 'STG LATAM', 'Cuenta de prueba de México y Chile, siempre en STG \r\njuanjo.miranda+mex@stelsolutions.com\r\na\r\njuanjo.miranda+chl@stelsolutions.com\r\na', '', '', '2026-03-11 17:04:33', 'General', 0, '2026-03-12 15:14:12', '2026-03-12 16:14:12', 'general', 0),
(664, NULL, '116084', 'Ivan', '647443093', 'ERROR API AL CREAR FACTURA VERIFACTU', '[{\"error\": \"Invalid item name in an ITEM type document line\", \"description\": \"The item name value is null, empty or has invalid length\", \"error-code\": \"E000161\"}]\r\n\r\nNombre de línea muy largo, en Verifactu solo permite 128 caracteres y el usuario está introduciendo 132.\r\n', '1773254530_0_sgbO4woZpta3nP50VbwZv9rn7EL14o8QYg.png', '', '2026-03-11 18:42:10', 'General', 0, '2026-05-14 14:21:46', '2026-03-11 20:12:07', 'general', 0),
(665, NULL, 'NOTA-104550', 'BAJAS', 'NOTA', 'ASIGNACIONES BAJAS', 'https://docs.google.com/spreadsheets/d/15zV6-ItExlq7yu064gO27TmRFno-GnzibrbtUmx5dkI/edit?gid=0#gid=0', '', '', '2026-03-12 14:45:50', 'General', 0, '2026-03-12 14:45:50', NULL, 'general', 0),
(666, NULL, 'NOTA-110851', 'LATAM', 'NOTA', 'FORMACIÓN CHILE', 'https://drive.google.com/drive/folders/1jTu1mQ09E3fkozZv-3u5JqfjnKYA4J8I', '', '', '2026-03-12 15:08:51', 'General', 0, '2026-03-12 15:08:51', NULL, 'general', 0),
(667, NULL, 'NOTA-160802', 'INTEGRACIONES', 'NOTA', 'WOOCOMMERCE PLUGIN INSTALACIÓN', 'https://drive.google.com/file/d/1P9cvT4NB0h-mhj0FoneW_NgK-cA1Ew0a/view', '', '', '2026-03-12 20:08:02', 'General', 0, '2026-05-14 15:39:48', '2026-05-14 16:39:37', 'general', 1),
(668, NULL, 'NOTA-171731', 'RECURSOS', 'NOTA', 'TIPS Y CONSEJOS', 'https://docs.google.com/spreadsheets/d/1j7LQDDLgEYzNMyZWLhBIjSF2887n1riPGXHM8DbVxts/edit?usp=drive_web&ouid=101147809742764075163', '', '', '2026-03-12 21:17:31', 'General', 0, '2026-05-14 14:15:48', NULL, 'general', 1),
(669, NULL, 'NOTA-100111', 'API', 'NOTA', 'CONFLUENCE API', 'https://stelorder.atlassian.net/wiki/home', '', '', '2026-03-13 14:01:11', 'General', 0, '2026-03-15 16:28:15', '2026-03-15 17:28:15', 'general', 0),
(672, NULL, 'NOTA-161605', 'BAJAS', 'NOTA', 'PRocedimiento bajas', 'https://docs.google.com/document/d/1dfAE86yVFAWw2wQRKRWxXUleqzL84YkEXEHWUfPyO0c/edit?tab=t.0', '', '', '2026-03-30 20:16:06', 'General', 0, '2026-03-30 20:16:06', NULL, 'general', 0),
(673, NULL, 'NOTA-124830', 'RECURSOS', 'NOTA', 'TICKET REGALO', 'Puede diseñar una plantilla, tipo presupuesto, donde usted diseñe la información que quiere que aparezca y a la hora de imprimirlo, debe de indicar en el campo Formato: Ticket y en el campo Plantilla, la que usted ha creado.\r\n\r\nPara crear plantillas acceda a Configuración>Mi Empresa>Mis Plantillas.\r\n\r\nPuede crear referencias para tener controlados los tickets regalos, desde Configuración>Configuración Básica>Referencias.\r\nPara la configuración de esa nueva plantilla destinada a los Tickets regalo, indique:\r\nEn el campo Datos documento > tipo del documento puede poner Ticket regalo\r\nY en Título de sección también Ticket regalo.\r\nEn el campo de Líneas desmarque Col. Descuento, Col. Precio unidad y Col. Subtotal.-En el campo de Vencimientos desmarque la casilla vencimientos.', '1774979858_0_1.png.png,1774979866_0_2.png.png,1774979872_0_3.png.png,1774979881_0_4.png.png', '', '2026-03-31 16:48:30', 'General', 0, '2026-04-01 12:50:00', '2026-04-01 13:50:00', 'general', 0),
(674, NULL, '78227', 'Maite', '943172412', '001- Certificado remitente debe estar homologado, no revocado y no caducado.', 'Solucionado al eliminar y volver a subir el certificado. Igualmente parece ser un problema de la diputación.', '', 'https://stelorder.atlassian.net/browse/SUP-673?atlOrigin=eyJpIjoiODA3ZTVjZjFmYjNlNDljNDgyOGQ5ZGZkM2EyYjJjMWQiLCJwIjoiaiJ9', '2026-04-08 10:00:08', 'General', 0, '2026-04-08 11:47:21', '2026-04-08 12:47:21', 'general', 0),
(675, NULL, '113107  ', 'Jaume', '683315429', 'No puede escribir manualmente la numeración de factura generada a partir de estado de albarán', 'Según pruebas en STG, cuando se crea un documento:\r\n\r\nEl bug provenía de los estados configurados, en concreto, el segundo tipo de estado registrado.\r\n\r\nSe indica en el campo Estado, el segundo estado configurado y se genera una factura, permitiendo editar el campo Referencia de factura.check mark button \r\n\r\nSe configura un nuevo Estado y se posiciona en segundo lugar, al indicarlo en un documento y generar una factura permite editar el campo Referencia de factura.check mark button \r\n\r\nNota: Se prueba en todos los documentos comerciales con el segundo estado de la configuración y se genera la factura correctamente.', '', 'https://stelorder.atlassian.net/browse/SUP-678', '2026-04-09 08:54:27', 'General', 0, '2026-05-14 14:32:42', '2026-04-23 08:34:05', 'general', 0),
(676, NULL, 'NOTA-102448', 'LATAM', 'NOTA', 'PREFIJOS LATAM', 'CHILE: 56 (9) 3165 6494 añadirle un 9 entre el prefijo y el teléfono / si es fijo + 54 9 si es fijo y si es móvil + 54 9 11\r\nMÉXICO: 52 (1) 8413213351 añadirle un 1 entre el prefijo y el teléfono\r\nVENEZUELA: 58 (0)4125616546 Otros números, quitar el 0 entre el prefijo y el número\r\nARGENTINA: 54 si es fijo + 54 9 y si es móvil + 54 9 11\r\nCOLOMBIA: 57\r\nPERÚ: 51', '', '', '2026-04-14 14:24:48', 'General', 0, '2026-04-14 14:24:48', NULL, 'general', 0),
(677, NULL, 'NOTA-104436', 'RECURSOS', 'NOTA', 'PROCEDIMIENTO FELICIDAD', 'Procedimiento actualizado, fidelización y bajas.', '1776177913_0_Gestión_de_tareas_fidelización_y_bajas.pdf', '', '2026-04-14 14:44:36', 'General', 0, '2026-05-14 14:15:41', '2026-04-14 15:45:13', 'general', 1),
(678, NULL, 'NOTA-111443', 'RECURSOS', 'NOTA', 'VIDEO CARA JAULA', 'https://www.youtube.com/watch?v=hfmMbBcOYRs', '', '', '2026-04-14 15:14:43', 'General', 0, '2026-05-14 14:05:06', NULL, 'general', 1),
(679, NULL, 'NOTA-112625', 'RECURSOS', 'NOTA', 'VIDEOS AYUDA', 'https://www.youtube.com/@STELOrderAyuda', '', '', '2026-04-14 15:26:25', 'General', 0, '2026-05-14 11:41:25', NULL, 'general', 0),
(680, NULL, 'NOTA-073320', 'ASSISTANT', 'NOTA', 'PRECIOS ASSISTANT', '', '1776339200_0_image (13).png,1776339207_0_image_(14).png', '', '2026-04-16 11:33:20', 'General', 0, '2026-05-14 12:25:01', '2026-04-16 12:33:27', 'general', 0),
(681, NULL, 'NOTA-052235', 'RECURSOS', 'NOTA', 'integraciones', 'https://www.stelorder.com/integraciones/', '', '', '2026-04-24 09:22:35', 'General', 0, '2026-05-14 12:24:56', NULL, 'general', 0),
(682, NULL, 'NOTA-052258', 'RECURSOS', 'NOTA', 'tpv', 'https://www.stelorder.com/tpv/\r\n\r\nhttps://stelorder.atlassian.net/wiki/spaces/SO/pages/468484098/Manual+de+usuario', '', '', '2026-04-24 09:22:58', 'General', 0, '2026-05-14 22:42:51', '2026-05-14 14:44:17', 'general', 0),
(683, NULL, 'NOTA-075909', 'RECURSOS', 'NOTA', 'DESCARGA RESUMEN', 'https://stelorder.atlassian.net/wiki/spaces/SO/pages/579993601/Informaci+n+y+Testeo+Descarga+Resumen', '', '', '2026-04-28 11:59:09', 'General', 0, '2026-05-14 13:19:58', NULL, 'general', 0),
(684, NULL, 'NOTA-052821', 'RECURSOS', 'NOTA', 'PUESTA EN MARCHA CANCELADA', 'Procedimiento -PUESTA EN MARCHA CANCELADA (EL CLIENTE NO SE PRESENTA)  \r\n\r\n\r\nLlamar para intentar localizarlo, por si tiene algún problema para conectarse a meet, o le ha surgido algún imprevisto.\r\n\r\nEn caso de que no lo localicemos: Dejamos nota en la tarea de lo sucedido, y reenviamos el correo del enlace indicando( algo así):   “Teníamos agendado para el día de hoy una sesión a las xx:xxh y no ha sido posible localizarle para realizarla. Quedamos pendiente de que nos indique, si desea agendar de nuevo la puesta en marcha\".\r\n\r\nAvisar al comercial de lo ocurrido para que le escriba y esté al tanto.\r\n\r\nCrear en  panel una nueva tarea de forma manual a los 3 días para volver a contactar al cliente.  En el asunto de la tarea indicar: “ SEGUIMIENTO PUESTA EN MARCHA CANCELADA”', '', '', '2026-05-05 09:28:21', 'General', 0, '2026-05-14 20:27:43', NULL, 'general', 0),
(685, NULL, '75606', 'Claudia', '951080355', 'Error 503 Bancos', '', '', 'https://stelorder.atlassian.net/browse/SUP-577?search_id=45b9249e-7ea6-4931-ae20-dc76786eb2c8', '2026-05-08 07:22:18', 'General', 0, '2026-05-14 14:51:19', NULL, 'general', 1),
(686, NULL, 'NOTA-104742', 'BANCOS', 'NOTA', 'no le deja renovar permisos, solo le aparece el botón de Volver', 'Al intentar renovar permisos ha intentado vincular otra cuenta diferente. Debe esperar a que pase el mes o consultar a Carol/Fran para ponerle un slot nuevo y dar de baja el anterior.\r\n\r\nhttps://stelorder.atlassian.net/wiki/spaces/SO/pages/12615693/Funcionalidad+Bancos#%C2%BFPorqu%C3%A9-muestra-bot%C3%B3n-%E2%80%9CVolver%E2%80%9D-al-renovar-permisos-en-una-cuenta-bancaria%3F', '1778510862_0_image (15).png', '', '2026-05-11 14:47:41', 'General', 0, '2026-05-14 22:54:19', '2026-05-11 15:48:16', 'general', 0),
(687, NULL, 'NOTA-163012', 'RECURSOS', 'NOTA', 'TAREAS SEGÚN PLANES', 'https://docs.google.com/spreadsheets/d/19WN6Sq3x5aQ53dk5uBxG5Iwcc-g0PPLwFfJ2_iBwOpg/edit?gid=0#gid=0', '', '', '2026-05-11 20:30:12', 'General', 0, '2026-05-14 14:15:29', NULL, 'general', 1),
(688, NULL, 'NOTA-101609', 'RECURSOS', 'NOTA', 'CONFIGURAR DTE', 'https://help.stelorder.com/hc/es/articles/7063369133341-Configura-tu-empresa-para-emitir-DTEs-con-STEL-Order', '', '', '2026-05-12 14:16:10', 'General', 0, '2026-05-14 23:24:47', '2026-05-12 15:16:28', 'general', 0),
(712, NULL, 'NOTA-110959', 'RECURSOS', 'NOTA', 'STEL IA', 'https://stelorder.atlassian.net/wiki/external/YjQxZjJmYzE0NzMyNDFiMTk2YmUyNzFhNjhlNjYwMjk', '', '', '2026-05-14 15:09:58', 'General', 0, '2026-05-14 15:09:58', NULL, 'general', 0),
(713, NULL, 'NOTA-113919', 'RECURSOS', 'NOTA', 'WOOCOMMERCE', 'https://www.stelorder.com/integraciones/woocommerce/', '', '', '2026-05-14 15:39:19', 'General', 0, '2026-05-14 15:39:22', NULL, 'general', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) DEFAULT 'user',
  `color_fondo` varchar(255) DEFAULT NULL,
  `color_tarjeta` varchar(7) DEFAULT '#ffffff',
  `foto_perfil` varchar(255) DEFAULT 'default.png',
  `font_size` varchar(10) DEFAULT 'medium',
  `color_botones` varchar(7) DEFAULT '#475569',
  `primer_acceso` tinyint(1) DEFAULT 0,
  `ultimo_entorno` varchar(50) NOT NULL DEFAULT 'general',
  `color_filas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`, `color_fondo`, `color_tarjeta`, `foto_perfil`, `font_size`, `color_botones`, `primer_acceso`, `ultimo_entorno`, `color_filas`) VALUES
(1, 'admin', '$2y$10$40Xksp/vkKTOdIu6A/3KX.svGr06VnFYnCi7B5dtLoKUekssbxHZy', 'admin', 'linear-', '#ffffff', 'perfil_admin_1778609486.jpeg', 'medium', '#475569', 1, 'general', 'rgba(255,255,255,0.0'),
(51, 'Gafúo', '$2y$10$4qaiZKmLriIzBUebHn5bcenPvt8IO2.Pz1HCLFYr3ptkbiDHIcyia', 'user', '#74e4fb', '#ffffff', 'perfil_Gafúo_1778739992.jpg', 'medium', '#475569', 1, 'general', '#ffffff'),
(53, 'Antonio', '$2y$10$JY14OSlJPx1NcYpFbWw4ielFk17Dhtoqsy7mzrjyMyRLGBqj7Tthe', 'user', '#f2f52e', '#ffffff', '', 'medium', '#475569', 1, 'general', '#ffffff'),
(54, 'Ali', '$2y$10$PlQWdEZkH8Szk.ByOaquluYYW31xlAHxMTjA8OJJ44CvHVzi6Ot/2', 'user', '#a0d7f3', '#ffffff', 'perfil_Ali_1778741458.png', 'medium', '#475569', 1, 'general', '#ffffff'),
(57, 'Marta', '$2y$10$TTJEw2nCPfW/vTBvZIMv7ujkkPQMvT7V/sc8J0UmvcNuZApXNvpBu', 'user', '#e2e8f0', '#ffffff', '', 'medium', '#475569', 1, 'general', '#ffffff');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `supositorio`
--
ALTER TABLE `supositorio`
  ADD PRIMARY KEY (`id_sistema`),
  ADD KEY `tipo` (`tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `supositorio`
--
ALTER TABLE `supositorio`
  MODIFY `id_sistema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=714;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
