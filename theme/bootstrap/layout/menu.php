<?php 

/*
 * Función que retorna la bienvenida segun el genero del perfilador getBienvenida
 * Desde la base de datos del portal
 */
function getBienvenida(){
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
                $bienvenida="Bienvenida";
            }else{
                $bienvenida="Bienvenido";
            }
        }
        return $bienvenida;
}

/*
 * Overriding the custom_menu function ensures the custom menu is
 * always shown, even if no menu items are configured in the global
 * theme settings page.
 */
function custom_menu($custommenuitems = '') {
    global $CFG;

    if (!empty($CFG->custommenuitems)) {
        $custommenuitems .= $CFG->custommenuitems;
    }
    $custommenu = new custom_menu($custommenuitems, current_language());
    return render_custom_menu($custommenu);
}

/*
 * This renders the bootstrap top menu.
 *
 * This renderer is needed to enable the Bootstrap style navigation.
 */
function render_custom_menu(custom_menu $menu) {
    global $CFG, $USER;

    // TODO: eliminate this duplicated logic, it belongs in core, not
    // here. See MDL-39565.

    $content = '<ul class="nav navbar-nav">';
    foreach ($menu->get_children() as $item) {
        $content .= render_custom_menu_item($item, 1);
    }

    return $content.'</ul>';
}

/*
 * Overriding the custom_menu function ensures the custom menu is
 * always shown, even if no menu items are configured in the global
 * theme settings page.
 */
function user_menu() {
    global $CFG;
    $usermenu = new custom_menu('', current_language());
    return render_user_menu($usermenu);
}

/*
 * This renders the bootstrap top menu.
 *
 * This renderer is needed to enable the Bootstrap style navigation.
 */
function render_user_menu(custom_menu $menu) {
    global $CFG, $USER, $DB;

    $addusermenu = true;
    $addlangmenu = true;
    $addmessagemenu = true;

    if (!isloggedin() || isguestuser()) {
        $addmessagemenu = false;
    }

    if ($addmessagemenu) {
        $messages = get_user_messages();
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
            $senderpicture = render($senderpicture);

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
    ) {
        $addlangmenu = false;
    }


    if ($addusermenu) {
        if (isloggedin()) {
            $url_imagen=getPicture($USER->id);
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

    $content = '<ul class="nav navbar-nav navbar-right">';
    //mis cursos
    $content .= misCursos();
    //demás items
    foreach ($menu->get_children() as $item) {
        $content .= render_custom_menu_item($item, 1);
    }
    $content .= '</ul>';
    
    return $content;
}

function process_user_messages() {

    $messagelist = array();

    foreach ($usermessages as $message) {
        $cleanmsg = new stdClass();
        $cleanmsg->from = fullname($message);
        $cleanmsg->msguserid = $message->id;

        $userpicture = new user_picture($message);
        $userpicture->link = false;
        $picture = render($userpicture);

        $cleanmsg->text = $picture . ' ' . $cleanmsg->text;

        $messagelist[] = $cleanmsg;
    }

    return $messagelist;
}

function get_user_messages() {
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
        $messagelist[] = bootstrap_process_message($message, 'new');
    }

    foreach ($readmessages as $message) {
        $messagelist[] = bootstrap_process_message($message, 'old');
    }
    return $messagelist;

}

function bootstrap_process_message($message, $state) {
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
function render_custom_menu_item(custom_menu_item $menunode, $level = 0 ) {
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
            $content .= render_custom_menu_item($menunode, 0);
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

?>
<nav id="menu-perfil">
    <div role="navigation" class="navbar navbar-fixed-top navbar-default">
        <div class="<?php echo $container; ?>">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#moodle-navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <a class="navbar-brand" href="<?php echo $CFG->wwwroot;?>"><?php echo getBienvenida(); ?> al <?php echo $SITE->shortname; ?></a>
        </div>

        <div id="moodle-navbar" class="navbar-collapse collapse">
             
            <?php echo custom_menu(); ?>
            <?php echo user_menu(); ?>
            <ul class="nav pull-right">
                <li><?php echo page_heading_menu(); ?></li>
            </ul>
        </div>
        </div>
    </div>
</nav>