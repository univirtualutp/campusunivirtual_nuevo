<?php
// This file is part of Moodle - http://moodle.org/
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
 *
 * @package   theme_mb2mclmain
 * @copyright 2020 - 2022 Mariusz Boloz (https://mb2themes.com)
 * @license   Commercial https://themeforest.net/licenses
 *
 */

defined('MOODLE_INTERNAL') || die();

$cls = ! theme_mb2mclmain_is_footer_columns() ? ' nofooter' : '';

?>
<?php if ( theme_mb2mclmain_theme_setting( $PAGE, 'btninfo' ) ) : ?>
<div id="footer-info" class="<?php echo $cls; ?>">
	<div class="container-fluid">
	   	<div class="row">
	       	<div class="col-md-12">
	       		<?php if ( theme_mb2mclmain_theme_setting( $PAGE, 'btninfo' ) ) : ?>
	             	<div class="footer-info-content">
	                	<?php echo theme_mb2mclmain_static_content( theme_mb2mclmain_theme_setting( $PAGE, 'btninfo' ), array( 'first' => get_string( 'footerinfotitle', 'theme_mb2mclmain' ) . ':' ) ); ?>
	             	</div>
	    		<?php endif; ?>
	         </div>
	 	</div>
	</div>
</div>
<?php endif; ?>
