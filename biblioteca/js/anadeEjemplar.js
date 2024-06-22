//AQUI SOLAMENTE VAMOS A ACTUALIZAR LA UBICACION DEL EJEMPLAR
//MOSTRAR EL CAMPO FILE O NO

let comboFormatoFisicoDigital = document.getElementById("comboFormatoFisicoDigital")
let divAnadeFileE = document.getElementById("divAnadeFileE")
let divAnadeUbicacionE = document.getElementById("divAnadeUbicacionE")



if (comboFormatoFisicoDigital){
    comboFormatoFisicoDigital.addEventListener("change", actualizaCamposFiles)
}


function actualizaCamposFiles (){



    if (comboFormatoFisicoDigital.value == -1){
        divAnadeFileE.style.display ="none"
        divAnadeUbicacionE.style.display ="none"

    }

    if (comboFormatoFisicoDigital.value == 1){
                divAnadeFileE.style.display ="none"
        divAnadeUbicacionE.style.display ="inline"
    }

    if (comboFormatoFisicoDigital.value == 2){
                divAnadeFileE.style.display ="inline"
        divAnadeUbicacionE.style.display ="none"
    }

}