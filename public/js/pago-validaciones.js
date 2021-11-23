document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("pago-form").addEventListener('submit', validarFormulario);
});

function validarFormulario(evento) {
    evento.preventDefault();
    var regexpNumber = /^\d+$/;
    var regexpString = /[a-zA-Z ]{5,254}/
    var x=0;
    if($('#cname').val()=="" || !regexpString.test($('#cname').val()) ) {
        $('#cname-error').css("visibility", "visible");
    }else{
        $('#cname-error').css("visibility", "hidden");
        x++;
    }
    if($('#ccnum').val()==""|| $('#ccnum').val().length!= 16 || !regexpNumber.test($('#ccnum').val()) ) {
        $('#ccnum-error').css("visibility", "visible");
    }else{
        $('#ccnum-error').css("visibility", "hidden");
        x++;
    }
    if($('#expmonth').val()=="" || !regexpString.test($('#expmonth').val()) ) {
        $('#expmonth-error').css("visibility", "visible");
    }else{
        $('#expmonth-error').css("visibility", "hidden");
        x++;
    }
    if($('#expyear').val()=="" || $('#expyear').val().length!= 4 || !regexpNumber.test($('#expyear').val())) {
        $('#expyear-error').css("visibility", "visible");
    }else{
        $('#expyear-error').css("visibility", "hidden");
        x++;
    }
    if($('#cvv').val()=="" || $('#cvv').val().length!=3 || !regexpNumber.test($('#cvv').val())) {
        $('#cvv-error').css("visibility", "visible");
    }else{
        $('#cvv-error').css("visibility", "hidden");
        x++;
    }
    if(x==5){
        this.submit();
    }else{
        return;
    }
}