import iziToast from "izitoast";
import 'izitoast/dist/css/iziToast.min.css';
import i18n from '../i18n';
const t = i18n.global.t;

export function askAffirmation(yesFunction, noFunction = ()=>{}){
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        title: t('toasts.confirmation'),
        message: t('toasts.areYouSure'),
        position: 'center',
        buttons: [
            [`<button>${t('toasts.no')}</button>`, function (instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                noFunction();
            }],
            [`<button><b>${t('toasts.yes')}</b></button>`, function (instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                yesFunction();
            }, true]
        ],
    });
}
