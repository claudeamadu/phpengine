function importScripts(file) {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = file;
    head.appendChild(script);
    script.onload = function () {

    };
}
var Toast = Swal.mixin({
    toast: false,
    position: 'center',
    showConfirmButton: false,
    timer: 3000
});

function SwalAlert(ico, t, m) {
    Swal.fire({
        icon: ico,
        title: t,
        text: m
    });
}
function SwalError(page, footer) {
    if (footer) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
            footer: '<a target="_blank" href="' + rootURL + 'docs/' + page + '">Why do I have this issue?</a>'
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
        });

    }
}

function SwalHelp(icon, title, message, helpurl, helptext) {
    if (helpurl !== '' && helptext !== '') {
        Swal.fire({
            icon: icon,
            title: title,
            text: message,
            footer: '<a target="_blank" href="' + helpurl + '">' + helptext + '</a>'
        });
    } else {
        Swal.fire({
            icon: icon,
            title: title,
            text: message,
        });

    }
}

function goHist(a) {
    history.go(a);      // Go back one.
}


//Payment
function paymentInit(email, amount, ref, label, currency, redirect) {
    let handler = PaystackPop.setup({
        key: 'pk_live_xxxxxxxxxxxxxxxxxxxxx',
        email: email,
        amount: amount * 100,
        ref: ref + Math.floor((Math.random() * 1000000000) + 1),
        label: label,
        currency: currency,
        onClose: function () {
            Toast.fire({ title: 'Payment Cancelled', icon: 'success' });
        },
        callback: function (response) {
            console.log(response);
            if (redirect == '') {
                if (response.status == 'success') {
                    Toast.fire({ title: 'Payment Complete', icon: 'success' });
                } else {
                    Toast.fire({ text: response.status });
                }
            } else {
                if (response.status == 'success') {
                    window.parent.location = redirect + response.redirecturl;
                } else {
                    Toast.fire({ text: response.status });
                }
            }
        }
    });
    handler.openIframe();
}

function countCharacters(element) {
    const maxLength = 160;
    const message = element.value;
    const messageLength = message.length;

    const messages = Math.ceil(messageLength / maxLength);
    const remainingCharacters = maxLength - (messageLength % maxLength);

    document.getElementById('charCount').innerText = `Characters: ${messageLength}/${messages * maxLength} - ${messages} message${messages > 1 ? 's' : ''}`;
}