<?php
// This file is part of The Bootstrap 3 Moodle theme
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$knownregionpre = $PAGE->blocks->is_known_region('side-pre');
$knownregionpost = $PAGE->blocks->is_known_region('side-post');



$regions = bootstrap_grid($hassidepre, $hassidepost);
$PAGE->set_popup_notification_allowed(false);
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('bootstrap', 'theme_bootstrap');

$fluid = (!empty($PAGE->layout_options['fluid']));
$container = 'container';
if (isset($PAGE->theme->settings->fluidwidth) && ($PAGE->theme->settings->fluidwidth == true)) {
    $container = 'container-fluid';
}
if ($fluid) {
    $container = 'container-fluid';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <link href="<?php echo $CFG->wwwroot; ?>/theme/bootstrap/style/campus_univirtual.css" media="all" type="text/css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/plantillas/prefixfree.min.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/plantillas/shortcut.min.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/plantillas/utilidades.min.js"> </script>
    <script type="text/javascript">
        $(document).on('ready', function() {
           $(document).on('scroll', function() {
               var alto_mensajes = $('#id_mensajes_perfil').height();
               //console.log('alto_mensajes ' + alto_mensajes);
               var y_mensajes = $('#id_mensajes_perfil').offset().top - 2 * alto_mensajes;
               //console.log('y_mensajes ' + y_mensajes);
               var y_scroll = $(document).scrollTop();
               //console.log('y_scroll ' + y_scroll);
               if (y_scroll < y_mensajes) {
                   //ocultar
                   if ($("#opciones-menu").css('display') == 'block') {
                           $("#opciones-menu").css({
                               opacity: '1',
                           });
                           $("#opciones-menu").animate({
                               opacity: "0",
                           }, 500);
                           $('#opciones-menu').addClass('hidden');
                       }
                   //console.log('agregando clase hidden');
               } else {
                   //mostrar
                   if ($("#opciones-menu").css('display') == 'none') {
                       $('#opciones-menu').removeClass('hidden');
                       $("#opciones-menu").css({
                           opacity: '0',
                       });
                       $("#opciones-menu").animate({
                           opacity: "1",
                       }, 500);
           }
           //console.log('quitando clase hidden');
       }
   });
});
    </script>
    <!-- <script type="text/javascript">
        var __lc = {};
        __lc.license = 1139351;

        (function() {
          var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
          lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
        })();
    </script> -->
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>
	<?php echo $OUTPUT->standard_top_of_body_html() ?>

    <nav id="menu-perfil" role="navigation" class="<?php echo $container; ?> navbar navbar-default navbar-fixed-top">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#moodle-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?php echo $CFG->wwwroot;?>" class="navbar-brand">Campus Univirtual</a>
            <button class="pull-right boton-salir-xs" onclick="document.location.href='<?php echo $OUTPUT->getURLSalir(); ?>'">
                Salir</button>
        </div>

        <div id="moodle-navbar" class="collapse navbar-collapse">
            <ul  id="opciones-menu" class="nav navbar-nav hidden">
                <li>
                    <a href="/campus_univirtual/user/profile.php?id=<?php echo $USER->id; ?>"> 
                        <?php echo $OUTPUT->getBienvenida() ?>
                        <img id="foto_nav" src="<?php echo $OUTPUT->getPicture($USER->id,'f2'); ?>" />
                        <?php echo $USER->firstname." ".$USER->lastname; ?> 
                    </a>
                </li>
                <?php echo $this->misCursos(); ?>
                <li>
                    <a href="/campus_univirtual/message/index.php"> 
                        Mensajes 
                    <span class="alerta_mensaje">
                        <?php echo $OUTPUT->mensajesSinLeer(); ?>
                    </span>
                   </a>
               </li>
            </ul>
            <button class="pull-right hidden-xs boton-salir" onclick="document.location.href='<?php echo $OUTPUT->getURLSalir(); ?>'">
                Salir</button>  
        </div>
    </nav>

<div id="page" class="<?php echo $container; ?>">

    <header class="moodleheader <?php echo $container; ?>">
        <div class="col-xs-8">
            <img src="<?php echo $CFG->wwwroot; ?>/plantillas/img/cabezote-campus.png" alt="Campus Univirtual" class="img-responsive">               
        </div>
        <div class="col-xs-4">
            <img src="<?php echo $CFG->wwwroot; ?>/plantillas/img/escudo-utp.png" alt="Universidad Tecnológica de Pereira" class="img-responsive pull-right"> 
        </div>
    </header>

    <?php 
            if(isloggedin()){  
        ?>

<!--  BLOQUE PERFIL PORTADA UNIVIRTUAL -->
    <section id="perfil-inicio" class="<?php echo $container; ?>">
	        <h2><?php echo $OUTPUT->getBienvenida(); ?> al campus Univirtual</h2>

	        <div class="col-sm-4 col-xs-12">
                <a href="/campus_univirtual/user/edit.php?id=<?php echo $USER->id; ?>">
                    <img class="userpicture" src="<?php echo $OUTPUT->getPicture($USER->id,'f1');?>" alt="Foto <?php echo $USER->firstname." ".$USER->lastname; ?>"/>
                </a>
                <div class="usuario">
                    <p><a href="/campus_univirtual/user/edit.php?id=<?php echo $USER->id; ?>">
                    <?php echo $USER->firstname." ".$USER->lastname; ?>
                    </a></p>
                    <p><span class="mensajes-nuevos">
                        <a id="id_mensajes_perfil" href="/campus_univirtual/message/index.php">
                            Mensajes nuevos
                        </a>
                        <span class="alerta_mensaje">
                            <?php echo $OUTPUT->mensajesSinLeer(); ?>
                        </span>
                    </span></p>
                </div>
            </div>

	        <div class="col-sm-4 col-xs-12">
	            <h4>Espacios Académicos</h4>
                <?php echo $OUTPUT->misCursosPortada(); ?>
	        </div>

	        <div class="col-sm-4 col-xs-12 ultimo-mensaje">
                <h4>Último mensaje</h4>
	            <div class="mensaje">
	            	<?php $ultimo_mensaje = $OUTPUT->getUltimoMensaje(); 
	            		if(!empty($ultimo_mensaje)){
	            	?>
                        <div class="nombre"><?php echo $ultimo_mensaje['nombre']; ?></div>
						<div class="fecha"><?php echo ucfirst(strftime("%d de %B de %Y %k:%M",$ultimo_mensaje['fecha_envio'])); ?></div>
        				<?php echo $ultimo_mensaje['mensaje']; ?>
        			<?php } 
                    else{ echo "Aún no tienes mensajes sin leer"; }  ?>
	            </div>
	        </div>
	</section>

<!--  FIN BLOQUE PERFIL PORTADA UNIVIRTUAL -->
    <?php }//fin del if esta logeado ?>

    <section id="page-content" class="<?php echo $container; ?>">
        <div id="region-main" class="<?php echo $regions['content']; ?>">
            <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            echo $OUTPUT->course_content_footer();
            ?>
        </div>

        <?php
        if ($knownregionpre) {
            echo $OUTPUT->blocks('side-pre', $regions['pre']);
        }?>
        <?php
        if ($knownregionpost) {
            echo $OUTPUT->blocks('side-post', $regions['post']);
        }?>
    </section>

   <?php include_once("footer.php"); ?>
   <?php echo $OUTPUT->standard_end_of_body_html() ?>

</div>
</body>
</html>
