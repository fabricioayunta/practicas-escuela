/* Relevamiento de laboratorio: progreso, detalle de inconvenientes y confirmación. */
(function () {
    "use strict";

    var form = document.querySelector(".rel-formulario");
    if (!form) { return; }

    var fichas    = Array.prototype.slice.call(form.querySelectorAll("fieldset.rel-pc"));
    var contador  = document.getElementById("rel-contador");
    var pendTexto = document.getElementById("rel-pendientes");
    var barra     = document.getElementById("rel-barra");
    var previsto  = document.getElementById("rel-resultado-previsto");
    var enviando  = false;

    var ETIQUETAS = {
        ok:        { clase: "rel-ok",        icono: "✔", texto: "En condiciones" },
        problema:  { clase: "rel-problema",  icono: "⚠", texto: "Con inconvenientes" },
        pendiente: { clase: "rel-pendiente", icono: "…", texto: "No revisada" }
    };

    function valorDe(ficha) {
        var marcado = ficha.querySelector('input[type="radio"][name^="estado["]:checked');
        return marcado ? marcado.value : "pendiente";
    }

    function actualizarFicha(ficha) {
        var valor   = valorDe(ficha);
        var info    = ETIQUETAS[valor] || ETIQUETAS.pendiente;
        var detalle = ficha.querySelector("[data-detalle]");
        var etiqueta = ficha.querySelector("[data-etiqueta]");

        ficha.classList.remove("rel-pc-pendiente", "rel-pc-ok", "rel-pc-problema");
        ficha.classList.add("rel-pc-" + (valor === "ok" ? "ok" : valor === "problema" ? "problema" : "pendiente"));

        if (detalle) { detalle.hidden = (valor !== "problema"); }

        if (etiqueta) {
            etiqueta.className = "estado rel-etiqueta " + info.clase;
            etiqueta.textContent = "";
            var icono = document.createElement("span");
            icono.setAttribute("aria-hidden", "true");
            icono.textContent = info.icono;
            etiqueta.appendChild(icono);
            etiqueta.appendChild(document.createTextNode(" " + info.texto));
        }
    }

    function resumen() {
        var total = fichas.length, revisadas = 0, problemas = 0, faltan = [];

        fichas.forEach(function (f) {
            var v = valorDe(f);
            if (v === "ok" || v === "problema") { revisadas++; }
            if (v === "problema") { problemas++; }
            if (v === "pendiente") { faltan.push("PC " + f.getAttribute("data-numero")); }
        });

        return { total: total, revisadas: revisadas, problemas: problemas, faltan: faltan };
    }

    function actualizarTodo() {
        var r = resumen();

        contador.textContent = r.revisadas + " de " + r.total + " computadoras revisadas";
        barra.max = r.total;
        barra.value = r.revisadas;

        if (r.faltan.length === 0) {
            pendTexto.textContent = "";
        } else if (r.faltan.length <= 8) {
            pendTexto.textContent = " Faltan: " + r.faltan.join(", ") + ".";
        } else {
            pendTexto.textContent = " Faltan " + r.faltan.length + " computadoras.";
        }

        if (r.faltan.length > 0) {
            previsto.textContent = "Todavía no se puede confirmar: faltan revisar " + r.faltan.length + " computadora(s).";
            previsto.className = "rel-resultado-previsto rel-pendiente";
        } else if (r.problemas > 0) {
            previsto.textContent = "Resultado: Con inconvenientes (" + r.problemas + " computadora(s) con problemas).";
            previsto.className = "rel-resultado-previsto rel-problema";
        } else {
            previsto.textContent = "Resultado: Todo OK. El laboratorio queda habilitado para este turno.";
            previsto.className = "rel-resultado-previsto rel-ok";
        }
    }

    fichas.forEach(function (ficha) {
        actualizarFicha(ficha);
        ficha.addEventListener("change", function () {
            actualizarFicha(ficha);
            actualizarTodo();
        });
    });

    actualizarTodo();

    form.addEventListener("submit", function (evento) {
        if (enviando) { evento.preventDefault(); return; }

        var r = resumen();

        if (r.faltan.length > 0) {
            evento.preventDefault();
            window.alert("Faltan revisar " + r.faltan.length + " computadora(s): " + r.faltan.join(", ") +
                ".\n\nMarque cada una como «En condiciones» o «Con inconvenientes» para poder confirmar.");
            var primera = form.querySelector('fieldset.rel-pc-pendiente');
            if (primera) { primera.scrollIntoView({ behavior: "smooth", block: "center" }); }
            return;
        }

        var texto = "¿Confirma el relevamiento de " + (form.getAttribute("data-laboratorio") || "") +
            " (turno " + (form.getAttribute("data-turno") || "").toLowerCase() + ")?\n\n" +
            "Computadoras revisadas: " + r.revisadas + " de " + r.total + "\n" +
            "Con inconvenientes: " + r.problemas + "\n" +
            "Resultado: " + (r.problemas > 0 ? "Con inconvenientes" : "Todo OK") +
            "\n\nUna vez guardado, el registro no se puede modificar.";

        if (!window.confirm(texto)) { evento.preventDefault(); return; }

        enviando = true;
        var boton = form.querySelector('button[type="submit"]');
        if (boton) { boton.disabled = true; boton.textContent = "Guardando..."; }
    });

    /* Si vuelve con el botón "atrás", se rehabilita el formulario */
    window.addEventListener("pageshow", function (e) {
        if (e.persisted) {
            enviando = false;
            var boton = form.querySelector('button[type="submit"]');
            if (boton) { boton.disabled = false; boton.textContent = "Confirmar relevamiento"; }
        }
    });
})();
