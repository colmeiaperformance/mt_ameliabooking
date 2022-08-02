jQuery( ".phone" ).ready(function() {

    var inputs = document.querySelectorAll(".phone");

    inputs.forEach(input => {
        window.intlTelInput(input, {
            initialCountry: "BR",
            preferredCountries: ["BR", "US"]
        }); 

        jQuery(input).on("input focus", function() {
            jQuery(this).mask(apply(this)); 
            this.removeAttribute('maxLength');
        });
    });

    const apply = (element) => {
        function placeholder(element){
            let length = element.value.replace(/\D/g, '').length;
            if(element.parentNode.firstChild.firstChild.firstChild.classList.contains('iti__br')){
                if(length === 11){
                    return '(00) 00000-0000';
                }else{
                    return '(00) 0000-00009';
                }
            }else{
                if(length === element.placeholder.replace(/\D/g, '').length){
                    return element.placeholder.replace(/[1-9]/g, '0');
                }
            }
        }

        var behavior = function (val) {
            return placeholder(element);
        },
        options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(behavior.apply({}, arguments), options);
            }
        };

        jQuery(element).mask(behavior, options);
    }

});