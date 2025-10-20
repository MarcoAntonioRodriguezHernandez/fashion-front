<script setup>
// ------------------------
// Component Attributes
// ------------------------

const props = defineProps({
    clientName: { type: String, default: 'Seleccionar Cliente', },
});
</script>

<template>
    <div style="text-align: justify;">
        <p class="fs-5">
            El <span class="fw-bold">Cliente: </span> <input disabled type="text" :value="clientName" readonly /> se obliga a pagar a Conspiración Moda, S.A de C.V.
            (el <span class="fw-bold">Proveedor</span>) por concepto de Renta del <span class="fw-bold">Bien Arrendado</span> (Vestido) por un periodo de
            <span class="fw-bold">4 (cuatro) días</span>, la cantidad indicada en el ticket de compra en una sola exhibición al momento de apartar el vestido, se
            puede reservar de 3 meses a 1 día antes si hay disponibilidad.
        </p>
        <p class="fs-5">
            <span class="fw-bold">1. Costo/Renta.</span> El <span class="fw-bold">Cliente</span> autoriza expresamente al <span class="fw-bold">Proveedor</span> a
            cobrar, el monto correspondiente a la Renta, así como cualquier otra cantidad que se genere con motivo de alquiler del
            <span class="fw-bold">Bien Arrendado</span>, incluyendo de manera enunciativa más no limitativa, composturas y/o gastos accesorios que se generen.
        </p>
        <p class="fs-5">
            <span class="fw-bold">2. Aprobación del Cliente:</span> El <span class="fw-bold">Cliente</span> estará recibiendo
            el <span class="fw-bold">Bien Arrendado</span> con la entrega del ticket de pago correspondiente, que fue previamente seleccionado, probado y aceptado por
            él, por lo que el <span class="fw-bold">Proveedor</span> no se hace responsable en caso de que al momento de que el <span class="fw-bold">Cliente</span>
            use las prendas, éstas no le queden correctamente o bien si el <span class="fw-bold">Cliente</span> decide no usar el <span class="fw-bold">Bien Arrendado
            </span>. Bajo ningún motivo hay devoluciones.
        </p>
        <p class="fs-5">
            <span class="fw-bold">3. Garantía y Responsabilidad del Cliente:</span> Con el efecto de garantizar que el <span class="fw-bold">Cliente</span> efectúe
            al término del plazo pactado la devolución del <span class="fw-bold">Bien Arrendado</span>, en buenas condiciones o bien, que éste lo devuelva con
            deterioro, manchas permanentes, quemaduras, así como para evitar la pérdida, robo o extravío y/o daños irreparables del
            <span class="fw-bold">Bien Arrendado</span>, el <span class="fw-bold">Cliente</span> entregará al <span class="fw-bold">Proveedor</span>:
        <ul class="fs-5">
            <li>
                Una identificación oficial con foto y dirección cierta. El <span class="fw-bold">Proveedor</span> tendrá el derecho de quedarse con una copia o
                foto de dichos documentos durante el periodo de renta.
            </li>
        </ul>
        </p>
        <p class="fs-5">
            <span class="fw-bold">4. Tiempo de Renta.</span> El <span class="fw-bold">Bien Arrendado</span> será rentado por 4 (cuatro) días naturales, En caso de
            no ser entregado en la fecha acordada, se generará por el primer día un cargo de $150; a partir del segundo día $250 por día y a partir del sexto día, se
            le cobrará al <span class="fw-bold">Cliente</span> la totalidad del valor del vestido descontando el monto que el <span class="fw-bold">Cliente</span> pagó
            por la renta del mismo.
        </p>
        <p class="fs-5">
            <span class="fw-bold">6. Ajustes del Bien Arrendado:</span>
        <ul class="fs-5">
            <li>
                Sólo se podrán hacer ajustes que no modifiquen la talla del vestido.
            </li>
            <li>
                Los ajustes adicionales serán sujetos a criterio, se cobraran extra y están sujetos a disponibilidad de tiempo.
            </li>
            <li>
                Es responsabilidad del <span class="fw-bold">Cliente</span> acudir a la toma de medidas.
            </li>
            <li>
                El <span class="fw-bold">Cliente</span> debe asegurarse que todos los ajustes estén en su nota o recibo. No nos hacemos responsables por los ajustes
                que no estén en la nota.
            </li>
            <li>
                Al momento de recibir el <span class="fw-bold">Bien Arrendado</span> es responsabilidad del <span class="fw-bold">Cliente</span> revisarlo y notificar a
                tienda si tiene algún daño o está inconforme con algún ajuste realizado.
            </li>
            <li>
                Es necesario agendar cita de prueba previa al evento del <span class="fw-bold">Cliente</span> con al menos 5 (cinco) días naturales de anticipación.
            </li>
        </ul>
        </p>
        <p class="fs-5">
            <span class="fw-bold">7. Daños o pérdidas:</span> Se considerará daño en los siguientes casos:
        <ul class="fs-5">
            <li>
                Manchas extremas de lodo, vino o cualquier sustancia difícil de limpiar.
            </li>
            <li>
                Daño al Bien arrendado de 1 a 5 centímetros de la parte inferior del vestido.
            </li>
            <li>
                Quemaduras de cigarro, desgarre, taconazos a partir de 1.5 cm. desde el suelo hacia el vestido.
            </li>
            <li>
                Que los vestidos regresen con piedras faltantes
            </li>
            <li>
                Que los vestidos tengan quemaduras o manchas que sean imposibles de reparar y de limpiar.
            </li>
            <li>
                Que él o los productos tengan lentejuelas caídas en una superficie de más de 5 cm. cuadrados.
            </li>
        </ul>
        </p>
        <p class="fs-5">
            En caso de que el <span class="fw-bold">Cliente</span> regrese el <span class="fw-bold">Bien Arrendado</span> con algún daño mayor, tendrá que pagar
            el costo total del <span class="fw-bold">Bien Arrendado</span>, y en caso de que el <span class="fw-bold">Bien Arrendado</span> tenga solución, estará
            sujeto a valoración del daño. En caso de robo o extravío del <span class="fw-bold">Bien Arrendado</span>, el <span class="fw-bold">Cliente</span> deberá
            de cubrir el valor del <span class="fw-bold">Bien Arrendado</span>.
        <ul class="fs-5">
            <li>
                El <span class="fw-bold">Cliente</span> deberá de respetar las etiquetas de la marca y de no removerlas ni hacer ninguna modificación al vestido. En
                caso de ser removidas o dañadas (una o ambas etiquetas) se cobrará un costo adicional a la renta.
            </li>
            <li>
                El saldo por las reparaciones/ cargos extras por daños antes mencionados tendrá que ser liquidado el día de la entrega del
                <span class="fw-bold">Bien Arrendado</span>.
            </li>
            <li>
                No nos hacemos responsables por objetos olvidados en el <span class="fw-bold">Bien Arrendado</span>.
            </li>
        </ul>
        </p>
        <p class="fs-5">
            <span class="fw-bold">8. Datos personales:</span> La Información recabada en el proceso de renta sólo es para el alquiler del
            <span class="fw-bold">Bien Arrendado</span>, dicha información no será utilizada para fines distintos que a la Renta del
            <span class="fw-bold">Bien Arrendado</span>.
            <span class="fw-bold">El Proveedor</span> puede obtener los datos personales cuando el Titular los proporciona de forma personal o directa en las sucursales.
        </p>
        <p class="fs-5">
            <span class="fw-bold">9. Contacto:</span> Para cualquier duda o comentario acerca del proceso de renta, ponemos a su disposición los siguientes medios de
            contacto:
        <ul class="fs-5">
            <li>
                info@conspiracionmoda.com
            </li>
        </ul>
        </p>
        <p class="fs-5">
            <span class="fw-bold">10. Jurisdicción y competencia:</span> Las Partes acuerdan someterse a las Leyes y Tribunales de la Ciudad de México para la resolución
            de todos los conflictos que pudieran surgir en virtud del presente, renunciando las Partes a cualquier jurisdicción y competencia que les corresponda por
            razón de domicilio presente y/o futuro o por cualquier otra causa.
        </p>
    </div>
</template>
