import './bootstrap';
import 'sweetalert2/dist/sweetalert2.css';
import Swal from 'sweetalert2';

// Делаем Swal доступным глобально
window.Swal = Swal;

// Создаем Toast уведомление
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: 'rgba(255, 255, 255, 0.95)',
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
window.Toast = Toast;

// Глобальный обработчик уведомлений
document.addEventListener('livewire:initialized', () => {
    Livewire.on('showToast', (data) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: data[0].type,
            title: data[0].message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
});
