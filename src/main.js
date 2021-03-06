var Consent = require('./index').default;

var consent = new Consent({
    banner: false,
    functional: false
});

consent.isAccepted().then(function(state) {
    console.log(JSON.stringify(state));
});
consent.isAccepted('essential').then(function(type) {
    console.log('Essential cookies have been enabled');
});
consent.isAccepted('marketing').then(function(state) {
    console.log('Marketing is enabled');
});

consent.launch();
window.consent = consent;

Array.prototype.forEach.call(
    document.querySelectorAll('.open-data-consent-dialog'),
    function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            consent.forceOpen();
        });
    }
);
