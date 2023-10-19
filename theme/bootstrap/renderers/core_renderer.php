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

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrap
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class theme_bootstrap_core_renderer extends core_renderer {
    /*
     * This renders a notification message.
     * Uses bootstrap compatible html.
     */
    public function notification($message, $classes = 'notifyproblem') {
        $message = clean_text($message);

        if ($classes == 'notifyproblem') {
            return html_writer::div($message, 'alert alert-danger');
        }
        if ($classes == 'notifywarning') {
            return html_writer::div($message, 'alert alert-warning');
        }
        if ($classes == 'notifysuccess') {
            return html_writer::div($message, 'alert alert-success');
        }
        if ($classes == 'notifymessage') {
            return html_writer::div($message, 'alert alert-info');
        }
        if ($classes == 'redirectmessage') {
            return html_writer::div($message, 'alert alert-block alert-info');
        }
        return html_writer::div($message, $classes);
    }

     /*
     *
     * Saludo 
     */
    public function saludo() {
        $hora=date("G");  
        $saludo="";
        if($hora >5 && $hora <12){
            $saludo="Buenos días " ;
        }elseif($hora >=12 && $hora <=18){
            $saludo="Buenas tardes ";
        }elseif($hora > 18){
            $saludo="Buenas noches ";
        }elseif($hora <=5){
            $saludo="Buenas noches ";
        }
        return $saludo;
    }

    /*
     * Función que retorna la bienvenida segun el genero del perfilador getBienvenida
     * Desde la base de datos del portal
     */
    public function getBienvenida(){
        //@error_reporting(E_ALL);
        //@ini_set('display_errors', 1);
        global $USER;
        require_once("DB.php");
        $bienvenida='';
        #-------------------------------------------------------
        # Se conecta a la base de datos
        #-------------------------------------------------------
        $dsnPortal = array(
            'phptype'   => "pgsql",
            'hostspec'  => "localhost",
            'database'  => "portal",
            'port'      => "5432",
            'username'  => "portal",
            'password'  => "p0rtal"
        );
        $db = DB::connect($dsnPortal);          
        if (DB::isError($db)){
            echo "por aqui";
            exit;
        }
        #-----------------------------------------------------------------------------
        # Consulta el recomendado 
        #-----------------------------------------------------------------------------
        $sql="
SELECT g.id_genero, g.genero
FROM cognos_persona p
JOIN cognos_genero g ON (p.id_genero=g.id_genero)
WHERE p.id_usuario_moodle=".$USER->id."
                ";
               
            $result = $db->query($sql);
            if (DB::isError ($result)){
                exit;
            }
            if($fila = $result->fetchRow(DB_FETCHMODE_ASSOC)){
                if($fila['genero']=='Femenino'){
                    $bienvenida="<span class='bienvenido' >Bienvenida</span>";
                }else{
                    $bienvenida="<span class='bienvenido' >Bienvenido</span>";
                }
            }
            return $bienvenida;
    }

    //mensaje sin leer
    //Consulta el nùmero de mensajes sin leer
    public function mensajesSinLeer(){
        global $CFG, $USER, $DB;
        $userid=$USER->id;     
        //CONSULTA LOS MENSAJES POR LEER
        $sql="
SELECT COUNT(*) as mensajes
FROM mdlcampus_message m
WHERE useridto=".$userid;       
        //Ejecuta la consulta en la BD
        $result= $DB->get_records_sql($sql); 
        $mensajes_sin_leer="";
        if(!empty($result)){
            foreach($result as $res){
                //if($res->mensajes!=0){
                    $mensajes_sin_leer=$res->mensajes;
                //}
            }
        }
        return $mensajes_sin_leer;
    }
    //tiempo pasado
    public function time_ago($unix_date){
		if(empty($unix_date)) {
			return "";
		}
		$periods = array("segundo", "minuto", "hora", "dia", "semana", "mes", "año", "decada");
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = time();
		// is it future date or past date
		if($now > $unix_date) {
			$difference = $now - $unix_date;
		}else{
			$difference = $unix_date - $now;
		}
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
		$difference = round($difference);
		if($difference != 1) {
			//pluralizacion
			if($j==4){
				$periods[$j].= "es";
			}else{
				$periods[$j].= "s";
			}
		}
		return "$difference $periods[$j]";
	}

    //devuelve la fecha del primer y último acceso 
	public function getPrimerAcceso(){
		global $USER, $DB;
		$userid=$USER->id;
		$sql="
SELECT u.firstaccess
FROM mdlcampus_user u
WHERE u.id = ".$userid;
		$result= $DB->get_records_sql($sql);
		$primer_acceso=0;
		if(!empty($result)){
            foreach($result as $res){ 			
				$primer_acceso=$res->firstaccess;					
			}
		}
		return($this->time_ago($primer_acceso));
	}

	//devuelve la fecha del primer y último acceso 
	public function getUltimoAcceso(){
		global $USER, $DB;
		$userid=$USER->id;
		$sql="
SELECT u.lastaccess
FROM mdlcampus_user u
WHERE u.id = ".$userid;
		$result= $DB->get_records_sql($sql);
		$ultimo_acceso=0;
		if(!empty($result)){
            foreach($result as $res){ 			
				$ultimo_acceso=$res->lastaccess;
			}
		}
		return($this->time_ago($ultimo_acceso));
	}
	

    public function getURLSalir(){
        global $CFG, $USER;
        return "/campus_univirtual/login/logout.php?sesskey=".$USER->sesskey;
    }
     /*
     * Función que obtiene los id de los metacursos
     * Desde la base de datos del portal
     */
    protected function obtenerIdsMetacursos(){
        //include(dirname(__FILE__) . '/config.php');
        require_once("DB.php");
        $id_tipo_metacurso=1;
            #-------------------------------------------------------
            # Se conecta a la base de datos
            #-------------------------------------------------------
            $dsnPortal = array(
                'phptype'   => "pgsql",
                'hostspec'  => "localhost",
                'database'  => "portal",
                'port'      => "5432",
                'username'  => "portal",
                'password'  => "p0rtal"
            );
            $db = DB::connect($dsnPortal);          
            if (DB::isError($db)){
                exit;
            }
            #-----------------------------------------------------------------------------
            # Consulta el recomendado 
            #-----------------------------------------------------------------------------
            $sql="
select id_curso 
from categoria_tipo_curso
where id_tipo_curso=".$id_tipo_metacurso."
                ";
            $result = $db->query($sql);
            if (DB::isError ($result)){
                exit;
            }
            $arrCursos=array();
            while($fila = $result->fetchRow(DB_FETCHMODE_ASSOC)){
                #-------------------------------------------------
                # Muestra las categorias consultadas
                #-------------------------------------------------
                array_push($arrCursos,$fila['id_curso']);
            }
            return $arrCursos;
    }

    /*
     * Mis cursos
     * Lista los cursos disponibles para el usuario
     */
    public function misCursos(){
        //@error_reporting(E_ALL);
        //@ini_set('display_errors', 1);
        global $CFG, $USER, $DB;
        $rol_desmatriculado=17;
        if(isloggedin()){
            //Obtener ids de los metacursos
            $arrMetacursos = $this->obtenerIdsMetacursos();
            $idsMetacursos = implode(',',$arrMetacursos);
            //CONSULTA SQL LAS ASIGNATURAS MATRICULADAS
            $sql="
SELECT DISTINCT c.id,c.fullname,c.sortorder,ct.depth,ct.path,ct.id as categoria_padre
FROM mdlcampus_user u
JOIN mdlcampus_role_assignments a ON (a.userid=u.id)
JOIN mdlcampus_context t ON (t.id = a.contextid)
JOIN mdlcampus_course c ON (c.id =t.instanceid)
JOIN mdlcampus_course_categories ct ON (c.category=ct.id)
WHERE u.id =".$USER->id."
AND t.contextlevel= 50
AND c.visible=1
AND a.roleid <> ".$rol_desmatriculado."
AND c.id in(".$idsMetacursos.")
ORDER BY c.sortorder
"; 

            //Ejecuta la consulta en la BD
            $result= $DB->get_records_sql($sql);
                
            $cursos='<li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Cursos matriculados">
                        Espacios académicos 
                        <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
            ';
            if(empty($result)){
                $cursos.="<li class='no-tiene'>Actualmente no tiene asignaturas matriculadas</li>";
            }else{
                 foreach($result as $res){
                    $cursos.="<li><a href='/campus_univirtual/course/view.php?id=".$res->id."'>".$res->fullname."</a></li>
                              <li class='divider'></li>"; 
                }   
            }
            $cursos.=" </ul>
                    </li>";
            return $cursos;
        }
    }

    /*
     * Mis cursos Portada
     * Lista los cursos disponibles para el usuario
     */
    public function misCursosPortada(){
        //@error_reporting(E_ALL);
        //@ini_set('display_errors', 1);
        global $CFG, $USER, $DB;
        $rol_desmatriculado=17;
        if(isloggedin()){
            //Obtener ids de los metacursos
            $arrMetacursos = $this->obtenerIdsMetacursos();
            $idsMetacursos = implode(',',$arrMetacursos);
            //CONSULTA SQL LAS ASIGNATURAS MATRICULADAS
            $sql="
SELECT DISTINCT c.id,c.fullname,c.sortorder,ct.depth,ct.path,ct.id as categoria_padre
FROM mdlcampus_user u
JOIN mdlcampus_role_assignments a ON (a.userid=u.id)
JOIN mdlcampus_context t ON (t.id = a.contextid)
JOIN mdlcampus_course c ON (c.id =t.instanceid)
JOIN mdlcampus_course_categories ct ON (c.category=ct.id)
WHERE u.id =".$USER->id."
AND t.contextlevel= 50
AND c.visible=1
AND a.roleid <> ".$rol_desmatriculado."
AND c.id in(".$idsMetacursos.")
ORDER BY c.sortorder
"; 

            //Ejecuta la consulta en la BD
            $result= $DB->get_records_sql($sql);
                
            $cursos='<ul>';
            if(empty($result)){
                $cursos.="<li>Actualmente no tiene asignaturas matriculadas</li>";
            }else{
                 $primero=1;
                 foreach($result as $res){
                    if($primero==1){
                        $cursos.="<li><a href='/campus_univirtual/course/view.php?id=".$res->id."'>".$res->fullname."</a></li>"; 
                        $primero=0;
                    }else{
                        $cursos.="<li><a href='/campus_univirtual/course/view.php?id=".$res->id."'>".$res->fullname."</a></li>"; 
                    }
                }   
            }
            $cursos.=" </ul>";
            return $cursos;
        }
    }

    //Obtener foto de perfil
    public function getPicture($id,$size='f1'){
        global $CFG, $USER, $DB;
        
        if(isloggedin()){       
            $sql="
SELECT u.id as userid,u.firstname,u.lastname,c.id as contextid,u.picture
FROM mdlcampus_user u
JOIN mdlcampus_context c ON (c.instanceid=u.id and c.contextlevel=30)
WHERE u.id = ".$id;

            //Ejecuta la consulta en la BD
            $result= $DB->get_records_sql($sql);
            
            $url_foto="/campus_univirtual/theme/image.php?image=u/".$size;
            
            if(!empty($result)){
                foreach($result as $res){
                    $userid=$res->userid;
                    if($res->picture==1){
                        //$url_foto = '/campus_univirtual/pluginfile.php/'.$res->contextid.'/user/icon/'.$size;
                        $url_foto='/campus_univirtual/api/picture?id_moodle='.$id;
                    }
                }
            }
            return $url_foto;
        }
    }


    //Obtener foto de perfil
    public function getImagenLocal($id,$size='f1'){
        global $CFG, $USER, $DB;
        
        if(isloggedin()){       
            $sql="
SELECT u.id as userid,u.firstname,u.lastname,c.id as contextid,u.picture
FROM mdlcampus_user u
JOIN mdlcampus_context c ON (c.instanceid=u.id and c.contextlevel=30)
WHERE u.id = ".$id;

            //Ejecuta la consulta en la BD
            $result= $DB->get_records_sql($sql);
            
            $url_foto="/campus_univirtual/theme/image.php?image=u/".$size;
            
            if(!empty($result)){
                foreach($result as $res){
                    $userid=$res->userid;
                    if($res->picture==1){
                        $url_foto = '/campus_univirtual/pluginfile.php/'.$res->contextid.'/user/icon/'.$size;
                    }
                }
            }
            return $url_foto;
        }
    }

    //Obtener foto de perfil
    public function getUsuario($id){
        global $CFG, $USER, $DB;
        $sql="
SELECT u.id as userid,u.firstname,u.lastname,c.id as contextid
FROM mdlcampus_user u
JOIN mdlcampus_context c ON (c.instanceid=u.id and c.contextlevel=30)
WHERE u.id = ".$id;

        //Ejecuta la consulta en la BD
        $result= $DB->get_records_sql($sql);
        
        if(!empty($result)){
            foreach($result as $res){
                $fila=$res;
            }
        }
        return $fila;
    }

    /*
     * This renders the navbar.
     * Uses bootstrap compatible html.
     */
    public function navbar() {
        $breadcrumbs = '';
        foreach ($this->page->navbar->get_items() as $item) {
            $item->hideicon = true;
            $breadcrumbs .= '<li>'.$this->render($item).'</li>';
        }
        return "<ol class=breadcrumb>$breadcrumbs</ol>";
    }

    /*
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */
    public function custom_menu($custommenuitems = '') {
        global $CFG;

        if (!empty($CFG->custommenuitems)) {
            $custommenuitems .= $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG, $USER;

        // TODO: eliminate this duplicated logic, it belongs in core, not
        // here. See MDL-39565.

        $content = '<ul class="nav navbar-nav">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }

        return $content.'</ul>';
    }

    /*
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */
    public function user_menu() {
        global $CFG;
        $usermenu = new custom_menu('', current_language());
        return $this->render_user_menu($usermenu);
    }

    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_user_menu(custom_menu $menu) {
        global $CFG, $USER, $DB;

        $addusermenu = true;
        $addlangmenu = true;
        $addmessagemenu = true;

        if (!isloggedin() || isguestuser()) {
            $addmessagemenu = false;
        }

        if ($addmessagemenu) {
            $messages = $this->get_user_messages();
            $messagecount = count($messages);
            $messagemenu = $menu->add(
                $messagecount . ' ' . get_string('messages', 'message'),
                new moodle_url('#'),
                get_string('messages', 'message'),
                9999
            );
            foreach ($messages as $message) {

                $senderpicture = new user_picture($message->from);
                $senderpicture->link = false;
                $senderpicture = $this->render($senderpicture);

                $messagecontent = $senderpicture;
                $messagecontent .= html_writer::start_span('msg-body');
                $messagecontent .= html_writer::start_span('msg-title');
                $messagecontent .= html_writer::span($message->from->firstname . ': ', 'msg-sender');
                $messagecontent .= $message->text;
                $messagecontent .= html_writer::end_span();
                $messagecontent .= html_writer::start_span('msg-time');
                $messagecontent .= html_writer::tag('i', '', array('class' => 'icon-time'));
                $messagecontent .= html_writer::span($message->date);
                $messagecontent .= html_writer::end_span();

                $messageurl = new moodle_url('/message/index.php', array('user1' => $USER->id, 'user2' => $message->from->id));
                $messagemenu->add($messagecontent, $messageurl, $message->state);
            }
        }

        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
        or empty($CFG->langmenu)
        or ($this->page->course != SITEID and !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if ($addlangmenu) {
            $language = $menu->add(get_string('language'), new moodle_url('#'), get_string('language'), 10000);
            foreach ($langs as $langtype => $langname) {
                $language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        if ($addusermenu) {
            if (isloggedin()) {
                $url_imagen=$this->getPicture($USER->id,'f2');
                $img ='<img id="img_perfil_nav" width="32" class="userpicture img-circle" src="'.$url_imagen.'"/>';
                $usermenu = $menu->add($img.' '.fullname($USER), new moodle_url('#'), fullname($USER), 10001);
                $usermenu->add(
                    '<span class="glyphicon glyphicon-off"></span>' . get_string('logout'),
                    new moodle_url('/login/logout.php', array('sesskey' => sesskey(), 'alt' => 'logout')),
                    get_string('logout')
                );

                $usermenu->add(
                    '<span class="glyphicon glyphicon-user"></span>' . get_string('viewprofile'),
                    new moodle_url('/user/profile.php', array('id' => $USER->id)),
                    get_string('viewprofile')
                );

                $usermenu->add(
                    '<span class="glyphicon glyphicon-cog"></span>' . get_string('editmyprofile'),
                    new moodle_url('/user/edit.php', array('id' => $USER->id)),
                    get_string('editmyprofile')
                );
            } else {
                $usermenu = $menu->add(get_string('login'), new moodle_url('/login/index.php'), get_string('login'), 10001);
            }
        }

        $content = '<ul class="nav navbar-nav">';
        //mis cursos
        $content .= $this->misCursos();
        //demás items
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }
        $content .= '</ul>';
        
        return $content;
    }

    protected function process_user_messages() {

        $messagelist = array();

        foreach ($usermessages as $message) {
            $cleanmsg = new stdClass();
            $cleanmsg->from = fullname($message);
            $cleanmsg->msguserid = $message->id;

            $userpicture = new user_picture($message);
            $userpicture->link = false;
            $picture = $this->render($userpicture);

            $cleanmsg->text = $picture . ' ' . $cleanmsg->text;

            $messagelist[] = $cleanmsg;
        }

        return $messagelist;
    }

    protected function get_user_messages() {
        global $USER, $DB;
        $messagelist = array();
        $maxmessages = 5;

        $readmessagesql = "SELECT id, smallmessage, useridfrom, useridto, timecreated, fullmessageformat, notification
        				     FROM {message_read}
        			        WHERE useridto = :userid
        			     ORDER BY timecreated DESC
        			        LIMIT $maxmessages";
        $newmessagesql = "SELECT id, smallmessage, useridfrom, useridto, timecreated, fullmessageformat, notification
        					FROM {message}
        			       WHERE useridto = :userid";

        $readmessages = $DB->get_records_sql($readmessagesql, array('userid' => $USER->id));

        $newmessages = $DB->get_records_sql($newmessagesql, array('userid' => $USER->id));
        

        foreach ($newmessages as $message) {
            $messagelist[] = $this->bootstrap_process_message($message, 'new');
        }

        foreach ($readmessages as $message) {
            $messagelist[] = $this->bootstrap_process_message($message, 'old');
        }
        return $messagelist;

    }

    public function getUltimoMensaje() {
        global $USER, $DB;
        $maxmessages = 1;

        $readmessagesql = "SELECT id, smallmessage, useridfrom, useridto, timecreated, fullmessageformat, notification
        				     FROM {message_read}
        			        WHERE useridto = :userid
        			     ORDER BY timecreated DESC
        			        LIMIT $maxmessages";

        $newmessagesql = "SELECT id, smallmessage, useridfrom, useridto, timecreated, fullmessageformat, notification
        					FROM {message}
        			       WHERE useridto = :userid
        			       ORDER BY timecreated DESC
        			        LIMIT $maxmessages";

        $newmessages = $DB->get_records_sql($newmessagesql, array('userid' => $USER->id));

        $ultimo_mensaje=array(); 
        if(count($newmessages)>0){
        	foreach ($newmessages as $message) {
        		$ultimo_mensaje['url_imagen']=$this->getImagenLocal($message->useridfrom,'f2');
        		$usuario=$this->getUsuario($message->useridfrom);
        		$ultimo_mensaje['nombre']=$usuario->firstname.' '.$usuario->lastname;
        		$ultimo_mensaje['fecha_envio']=$message->timecreated;
        		$ultimo_mensaje['mensaje']=strip_tags($message->smallmessage);
        	}
        }else{
        	$readmessages = $DB->get_records_sql($readmessagesql, array('userid' => $USER->id));
        	foreach ($readmessages as $message) {
        		$ultimo_mensaje['url_imagen']=$this->getImagenLocal($message->useridfrom,'f2');
        		$usuario=$this->getUsuario($message->useridfrom);
        		$ultimo_mensaje['nombre']=$usuario->firstname.' '.$usuario->lastname;
        		$ultimo_mensaje['fecha_envio']=$message->timecreated;
        		$ultimo_mensaje['mensaje']=strip_tags($message->smallmessage);
			}
        }
		return $ultimo_mensaje;
	}

    protected function bootstrap_process_message($message, $state) {
        global $DB;
        $messagecontent = new stdClass();

        if ($message->notification) {
            $messagecontent->text = get_string('unreadnewnotification', 'message');
        } else {
            if ($message->fullmessageformat == FORMAT_HTML) {
                $message->smallmessage = html_to_text($message->smallmessage);
            }
            if (core_text::strlen($message->smallmessage) > 15) {
                $messagecontent->text = core_text::substr($message->smallmessage, 0, 15).'...';
            } else {
                $messagecontent->text = $message->smallmessage;
            }
        }

        if ((time() - $message->timecreated ) <= (3600 * 3)) {
            $messagecontent->date = format_time(time() - $message->timecreated);
        } else {
            $messagecontent->date = userdate($message->timecreated, get_string('strftimetime', 'langconfig'));
        }

        $messagecontent->from = $DB->get_record('user', array('id' => $message->useridfrom));
        $messagecontent->state = $state;
        return $messagecontent;
    }



    /*
     * This code renders the custom menu items for the
     * bootstrap dropdown menu.
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0 ) {
        static $submenucount = 0;

        if ($menunode->has_children()) {

            if ($level == 1) {
                $dropdowntype = 'dropdown';
            } else {
                $dropdowntype = 'dropdown-submenu';
            }

            $content = html_writer::start_tag('li', array('class' => $dropdowntype));
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }
            $linkattributes = array(
                'href' => $url,
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'title' => $menunode->get_title(),
            );
            $content .= html_writer::start_tag('a', $linkattributes);
            $content .= $menunode->get_text();
            if ($level == 1) {
                $content .= '<b class="caret"></b>';
            }
            $content .= '</a>';
            $content .= '<ul class="dropdown-menu">';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 0);
            }
            $content .= '</ul>';
        } else {
            $content = '<li>';
            // The node doesn't have children so produce a final menuitem.
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title' => $menunode->get_title()));
        }
        return $content;
    }

    /**
     * Renders tabtree
     *
     * @param tabtree $tabtree
     * @return string
     */
    protected function render_tabtree(tabtree $tabtree) {
        if (empty($tabtree->subtree)) {
            return '';
        }
        $firstrow = $secondrow = '';
        foreach ($tabtree->subtree as $tab) {
            $firstrow .= $this->render($tab);
            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== array()) {
                $secondrow = $this->tabtree($tab->subtree);
            }
        }
        return html_writer::tag('ul', $firstrow, array('class' => 'nav nav-tabs')) . $secondrow;
    }

    /**
     * Renders tabobject (part of tabtree)
     *
     * This function is called from {@link core_renderer::render_tabtree()}
     * and also it calls itself when printing the $tabobject subtree recursively.
     *
     * @param tabobject $tabobject
     * @return string HTML fragment
     */
    protected function render_tabobject(tabobject $tab) {
        if ($tab->selected or $tab->activated) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'active'));
        } else if ($tab->inactive) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'disabled'));
        } else {
            if (!($tab->link instanceof moodle_url)) {
                // Backward compatibility when link was passed as quoted string.
                $link = "<a href=\"$tab->link\" title=\"$tab->title\">$tab->text</a>";
            } else {
                $link = html_writer::link($tab->link, $tab->text, array('title' => $tab->title));
            }
            return html_writer::tag('li', $link);
        }
    }
}
