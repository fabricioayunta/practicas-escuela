<?php
/*
   Tablas en pantallas chicas.
   Copia el título de cada columna en sus celdas (data-label) para que,
   en el celular, cada fila se muestre como una tarjeta sin deslizar
   hacia el costado. Lo incluye el pie de los tres módulos.
   Las tablas tipo ficha (dos columnas, sin encabezado) no se tocan.
*/
?>
<script>
document.querySelectorAll("table").forEach(function (tabla) {
    var titulos = Array.prototype.map.call(tabla.querySelectorAll("thead th"), function (th) {
        return th.textContent.trim();
    });

    if (titulos.length === 0 || tabla.classList.contains("ficha")) {
        return;
    }

    tabla.classList.add("tabla-tarjetas");

    var contenedor = tabla.closest(".tabla-responsive");
    if (contenedor) {
        contenedor.classList.add("con-tarjetas");
    }

    tabla.querySelectorAll("tbody tr").forEach(function (fila) {
        var columna = 0;

        Array.prototype.forEach.call(fila.children, function (celda) {
            if (celda.colSpan === 1 && titulos[columna]) {
                celda.setAttribute("data-label", titulos[columna]);
            }
            columna += celda.colSpan;
        });
    });
});
</script>
