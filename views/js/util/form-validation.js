(() => {
  'use strict'

  jQuery('.needs-validation').ready(function() {
    const forms = document.querySelectorAll('.needs-validation')

    forms.forEach(form => {
      form.addEventListener('submit', event => {
        event.preventDefault();
        
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
  });
})()