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
 * @package   theme_mb2mcl
 * @copyright 2020 - 2022 Mariusz Boloz (https://mb2themes.com)
 * @license   Commercial https://themeforest.net/licenses
 *
 */

defined('MOODLE_INTERNAL') || die();

$sitemenu = theme_mb2mcl_theme_setting($PAGE,'sitemnu');



if ( $sitemenu && isloggedin() && ! isguestuser() ) : ?>
<div id="site-menu">
	<div class="container-fluid">
		<!-- ajuste template alex -->
		<div class="row course-nav justify-content-center">
				<?php echo theme_mb2mcl_get_list_of_sections() ?>
		</div>
		<!-- fin ajuste template alex -->

		<div class="row">
			<div class="col-md-12" style="min-height:0;">
            	<?php echo theme_mb2mcl_site_menu(); ?>
            </div>
        </div>

    </div>
</div>
<a href="#" class="show-sitemenu"></a>
<?php endif; ?>
