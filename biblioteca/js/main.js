let divEjemplares = document.getElementsByClassName("divEjemplar");
let divPrestamos = document.getElementsByClassName("divPrestamo");
let barraBuscador = document.getElementsByClassName("fas fa-search")[0];
let comboCategoriaObra = document.getElementById("comboCategoriaObra");
let comboGeneroObra = document.getElementById("comboGenerosObras");
let comboFormatosEjemplar = document.getElementById("ejemplares_cod_formato_ejemplar")
let comboFormatoMedio = document.getElementById("ejemplares_codigo_formato_medio")
let comboObraEjemplar = document.getElementById("comboObraEjemplar")
let divPelicula = document.getElementById("divAnadePeliculaE")
let divLibro = document.getElementById("divAnadeLibroE")
let divAudio = document.getElementById("divAnadeAudioE")
let divAnadeFileE = document.getElementById("divAnadeFileE")
let divAnadeUbicacionE = document.getElementById("divAnadeUbicacionE");
let bBorrarCamposAnadeE = document.getElementById("borrarCamposAnadeE");
let botonResetAnade = document.getElementById("resetCampos")
let bMarchaTodosCheck = document.getElementById("selectCheckPrestamo")
let bEnviarNotiCheck = document.getElementById("enviarCorreoPrestamo")
let comboUsuarioAndalucia = document.getElementById("comboModAndalucia");
let comboModPoblacion = document.getElementById("comboModPoblacion")


if (!("Notification" in window)) {
    alert("Este navegador no soporta las notificaciones del sistema");
   }
   
else {
    Notification.requestPermission().then(function(result) {
    console.log(result);
    });
}



const RUTA = "http://www.biblioteca.es";

//-------------------------------------------------------------------------------------------------------------//
//-------------------------------------EVENTOS ON CLICK---------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//


if (botonResetAnade){
    botonResetAnade.addEventListener("click", respetaValores)
}


//Si no es nulo damos eventos
if (divEjemplares){
    for (let ejemplar of divEjemplares){
        ejemplar.addEventListener("click", muestraEjemplar)

    }
}


if (divPrestamos){
    for (let prestamo of divPrestamos){
        prestamo.addEventListener("click", muestraPrestamo)

    }
}

/*Comprobamos si existe */
if (barraBuscador){
    barraBuscador.addEventListener ("click", function (){
        alert("Evento buscar")
    })
}

/*Se comprueba que exista en la página actual */
if (comboCategoriaObra){
    comboCategoriaObra.addEventListener("change", actualizaComboObras)

}


if (comboFormatosEjemplar){
    comboFormatosEjemplar.addEventListener("change", actualizarComboEjemplares)
}

if (comboObraEjemplar){
    comboObraEjemplar.addEventListener("change", rellenarCamposAnade)

}



if (comboFormatosEjemplar){ //si se elige fisico o digital se mostrara el input file
    comboFormatosEjemplar.addEventListener("change", mostrarDivUbicacionEjemplar)
}


if (bBorrarCamposAnadeE){
    bBorrarCamposAnadeE.addEventListener("change", borrarDatosFormAddEjemplar)
}

if (bMarchaTodosCheck){
    bMarchaTodosCheck.addEventListener("click", marcarCheckPrestamos)
}


if (bEnviarNotiCheck){
    bEnviarNotiCheck.addEventListener("click", enviaCorreoChecksMarcados)
}

if (comboUsuarioAndalucia){
    comboUsuarioAndalucia.addEventListener("change", actualizaComboPoblaciones)

}

//LO TENGO QUE HACEEEEEEEEEEEEEEEEEEER
//ESTO ES PARA EL AÑADIR PARA QUE NO SE ME BORREN TODOS LOS CAMPOS
//PERO VOY A PONTER LOS DATOS NO MODIFICABLES EN LABEL MEJOR

function borrarDatosFormAddEjemplar(){


    alert("borrar campos")
}



//-------------------------------------------------------------------------------------------------------------//
//---------------------------------------FUNCIONES Y PETICIONES------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//


/**
 * Sirve para mostrar u ocultar
 * el input file o text en funcion si es fisico
 * o digital
 */
function mostrarDivUbicacionEjemplar(){



    let tipoFormato = comboFormatosEjemplar.value
    tipoFormato = parseInt(tipoFormato)

    if (tipoFormato === -1){
        divAnadeFileE.style.display = "none"
        divAnadeUbicacionE.style.display = "none"

    }


    if (tipoFormato === 1){ //fisico
        divAnadeFileE.style.display = "none"
        divAnadeUbicacionE.style.display = "inline"
        

    }
    

    
    if (tipoFormato === 2){ //digital
        divAnadeFileE.style.display = "inline"
        divAnadeUbicacionE.style.display = "none"

    }
    
}


/**
 * 
 */
function muestraPrestamo (){
    
    //Se coge el valor del id, está con el formato digito-digito

    //el primer digito es el cod de la obra y el segundo el del usuario actual
    //para validarlo luego en el controlador

    let miId = this.id
    miId = miId.split("-")

    let codPrestamo = parseInt(miId[0]);
    let codUsuario = parseInt(miId[1]);

    location.href = "http://www.biblioteca.es/prestamos/verPrestamo/?prest="+codPrestamo+"&us="+codUsuario



}


/**
 * Función que se usa para evento onclick de una tarjeta de los ejemplares mostrados en el indice
 * se coge el id del ejemplar y se redirige a la página con ese id
 */
function muestraEjemplar(){

    location.href = "http://www.biblioteca.es/inicial/verObra/id="+this.id
 }
 



/**
 * Función que hace petición al servidor para actualizar el comboBox de los géneros
 * se cargará en función del tipo de obra seleccionado en el comboBox de caterorías obras
 */
function actualizaComboObras (){

    let codCategoria = comboCategoriaObra.value
    codCategoria = parseInt(codCategoria)

    if (Number.isInteger(codCategoria) === true){ //Se comprueba que se un número

        let enlace = RUTA + "/obras/PeticionGenerosCategorias"


        //Hacemos la petición fetch al servidor

        fetch (enlace, {
            method: "POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"}, // Poner solo si se envían datos
            body : "id=" + codCategoria
        })
        .then(function (response){

            if (response.ok){
                response.text()
                .then(function (resp){

                    //De respuesta espero un arrayJSON
                    var arrayJSON = JSON.parse(resp);

                    if (arrayJSON.correcto === true){ //Validamos resultado
                       

                        //Borramos los elementos anteriores
                        borraElementosNodo(comboGeneroObra)

                        var arrayGeneros = arrayJSON.generosObras; //Nos saca un objeto

                        let opcionDefecto = creaNodo("option", "Seleccione una opción", {"value": "-1"})
                        comboGeneroObra.appendChild(opcionDefecto)
                        for (const genero in arrayGeneros){

                            let cadenaNumber = genero.toString();
                            let opcion = creaNodo("option",  arrayGeneros[genero],{"value": cadenaNumber})
                            
                            
                            comboGeneroObra.appendChild(opcion)

                        }

                    }
                    else{
                        console.log(arrayJSON["generosObras"]);
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
///------------------------------------------EJEMPLARES-----------------------------------///
///---------------------------------------------------------------------------------------///


function actualizarComboEjemplares (){

    //TAMBIEN HAY QUE CAMBIAR EL TYPE DEL INPUT FILE
    //SI ES LIBRO, AUDIO O PELICULA
    // document.getElementsByTagName("input")[10].type




    //Cogemos el valor del input hidden que nos da el parámetro del tipo de obra
    let codTipoObra = document.getElementById("valueCodTipoObra").value
    codTipoObra = parseInt(codTipoObra)

    //Cogemos el valor del combo que nos indica si es fisico o digital
    let codFormatoEjemplar = comboFormatosEjemplar.value
    codFormatoEjemplar =  parseInt(codFormatoEjemplar)

    //Con estos dos parámetros enviados por POST podemos llamar a los
    // metodos que nos dan el combo de los tipos de formatos actualizados
    // cada vez que se cambie de fisica a digital o al revés


    //Comprobamos que ambos valores sean numéricos

    if (Number.isInteger(codFormatoEjemplar)=== true 
    &&  Number.isInteger(codTipoObra)=== true){

        //A partir de la selección del combo, si es fisico o digital muestro el
        //input type file

         if( document.getElementById("inputFileMod")){

             let inputFileMod = document.getElementById("inputFileMod")

             let valorHidden = inputFileMod.style.display;

             if (codFormatoEjemplar === 1) { //FÍSICO

                 if (valorHidden === "inline") { //Si es físico, hay que ocultarlo hay que mirar el display
                     inputFileMod.style.display = "none"
                 }

             }

             if (codFormatoEjemplar === 2) { //DIGITAL

                 //Es digital se tiene que mostrar
                 if (valorHidden === "none") {
                     inputFileMod.style.display = "inline"

                 }


        }


        }




   
        

        let enlace = RUTA + "/ejemplares/PeticionFormatosEjemplares"

        //Se hace petición fetch al server

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
    else{
        console.log("Error: los valores no son numéricos")
    }


}

/**
 * 
 */
function rellenarCamposAnade (){

    let codObra = comboObraEjemplar.value

    codObra = parseInt(codObra);
    let divForm = document.getElementById("camposAnade");


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



                                    if (divForm.style.display !== "block"){
                                        document.getElementById("camposAnade").style.display = "block"
                                    }

                                    
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
                                        document.getElementById("camposAnade").append(inputHidden)
                                       }


                                 
                                        let inputAutor = document.getElementById("ejemplares_autor");
                                        let inputTitulo = document.getElementById("ejemplares_titulo");
                                        let inputGenero = document.getElementById("ejemplares_codigo_genero");
                                        let inputDistribuidora = document.getElementById("ejemplares_distribuidora");
                                        let inputFechaLanzamiento = document.getElementById("ejemplares_fecha_lanzamiento");

                                        document.getElementById("ejemplares_cod_formato_ejemplar").value = "-1"
                                        document.getElementById("ejemplares_codigo_formato_medio").value = "-1"


                                        //ocultamos divs que muestran input de ruta ubicacion
                                        divAnadeFileE.style.display = "none"
                                        divAnadeUbicacionE.style.display = "none"

                                        //Tengo que mostrar los divs de libro, pelicula o audio
                                        //comprobar su style.display

                        

                                        
                                        inputAutor.value = autor
                                        inputTitulo.value = titulo
                                        inputGenero.value = genero
                                        inputDistribuidora.value = distribuidora
                                        inputFechaLanzamiento.value = fechaLanzamiento


                                    if (codCategoriaObra === 1) {//libro

                                        if (divLibro.style.display === "none"){
                                            divLibro.style.display = "block";
                                        }
                                        divPelicula.style.display = "none"
                                        divAudio.style.display = "none"
                                        // let divPelicula = document.getElementById("divAnadePeliculaE")
                                        // let divLibro = document.getElementById("divAnadeLibroE")
                                        // let divAudio = document.getElementById("divAnadeAudioE")
                                    }


                                    if (codCategoriaObra === 2) {//peliucla

                                        if (divPelicula.style.display === "none"){
                                            divPelicula.style.display = "block";
                                        }
                                        divLibro.style.display = "none"
                                        divAudio.style.display = "none"
                                    }


                                    if (codCategoriaObra === 3) {//audio

                                        if (divAudio.style.display === "none"){
                                            divAudio.style.display = "block";
                                        }
                                        divLibro.style.display = "none"
                                        divPelicula.style.display = "none"
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
    else{
        
        
        if (divForm.style.display !== "none"){
            document.getElementById("camposAnade").style.display = "none"
        }

    }
}


function respetaValores(){
    // Elige una obra: 
    // Frankenstein o el moderno Prometeo - LIBRO
    // Título 
    // Frankenstein o el moderno Prometeo
    
    // Autor 
    // Mary Shelley
    
    // Género 
    // gótico
    
    // Distribuidora 
    // Editorial Valdemar
    
    // Fecha de lanzamiento
}


function modificarInputFile (){}


///---------------------------------------------------------------------------------------///
///------------------------------------INDEX DE PRESTAMOS---------------------------------///
///---------------------------------------------------------------------------------------///


/**
 * Funcion para evento de boton onclick
 * se llama cuando pulsamos el boton de index préstamos
 * que marcará todos los checks, en caso de estar marcados
 * se desmarcarán
 */
function marcarCheckPrestamos (){

    //Si se pulsa el boton recogemos un colección de HTML de 
    //elementos checkbox a partir del name, se comprobarán si 
    //estan marcados o no
    let inputsIndexPrestamos = document.getElementsByName("enviarMail")
    let arrayMarcados = [];
    let arrayNoMarcados = [];

    for(const inputC of inputsIndexPrestamos){

        if (inputC.checked === true){
            arrayMarcados.push(inputC)
        }

        if (inputC.checked === false){
            arrayNoMarcados.push(inputC)
        }
    }


    if (arrayMarcados.length >= arrayNoMarcados){//Si hay más marcados que no marcados, se desmarcan todos

        for(const inputC of inputsIndexPrestamos){
                inputC.checked = false;
        }
    }
    else{//Si no se ponen todos a true

        for(const inputC of inputsIndexPrestamos){
            inputC.checked = true;
        }
    }

}

/**
 * Función que al pulsar el boton de index prestamos
 * envia una petición FETCH al servidor con los ids de los
 * prestamos a los que hay que enviar un correo, cuando se hayan enviado
 * se pone una notifiación push-up
 */
function enviaCorreoChecksMarcados(){

    let inputsIndexPrestamos = document.getElementsByName("enviarMail")

    let arrayMarcados = [];

    //guardamos marcados
    for(const inputC of inputsIndexPrestamos){

        if (inputC.checked === true){
            arrayMarcados.push(inputC)
        }
    }


    if (arrayMarcados.length === 0){
        alert("Debes seleccionar algún préstamo")
    }
    else{

        let arrayCodPrestamos = []

        for(const codPrestamos of arrayMarcados){

            arrayCodPrestamos.push(codPrestamos.value)
        }

        //Montamos petición FETCH

        let enlace = RUTA + "/prestamos/enviaNotificacionUsuariosPrestamos"

        fetch (enlace ,{
            method: "POST",
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(arrayCodPrestamos)
        })
        .then(function(response){

            if (response.ok){
                response.text()
                .then(function(resp){


                    var arrayJSON = JSON.parse(resp)


                    if (arrayJSON.correcto === true){

                        //Enviamos notifiación de que se ha validado
                        var options = {
                            body: "Notificación enviada! ", // Mensaje.
                            icon: "../imagenes/logo.jpg" // Icono de la notificación (opcional).
                        }
                        var n = new Notification("Biblioteca grimorios", options);
                        setTimeout(n.close.bind(n), 5000);
                        
                        
                        //Ahora desmarcamos los campos check
                        for (const inputCheck of arrayMarcados){
                            inputCheck.checked = false;
                        }


                    }
                    else{

                    }
                })
            }

        })
        .catch(function (e){
            console.log("Error: " + e)
        })


    }

}



function actualizaComboPoblaciones (){

    let valorComboAndalucia = comboUsuarioAndalucia.value

    if (valorComboAndalucia != -1 && valorComboAndalucia != ""){ //Se cambia valor del combo

        let enlace = RUTA + "/usuarios/PeticionProvinciasAndalucia"
        
        //Se hace petición fetch al server

        fetch (enlace, {
            method: "POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"}, // Poner solo si se envían datos
            body : "provincia=" + valorComboAndalucia

        })
        .then(function(response){

            if (response.ok){
                response.text()
                .then(function (resp){


                    var arrayJSON = JSON.parse(resp);


                    if (arrayJSON.correcto === true){

                        borraElementosNodo(comboModPoblacion)


                        var arrayPoblaciones = arrayJSON.respuesta;

                        let opcionDefecto = creaNodo("option", "Seleccione una opción", {"value": "-1"})
                        comboModPoblacion.appendChild(opcionDefecto)


                        for (const poblacion in arrayPoblaciones){

                            let opcion = creaNodo("option",  poblacion,{"value": poblacion})
                            
                            
                            comboModPoblacion.appendChild(opcion)

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