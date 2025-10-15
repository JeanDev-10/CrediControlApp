import './bootstrap';
import './theme';
import Alpine from 'alpinejs';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/es';  // Importa la configuración en español
import Swal from 'sweetalert2';
// Usamos el plugin para los tiempos relativos
dayjs.extend(relativeTime);
dayjs.locale('es');  // Establece el idioma a español

window.Swal=Swal;
window.Alpine = Alpine;
window.dayjs=dayjs;
Alpine.start();


