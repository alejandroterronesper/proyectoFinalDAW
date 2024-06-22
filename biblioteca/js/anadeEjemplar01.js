//Variables de elementos HTML
let comboObraEjemplar = document.getElementById("comboObraEjemplar")
let comboFormatoFisicoDigital = document.getElementById("comboFormatoFisicoDigital")
let divAnadeFileE = document.getElementById("divAnadeFileE")
let divAnadeUbicacionE = document.getElementById("divAnadeUbicacionE");
let comboFormatoMedio = document.getElementById("comboFormatosEjemplaresMedios");
let divAnadePeliculaE = document.getElementById("divAnadePeliculaE")
let divAnadeAudioE = document.getElementById("divAnadeAudioE")
let divAnadeLibroE = document.getElementById("divAnadeLibroE")


const RUTA = "http://www.biblioteca.es";



//Se comprueba que existe y se añade evento on change
if (comboObraEjemplar){
    comboObraEjemplar.addEventListener("change", rellenarCamposAnade)
}

if (comboFormatoFisicoDigital){
    comboFormatoFisicoDigital.addEventListener("change", rellenaCampoRuta)

}







/**
 * Función para rellenar los campos de la obra
 * del formulario de añade ejemplar
 * en función de la obra seleccionada, se rellenaran con
 * los campos correspondientes a estos
 */
function rellenarCamposAnade (){

    //Se coge el código de la obra
    let codObra = comboObraEjemplar.value
    codObra = parseInt(codObra);


    if (Number.isInteger(codObra) === true){

        if (codObra === -1){//si se elige -1 se oculta


            if (divForm.style.display !== "none"){
                document.getElementById("camposAnade").style.display = "none"
            }

        }
        else{
            let enlace = RUTA + "/obras/dameObjObra";


            fetch(enlace, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" }, // Poner solo si se envían datos
                body: "codObra=" + codObra
            })
                .then(function (response) {

                    if (response.ok) {
                        response.text()
                            .then(function (resp) {

                                var arrayJSON = JSON.parse(resp);

                                if (arrayJSON.correcto === true) {
                                    
                                        //se cargan los campos del modelo ejemplar
                                        //que corresponde a la obra

                                        let datosObra = arrayJSON.respuesta
                                        let titulo = datosObra.titulo
                                        let autor = datosObra.autor
                                        let genero = datosObra.cod_genero
                                        let distribuidora = datosObra.distribuidora
                                        let fechaLanzamiento = datosObra.fecha_lanzamiento
                                        let codCategoriaObra = datosObra.cod_categoria_obra


                                        //AQUI TENGO QUE HACER UN HIDDEN QUE SE CREE Y SE BORRE PONERLE UN ID
                                        //Y PILLARLO DESDE EL FETCH DEL COMBO
                                       if (document.getElementById("valueCodTipoObra")){

                                            document.getElementById("valueCodTipoObra").value = codCategoriaObra
                                       }
                                       else{

                                            let inputHidden = creaNodo("input", null, {"id": "valueCodTipoObra", "type":"hidden", "value":codCategoriaObra})
                                            let formulario = document.getElementsByTagName("form")[0]

                                            formulario.append(inputHidden)
                                        
                                        // document.getElementById("camposAnade").append(inputHidden)
                                       }

                                 
                                        let inputAutor = document.getElementById("ejemplares_autor");
                                        let inputTitulo = document.getElementById("ejemplares_titulo");
                                        let inputGenero = document.getElementById("ejemplares_descripcion_genero");
                                        let inputDistribuidora = document.getElementById("ejemplares_distribuidora");
                                        let inputFechaLanzamiento = document.getElementById("ejemplares_fecha_lanzamiento");

                                        inputAutor.value = autor
                                        inputTitulo.value = titulo
                                        inputGenero.value = genero
                                        inputDistribuidora.value = distribuidora
                                        inputFechaLanzamiento.value = fechaLanzamiento

                                        //Ahora mostramos u ocutlamos modelos de libro audio o pelicula
                                        if (codCategoriaObra === 1){

                                            divAnadePeliculaE.style.display ="none"
                                            divAnadeAudioE.style.display ="none"
                                            divAnadeLibroE.style.display = "block" 
                                        }
                                        if (codCategoriaObra === 2){

                                            divAnadePeliculaE.style.display ="block"
                                            divAnadeAudioE.style.display ="none"
                                            divAnadeLibroE.style.display = "none" 
                                            
                                        }
                                        if (codCategoriaObra === 3){

                                            divAnadePeliculaE.style.display ="none"
                                            divAnadeAudioE.style.display ="block"
                                            divAnadeLibroE.style.display = "none" 
                                            
                                        }

                                    
                                    
                                }
                                else {

                                    let respuesta = arrayJSON.respuesta;

                                    console.log(respuesta);
                                }

                            })

                    }

                })
                .catch(function (e) { //En caso de haber errores
                    console.log("Error: " + e);
                })
        }
    }

}


/**
 * esto añade campos rutas
 * y rellena el combo de los tipos de formatos ejemplares
 * tipo si es libro y digital que sea en pdf
 * si es libro y fisico que sea tapa dura, tapa blanca... 
 */
function rellenaCampoRuta(){

    let formatoFisicoDigital = comboFormatoFisicoDigital.value
    let codObra = formatoFisicoDigital
    formatoFisicoDigital = parseInt(codObra);

    //SE MODIFICA LOS MIMES DEL FICHERO QUE PERMITIRA
    let tipoObra = ""
    if (document.getElementById("valueCodTipoObra")) {
        tipoObra = document.getElementById("valueCodTipoObra").value;
    }

 
    atualizaComboMediosEjemplares(tipoObra, formatoFisicoDigital)

    //tres casos 

    if (formatoFisicoDigital === -1){
        divAnadeFileE.style.display =  "none"
        divAnadeUbicacionE.style.display =  "none"
    }

    
    if (formatoFisicoDigital === 1){//fisico
        divAnadeFileE.style.display =  "none"
        divAnadeUbicacionE.style.display =  "inline"

        //Ahora relleno el combo de los tipos de ejemplares

        //llamo funcion
    }

    if (formatoFisicoDigital === 2){//digital

        divAnadeFileE.style.display =  "inline"
        divAnadeUbicacionE.style.display =  "none"

     



        let inPutFile = document.getElementById("inputFILEE")

        //y modifico los valores que admite el files
        
        if (tipoObra == 1){//libro
            inPutFile.accept = "application/pdf"
        }

        if (tipoObra == 2){//pelicula
            inPutFile.accept = "video/*"

        }


        if (tipoObra == 3){//audio
            inPutFile.accept = "audio/*"

        }
    }

}


/**
 * Actualiza los combos
 */
function atualizaComboMediosEjemplares (codTipoObra, codFormatoEjemplar){

    let enlace = RUTA + "/ejemplares/PeticionFormatosEjemplares"

    if (codFormatoEjemplar != -1){
        fetch (enlace, {
            method: "POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"}, // Poner solo si se envían datos
            body : "codTipoObra=" + codTipoObra + "&codFormatoEjemplar="+ codFormatoEjemplar
    
        })
        .then(function(response){
    
            if (response.ok){
                response.text()
                .then(function (resp){
    
    
                    var arrayJSON = JSON.parse(resp);
    
    
                    if (arrayJSON.correcto === true){
    
    
                        //Borramos elementos nodo
                        borraElementosNodo(comboFormatoMedio);
    
    
                        var arrayFormatosMedio = arrayJSON.respuesta;
    
                        let opcionDefecto = creaNodo("option", "Seleccione una opción", {"value": "-1"})
                        comboFormatoMedio.appendChild(opcionDefecto)
    
    
                        for (const valor in arrayFormatosMedio){
    
    
    
                            let cadenaNumber = valor.toString();
                            let opcion = creaNodo("option",  arrayFormatosMedio[valor],{"value": cadenaNumber})
    
                            // let cadenaNumber = genero.toString();
                            // let opcion = creaNodo("option",  arrayGeneros[genero],{"value": cadenaNumber})
                            comboFormatoMedio.appendChild(opcion)
    
                        }
    
    
                    }
                    else{
                        let respuesta = arrayJSON.respuesta ;
    
                        console.log(respuesta);
    
                    }
                })
    
            }
    
        })
        .catch(function(e){ //En caso de haber errores
            console.log("Error: " + e);
        })
    }


}



///---------------------------------------------------------------------------------------///
///------------------------------------FUNCIONES-------------------------------------------//
///---------------------------------------------------------------------------------------///


/**
 * Funcion que recibe como parámetro un nodo
 * y borra todos los elementos que tenga dentro
 * 
 * @param {Node} nodo 
 */
function borraElementosNodo (nodo){
    while (nodo.firstChild) {
        nodo.removeChild(nodo.firstChild);
    }
}


/**
 * Funcion que permite crear un nodo,
 * se le pasa como parametro una cadena que indica la etiqueta
 * una cadena que es el contenido que va dentro de la etiqueta
 * y un array con las diferentes propiedades del nodo
 * @param {String} tipoNodo 
 * @param {String | Null} contenido 
 * @param {Array | Null} atributos 
 * @returns {Node}
 */
function creaNodo (tipoNodo, contenido = null, atributos = null){

    let nodo = document.createElement(tipoNodo);

    if (contenido !== null){
        let addContenido = document.createTextNode(contenido);
        nodo.appendChild(addContenido);
    }

    if (atributos !== null){

        for(const clave in atributos){
            nodo.setAttribute(clave, atributos[clave]);
        }
    }

    return nodo;
}