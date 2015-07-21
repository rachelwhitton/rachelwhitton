<?php

function advent_options_init() {
    register_setting('advent_options', 'advent_theme_options', 'advent_options_validate');
}

add_action('admin_init', 'advent_options_init');

function advent_options_validate($input) {
    $input['logo'] = advent_image_validation(esc_url_raw($input['logo']));
    $input['favicon'] = advent_image_validation(esc_url_raw($input['favicon']));
    $input['footertext'] = sanitize_text_field($input['footertext']);

    $input['topheading'] = sanitize_text_field($input['topheading']);
    $input['headertop-logo'] = advent_image_validation(esc_url_raw($input['headertop-logo']));
    $input['headertop-img'] = advent_image_validation(esc_url_raw($input['headertop-img']));
    $input['headertop-bg'] = advent_image_validation(esc_url_raw($input['headertop-bg']));

    $input['home-title'] = sanitize_text_field($input['home-title']);
    $input['home-content'] = sanitize_text_field($input['home-content']);

    $input['howitwork'] = sanitize_text_field($input['howitwork']);
    $input['howitworktitle'] = sanitize_text_field($input['howitworktitle']);
    $input['howitworkdesc'] = sanitize_text_field($input['howitworkdesc']);
    $input['howitwork-img'] = advent_image_validation(esc_url_raw($input['howitwork-img']));

    $input['post-title'] = sanitize_text_field($input['post-title']);

    $input['email'] = sanitize_email($input['email']);
    $input['facebook'] = esc_url_raw($input['facebook']);
    $input['twitter'] = esc_url_raw($input['twitter']);
    $input['pinterest'] = esc_url_raw($input['pinterest']);

    for ($advent_section_i = 1; $advent_section_i <= 6; $advent_section_i++):
        $input['section-title-' . $advent_section_i] = sanitize_text_field($input['section-title-' . $advent_section_i]);
        $input['section-content-' . $advent_section_i] = sanitize_text_field($input['section-content-' . $advent_section_i]);
    endfor;
    return $input;
}

/* Validation for uploaded image */

function advent_image_validation($advent_imge_url) {
    $advent_filetype = wp_check_filetype($advent_imge_url);

    $advent_supported_image = array('gif', 'jpg', 'jpeg', 'png', 'ico');

    if (in_array($advent_filetype['ext'], $advent_supported_image)) {
        return $advent_imge_url;
    } else {
        return '';
    }
}

function advent_framework_load_scripts($hook) {
	if($GLOBALS['advent_menu'] == $hook){
    wp_enqueue_media();
    wp_enqueue_style('advent_themeoptions_framework', get_template_directory_uri() . '/theme-options/css/themeoptions_framework.css', false, '1.0.0');
    // Enqueue custom option panel JS
    wp_enqueue_script('advent-options-custom', get_template_directory_uri() . '/theme-options/js/themeoptions-custom.js', array('jquery'));
    wp_enqueue_script('advent-media-uploader', get_template_directory_uri() . '/theme-options/js/media-uploader.js', array('jquery'));
	}
}

function advent_framework_menu_settings() {
    $advent_menu = array(
        'page_title' => __('Theme Options', 'advent'),
        'menu_title' => __('Theme Options', 'advent'),
        'capability' => 'edit_theme_options',
        'menu_slug' => 'themeoptions_framework',
        'callback' => 'advent_framework_page'
    );
    return apply_filters('advent_framework_menu', $advent_menu);
}

add_action('admin_menu', 'advent_add_page');
function advent_add_page() {
    $advent_menu = advent_framework_menu_settings();
    $GLOBALS['advent_menu']=add_theme_page($advent_menu['page_title'], $advent_menu['menu_title'], $advent_menu['capability'], $advent_menu['menu_slug'], $advent_menu['callback']);
    add_action( 'admin_enqueue_scripts', 'advent_framework_load_scripts' );
}

function advent_framework_page() {
    global $select_options;
    if (!isset($_REQUEST['settings-updated']))
        $_REQUEST['settings-updated'] = false;
    ?>
    <div class="themeoptions-themes">
        <form method="post" action="options.php" id="form-option" class="theme_option_ft">
            <div class="themeoptions-header">
                <div class="logo">
    <?php
    $advent_image = get_template_directory_uri() . '/theme-options/images/dashboard-logo.png';
    echo "<a href='http://fruitthemes.com/' target='_blank'><img src='" . $advent_image . "' alt='" . __('FruitThemes', 'advent') . "' /></a>";
    ?>
                </div>
                <div class="header-right">
    <?php
    echo "<div class='btn-save'><input type='submit' class='button-primary' value='" . __('Save Options', 'advent') . "' /></div>";
    ?>
                </div>
            </div>
            <div class="themeoptions-details">
                <div class="themeoptions-options">
                    <div class="right-box">
                        <div class="nav-tab-wrapper">
                            <div class="option-title">
                                <h2><?php _e('theme options', 'advent') ?> </h2>
                            </div>
                            <ul>
                                <li><a id="options-group-1-tab" class="nav-tab basicsettings-tab" title="<?php _e('Basic Settings', 'advent'); ?>" href="#options-group-1"><?php _e('Basic Settings', 'advent'); ?></a></li>
                                <li><a id="options-group-2-tab" class="nav-tab homepagesettings-tab" title="<?php _e('Home Page Settings', 'advent'); ?>" href="#options-group-2"><?php _e('Home Page Settings', 'advent'); ?></a></li>
                                <li><a id="options-group-3-tab" class="nav-tab socialsettings-tab" title="<?php _e('Social Settings', 'advent'); ?>" href="#options-group-3"><?php _e('Social Settings', 'advent'); ?></a></li>
                                <li><a id="options-group-4-tab" class="nav-tab profeatures-tab" title="<?php _e('PRO Theme Features','advent');?>" href="#options-group-4"><?php _e('PRO Theme Features','advent');?></a></li> 
                            </ul>
                        </div>
                    </div>
                    <div class="right-box-bg"></div>
                    <div class="postbox left-box"> 
                        <!--======================== F I N A L - - T H E M E - - O P T I O N ===================-->
    <?php
    settings_fields('advent_options');
    $advent_options = get_option('advent_theme_options');
    ?>

				<!-------------- Header group ----------------->
				<div id="options-group-1" class="group themeoptions-tabs">   
					<div class="section theme-tabs theme-logo">
						<a class="heading themeoptions-inner-tab active" href="javascript:void(0)"><?php _e('Site Logo (Recommended Size : 135px * 33px)', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group active">
							<div class="ft-control">
								<input id="logo-img" class="upload" type="text" name="advent_theme_options[logo]" 
									   value="<?php if (!empty($advent_options['logo'])) {
echo esc_attr($advent_options['logo']);
} ?>" placeholder="<?php _e('No file chosen', 'advent'); ?>" />
								<input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload', 'advent'); ?>" />
								<div class="screenshot" id="logo-image">
									<?php if (!empty($advent_options['logo'])) {
										echo "<img src='" . esc_url($advent_options['logo']) . "' /><a class='remove-image'></a>";
									} ?>
								</div>
							</div>
						</div>
					</div>
					<div class="section theme-tabs theme-favicon">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Favicon (Recommended Size : 32px * 32px)', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="explain"><?php _e('Size of favicon should be exactly 32x32px for best results.', 'advent'); ?></div>
							<div class="ft-control">
								<input id="favicon-img" class="upload" type="text" name="advent_theme_options[favicon]" 
									   value="<?php if (!empty($advent_options['favicon'])) {
										echo esc_attr($advent_options['favicon']);
									} ?>" placeholder="<?php _e('No file chosen', 'advent'); ?>" />
								<input id="upload_image_button1" class="upload-button button" type="button" value="<?php _e('Upload', 'advent'); ?>" />
								<div class="screenshot" id="favicon-image">
<?php if (!empty($advent_options['favicon'])) {
echo "<img src='" . esc_url($advent_options['favicon']) . "' /><a class='remove-image'></a>";
} ?>
								</div>
							</div>
						</div>
					</div>     
					<div id="section-footertext" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Copyright Text', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Some text regarding copyright of your site, you would like to display in the footer.', 'advent'); ?></div>                
								<input maxlength="300" type="text" id="footertext" class="of-input" name="advent_theme_options[footertext]" size="32"  value="<?php if (!empty($advent_options['footertext'])) {
echo esc_attr($advent_options['footertext']);
} ?>">
							</div>                
						</div>
					</div>
				</div>          
				<!-------------- Home Page group ----------------->
				<div id="options-group-2" class="group themeoptions-tabs">
					<h3><?php _e('Top Header', 'advent'); ?></h3>
					<div id="top-heading" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Heading', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter home page heading for your top header, you would like to display in the Home Page.', 'advent'); ?></div>                
								<input maxlength="40" id="topheading" class="of-input" name="advent_theme_options[topheading]"  type="text" size="50" value="<?php if (!empty($advent_options['topheading'])) {
echo esc_attr($advent_options['topheading']);
} ?>" />
							</div>                
						</div>
					</div>
					<div id="headertop-logo" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Top Logo (Recommended Size : 155px * 155px)', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<input id="headertop-logo" class="upload" type="text" name="advent_theme_options[headertop-logo]" 
									   value="<?php if (!empty($advent_options['headertop-logo'])) {
echo esc_attr($advent_options['headertop-logo']);
} ?>" placeholder="<?php _e('No file chosen', 'advent'); ?>" />
								<input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload', 'advent'); ?>" />
								<div class="screenshot" id="headertop-logo">
<?php if (!empty($advent_options['headertop-logo'])) {
echo "<img src='" . esc_url($advent_options['headertop-logo']) . "' /><a class='remove-image'></a>";
} ?>
								</div>
							</div>
						</div>
					</div>
					<div id="headertop-img" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Image (Recommended Size : 500px * 616px)', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<input id="headertop-img" class="upload" type="text" name="advent_theme_options[headertop-img]" 
									   value="<?php if (!empty($advent_options['headertop-img'])) {
echo esc_attr($advent_options['headertop-img']);
} ?>" placeholder="<?php _e('No file chosen', 'advent'); ?>" />
								<input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload', 'advent'); ?>" />
								<div class="screenshot" id="headertop-img">
<?php if (!empty($advent_options['headertop-img'])) {
echo "<img src='" . esc_url($advent_options['headertop-img']) . "' /><a class='remove-image'></a>";
} ?>
								</div>
							</div>
						</div>
					</div>




					<div id="headertop-bg" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Backgroung Image (Recommended Size : 1350px * 667px)', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<input id="headertop-bg" class="upload" type="text" name="advent_theme_options[headertop-bg]" 
									   value="<?php if (!empty($advent_options['headertop-bg'])) {	
												echo esc_attr($advent_options['headertop-bg']);
								} ?>" placeholder="<?php _e('No file chosen', 'advent'); ?>" />
								<input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload', 'advent'); ?>" />
								<div class="screenshot" id="headertop-bg">
<?php if (!empty($advent_options['headertop-bg'])) {
echo "<img src='" . esc_url($advent_options['headertop-bg']) . "' /><a class='remove-image'></a>";
} ?>
								</div>
							</div>
						</div>
					</div>
					<h3><?php _e('Title Bar', 'advent'); ?></h3>
					<div id="section-title" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Title', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter home page title for your site , you would like to display in the Home Page.', 'advent'); ?></div>                
								<input maxlength="100" id="title" class="of-input" name="advent_theme_options[home-title]"  type="text" size="50" value="<?php if (!empty($advent_options['home-title'])) {
echo esc_attr($advent_options['home-title']);
} ?>" />
							</div>                
						</div>
					</div>
					<div class="section theme-tabs theme-short_description">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Short Description', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter home content for your site , you would like to display in the Home Page.', 'advent'); ?></div>
								<textarea maxlength="600" name="advent_theme_options[home-content]" rows="6" id="home-content1" class="of-input"><?php if (!empty($advent_options['home-content'])) {
echo esc_attr($advent_options['home-content']);
} ?></textarea>
							</div>                
						</div>
					</div>
					<h3><?php _e('Features', 'advent'); ?></h3>
<?php for ($advent_section_i = 1; $advent_section_i <= 6; $advent_section_i++): ?>
						<div class="section theme-tabs theme-slider-img">
							<a class="heading themeoptions-inner-tab" href="javascript:void(0)">Tab <?php echo $advent_section_i; ?></a>
							<div class="themeoptions-inner-tab-group">
								<div class="ft-control">
									<div class="explain"><?php _e('Enter any font-awesome icon name here. i.e. ', 'advent');
echo 'fa-inr *'; ?></div>
									<input type="text"   placeholder="<?php _e('Enter icon class i.e. ', 'advent');
echo 'fa-inr'; ?>" id="faicon-<?php echo $advent_section_i; ?>" class="of-input" name="advent_theme_options[faicon-<?php echo $advent_section_i; ?>]" size="32"  value="<?php if (!empty($advent_options['faicon-' . $advent_section_i])) {
	echo esc_attr($advent_options['faicon-' . $advent_section_i]);
} ?>">
									<?php $link_font = 'http://fortawesome.github.io/Font-Awesome/icons/'; ?>
									<a href="<?php echo $link_font; ?>" target="_blank"><?php _e('View all icons', 'advent'); ?></a>
								</div>
								<div class="ft-control">
									<div class="explain"><?php _e('Enter secion title for your home template , you would like to display in the Home Page.', 'advent');
echo '*'; ?></div>
									<input type="text" maxlength="50"   placeholder="<?php _e('Enter title here', 'advent'); ?>" id="title-<?php echo $advent_section_i; ?>" class="of-input" name="advent_theme_options[section-title-<?php echo $advent_section_i; ?>]" size="32"  value="<?php if (!empty($advent_options['section-title-' . $advent_section_i])) {
	echo esc_attr($advent_options['section-title-' . $advent_section_i]);
} ?>">
								</div>
								<div class="ft-control">
									<div class="explain"><?php _e('Enter section content for home template , you would like to display in the Home Page.', 'advent'); ?></div>
									<textarea maxlength="200"  name="advent_theme_options[section-content-<?php echo $advent_section_i; ?>]" rows="6" id="content-<?php echo $advent_section_i; ?>" placeholder="<?php _e('Enter Content here', 'advent'); ?>" class="of-input"><?php if (!empty($advent_options['section-content-' . $advent_section_i])) {
	echo esc_attr($advent_options['section-content-' . $advent_section_i]);
} ?></textarea>
								</div>                              
							</div>
						</div>
<?php endfor; ?>
					<h3><?php _e('How it work', 'advent'); ?></h3>
					<div id="howitwork-title" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Title', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter section title for your site , you would like to display in the Home Page.', 'advent'); ?></div>                
								<input maxlength="100" id="howitwork" class="of-input" name="advent_theme_options[howitwork]" type="text" size="50" value="<?php if (!empty($advent_options['howitwork'])) {
echo esc_attr($advent_options['howitwork']);
} ?>" />
							</div>                
						</div>
					</div>
					<div id="howitwork-heading" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Heading', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter section heading for your site , you would like to display in the Home Page.', 'advent'); ?></div>                
								<input maxlength="70" id="howitworktitle" class="of-input" name="advent_theme_options[howitworktitle]" type="text" size="50" value="<?php if (!empty($advent_options['howitworktitle'])) {
									echo esc_attr($advent_options['howitworktitle']);
								} ?>" />
							</div>                
						</div>
					</div>
					<div id="howitwork-desc" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Content', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter section content title for your site , you would like to display in the Home Page.', 'advent'); ?></div>                
								<textarea maxlength="1000" name="advent_theme_options[howitworkdesc]" rows="6" id="howitworkdesc" class="of-input"><?php if (!empty($advent_options['howitworkdesc'])) {
									echo esc_attr($advent_options['howitworkdesc']);
								} ?></textarea>
							</div>                
						</div>
					</div>
					<div id="howitwork-img" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Image (Recommended Size : 362px * 400px)', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<input id="howitwork-img" class="upload" type="text" name="advent_theme_options[howitwork-img]" 
									   value="<?php if (!empty($advent_options['howitwork-img'])) {
									echo esc_attr($advent_options['howitwork-img']);
								} ?>" placeholder="<?php _e('No file chosen', 'advent'); ?>" />
								<input id="upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload', 'advent'); ?>" />
								<div class="screenshot" id="howitwork-img">
<?php if (!empty($advent_options['howitwork-img'])) {
echo "<img src='" . esc_url($advent_options['howitwork-img']) . "' /><a class='remove-image'></a>";
} ?>
								</div>
							</div>
						</div>
					</div>
					<h3><?php _e('Recent Post', 'advent'); ?></h3>
					<div id="section-recent-title" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Recent Post Title', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Enter recent post title for your site , you would like to display in the Home Page.', 'advent'); ?></div>                
								<input maxlength="40" id="post" class="of-input" name="advent_theme_options[post-title]" type="text" size="50" value="<?php if (!empty($advent_options['post-title'])) {
echo esc_attr($advent_options['post-title']);
} ?>" />
							</div>                
						</div>
					</div>
					<div class="section theme-tabs theme-short_description">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Category', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<select name="advent_theme_options[post-category]" id="category">
									<option value=""><?php echo esc_attr(__('Select Category', 'advent')); ?></option>
<?php
$advent_args = array(
'meta_query' => array(
	array(
		'key' => '_thumbnail_id',
		'compare' => 'EXISTS'
	),
)
);
$advent_post = new WP_Query($advent_args);
$advent_cat_id = array();
while ($advent_post->have_posts()) {
$advent_post->the_post();
$advent_post_categories = wp_get_post_categories(get_the_id());
foreach ($advent_post_categories as $advent_post_category)
	$advent_cat_id[] = $advent_post_category;
}
$advent_cat_id = array_unique($advent_cat_id);
$advent_args = array(
'orderby' => 'name',
'parent' => 0,
'include' => $advent_cat_id
);
$advent_categories = get_categories($advent_args);
foreach ($advent_categories as $advent_category) {
if ($advent_category->term_id == $advent_options['post-category'])
	$advent_selected = "selected=selected";
else
	$advent_selected = '';
$advent_option = '<option value="' . $advent_category->term_id . '" ' . $advent_selected . '>';
$advent_option .= $advent_category->cat_name;
$advent_option .= '</option>';
echo $advent_option;
}
?>
								</select>
							</div>                
						</div>
					</div>
				</div>    
				<!-------------- Social group ----------------->
				<div id="options-group-3" class="group themeoptions-tabs">
					<div id="section-email" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Email', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group  active">
							<div class="ft-control">
								<div class="explain"><?php _e('Send email i.e. ', 'advent'); ?>name@gmail.com</div>                
								<input id="email" class="of-input" name="advent_theme_options[email]" type="text" size="30" value="<?php if (!empty($advent_options['email'])) {
echo esc_attr($advent_options['email']);
} ?>" />
							</div>                
						</div>
					</div>            
					<div id="section-facebook" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab active" href="javascript:void(0)"><?php _e('Facebook', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Facebook profile or page URL i.e. ', 'advent'); ?>http://facebook.com/username/ </div>                
								<input id="facebook" class="of-input" name="advent_theme_options[facebook]" size="30" type="text" value="<?php if (!empty($advent_options['facebook'])) {
echo esc_attr($advent_options['facebook']);
} ?>" />
							</div>                
						</div>
					</div>
					<div id="section-twitter" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Twitter', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Twitter profile or page URL i.e. ', 'advent'); ?>http://www.twitter.com/username/</div>                
								<input id="twitter" class="of-input" name="advent_theme_options[twitter]" type="text" size="30" value="<?php if (!empty($advent_options['twitter'])) {
echo esc_attr($advent_options['twitter']);
} ?>" />
							</div>                
						</div>
					</div>
					<div id="section-pinterest" class="section theme-tabs">
						<a class="heading themeoptions-inner-tab" href="javascript:void(0)"><?php _e('Pinterest', 'advent'); ?></a>
						<div class="themeoptions-inner-tab-group">
							<div class="ft-control">
								<div class="explain"><?php _e('Pinterest profile or page URL i.e.', 'advent'); ?> https://pinterest.com/username/</div>                
								<input id="pinterest" class="of-input" name="advent_theme_options[pinterest]" type="text" size="30" value="<?php if (!empty($advent_options['pinterest'])) {
echo esc_attr($advent_options['pinterest']);
} ?>" />
							</div>                
						</div>
					</div>
				</div>

				<div id="options-group-4" class="group theme-option-inner-tabs advent-pro-image">  
					<div class="advent-pro-header">
					  <img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/advent_logopro_features.png" class="advent-pro-logo" />
					  <a href="http://fruitthemes.com/wordpress-themes/advent" target="_blank">
							<img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/advent-buy-now.png" class="advent-pro-buynow" /></a>
					  </div>
					<img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/advent_pro_features.png" />
				  </div> 
						
                        <!--======================== F I N A L - - T H E M E - - O P T I O N S ===================--> 
                    </div>
                </div>
            </div>
            <div class="themeoptions-footer">
                <ul>
                    <li class="btn-save"><input type="submit" class="button-primary" value="<?php _e('Save Options', 'advent'); ?>" /></li>
                </ul>
            </div>
        </form>    
    </div>

<?php } ?>
