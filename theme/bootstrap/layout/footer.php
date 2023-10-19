<section id="bloque-sasde" class="<?php echo $container; ?>">
        <h2>Sistema de acompañamiento y seguimiento</h2>
    
        <div class="col-sm-3 col-xs-12 botiquin">
            <h3>¿Necesita ayuda?</h3>
            <img src="<?php echo $CFG->wwwroot; ?>/plantillas/img/botiquin_ayuda.png" alt="Sistema de apoyo y seguimiento" class="img-responsive">
        </div>

        <div class="col-sm-3 col-xs-6">
            <h4>Celular y WhatsApp:</h4><p> 320 3921622</p> 
            <!--<h4>Telef&oacute;no:</h4>
            <p>(57) (6)<br /> 
                313 7117 - 313 7373 - 313 7548 - 313 7549<br />
                <em>L&iacute;nea gratuita:</em> 01 8000 - 951010</p>-->

            <h4>Correo electr&oacute;nico:</h4>
            <p><a href="mailto:univirtual-utp@utp.edu.co">univirtual-utp@utp.edu.co</a></p>
        </div>

        <div class="col-sm-3 col-xs-6">
            <h4>Direcci&oacute;n:</h4>
            <p> Universidad Tecnol&oacute;gica de Pereira<br />
                Bloque H oficina H-513<br />
                Pereira - Colombia</p>
            
            <h4>Preguntas frecuentes:</h4>
            <p><a href="//pruebas-univirtual.utp.edu.co/portal/scripts/inicio/index.php?url=http://bit.ly/pcJFlW" target="_blank">Consulte nuestra p&aacute;gina de FAQ</a></p>
        </div>

        <div class="col-sm-3 col-xs-12 horarios">
            <h4>Horarios de atenci&oacute;n:</h4> 
            <p>Lunes a Viernes de 8:00 am a 12:00 am y de 2:00 pm a 6:00 pm - hora colombiana (GMT-05:00).</p>
        </div>
</section>

<footer id="page-footer" class="<?php echo $container; ?>">
        <div class="col-sm-4">
            <a href="http://univirtual.utp.edu.co" target="_blank">
                <img class="img-responsive logos" src="<?php echo $CFG->wwwroot; ?>/plantillas/img/logo-univirtual-utp.png" alt="Univirtual - Universidad Tecnológica de Pereira">
            </a>
        </div>

        <div class="col-sm-6">
            <strong>
                <span>Univirtual</span> <br />
                Universidad Tecnológica de Pereira <br />
            </strong>
            Todos los derechos reservados
            <p>
                <a href="javascript:void(0)" onclick="abrirTerminosLegales();">Términos Legales</a> |
                <a href="http://www.utp.edu.co/cms-utp/data/bin/UTP/web/uploads/media/comunicaciones/documentos/reglamento-estudiantil-19-jun-2012.pdf" onclick="registrarLog(1,'reglamento estudiantil','view','reglamento','Descarga Reglamento Estudiantil');" target="_blank">Reglamento Estudiantil</a> |
                <a href="http://media.utp.edu.co/vicerrectoria-academica/archivos/estatutodocente.pdf" onclick="registrarLog(1,'estatuto docente','view','http://media.utp.edu.co/vicerrectoria-academica/archivos/estatutodocente.pdf','Descarga Estatuto Docente');" target="_blank">Estatuto Docente</a>
            </p>
        </div>
    
        <div class="col-sm-2 redes">
                <a href="http://www.facebook.com/utpunivirtual" target="_blank">
                    <img src="//univirtual.utp.edu.co/portal/interfaz/imagenes/facebook.png" alt="Facebook">
                </a> 
                <a href="http://twitter.com/Univirtual" target="_blank">
                    <img src="//univirtual.utp.edu.co/portal/interfaz/imagenes/twitter.png" alt="Twitter">
                </a>
                <a href="http://www.youtube.com/univirtualutp" target="_blank">
                    <img src="//univirtual.utp.edu.co/portal/interfaz/imagenes/youtube.png" alt="Youtube">
                </a>
        </div>
            <!-- <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div> -->
            <?php echo $OUTPUT->standard_footer_html(); ?>
</footer>
