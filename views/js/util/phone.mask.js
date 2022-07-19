var phoneBehavior = function (val, e, field) {
  return val.replace(/\D/g, '').length == 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
maskInternal = function(val, e, field, options) {
    field.mask(phoneBehavior.apply({}, arguments), spOptions);
},
spOptions = {onKeyPress: maskInternal};