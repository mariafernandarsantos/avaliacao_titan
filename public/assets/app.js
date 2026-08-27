
document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------
    // Confirmação em formulários com data-message
    // ----------------------------------------------------------
    document.querySelectorAll('.js-confirm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var msg = form.dataset.message || 'Confirma esta ação?';
            if (!window.confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // ----------------------------------------------------------
    // Fechar alertas flash ao clicar no "×" e auto-fechar após 5 segundos
    // ----------------------------------------------------------
    document.querySelectorAll('.js-alert').forEach(function (alert) {
        // Botão de fechar manual
        var closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                dismissAlert(alert);
            });
        }

        // Auto-fechar após 5s
        setTimeout(function () {
            dismissAlert(alert);
        }, 5000);
    });

    function dismissAlert(el) {
        el.style.transition = 'opacity .3s ease, max-height .3s ease, padding .3s ease, margin .3s ease';
        el.style.opacity    = '0';
        el.style.maxHeight  = '0';
        el.style.overflow   = 'hidden';
        el.style.padding    = '0';
        el.style.marginBottom = '0';

        setTimeout(function () {
            if (el.parentNode) {
                el.parentNode.removeChild(el);
            }
        }, 320);
    }

    // ----------------------------------------------------------
    // Toggle de visibilidade do campo de senha
    // ----------------------------------------------------------
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.closest('.input-password').querySelector('input');
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.setAttribute('aria-label', 'Ocultar senha');
            } else {
                input.type = 'password';
                btn.setAttribute('aria-label', 'Mostrar senha');
            }
        });
    });

    // ----------------------------------------------------------
    // 4. Máscara de moeda nos inputs com classe .input-money
    //    Permite digitar apenas números e formata em tempo real
    //    O valor enviado ao servidor usa vírgula como separador
    // ----------------------------------------------------------
    document.querySelectorAll('.input-money').forEach(function (input) {
        // Ao focar, remove a formatação para facilitar edição
        input.addEventListener('focus', function () {
            var raw = input.value.replace(/\./g, '').replace(',', '.');
            var num = parseFloat(raw);
            input.value = isNaN(num) ? '' : num.toString().replace('.', ',');
            input.select();
        });

        // Ao digitar, aceita apenas números e vírgula/ponto
        input.addEventListener('input', function () {
            var cursor = input.selectionStart;
            var val    = input.value.replace(/[^\d,]/g, '');

            // Só uma vírgula permitida
            var parts = val.split(',');
            if (parts.length > 2) {
                val = parts[0] + ',' + parts.slice(1).join('');
            }

            input.value = val;
            // Restaura a posição do cursor
            input.setSelectionRange(cursor, cursor);
        });

        // Ao sair do foco, formata com duas casas decimais
        input.addEventListener('blur', function () {
            var raw = input.value.replace(/\./g, '').replace(',', '.');
            var num = parseFloat(raw);
            if (!isNaN(num)) {
                input.value = num.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });
    });

});