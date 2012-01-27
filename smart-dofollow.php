<?php
/*
Plugin Name: Smart DoFollow
Plugin URI: http://mydiy.pl/
Description: Smart DoFollow
Version: 1.0.2
Author: Łukasz Więcek
Author URI: http://mydiy.pl/
*/

// Język
add_action('init', 'SmartDoFollowLang'); function SmartDoFollowLang() {load_plugin_textdomain('smart-dofollow', false, dirname(plugin_basename( __FILE__ )).'/lang');}

// CSS
add_action('admin_head', 'SmartDoFollowAdminCSS');
function SmartDoFollowAdminCSS() {echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('css/wp-admin.css', __FILE__). '">';}

// Menu
add_action('admin_menu','SmartDoFollowMenu');
function SmartDoFollowMenu() {add_options_page('Smart DoFollow','Smart DoFollow', 7, __FILE__, 'SmartDoFollowSettings');}

// Pobranie ustawień
$SmartDoFollow = get_option('SmartDoFollow');

// Ustawienia
function SmartDoFollowSettings()
    {
    global $SmartDoFollow; 
    ?>
    <div id="wrap" class="wrap smartdofollow">
        <?php
        if($_POST['submit'])
            {
            $SmartDoFollow = array(
    			'count'          => $_POST['count'],
    			'in_nickname'    => $_POST['in_nickname'],
    			'in_content'	 => $_POST['in_content']);
            
            if(!get_option('SmartDoFollow'))  add_option('SmartDoFollow', $SmartDoFollow);
            else                              update_option('SmartDoFollow', $SmartDoFollow);
            }
       ?>
       <h2><?php _e('Smart DoFollow','smart-dofollow') ?></h2>
       
       <h3><?php _e('Settings','smart-dofollow') ?></h3>
       <form class="left20" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="count"><?php _e('Minimum number of chars','smart-dofollow') ?></label></th>
                    <td><input name="count" type="text" id="count" style="width: 100px;" value="<?php if(!empty($SmartDoFollow['count'])) echo $SmartDoFollow['count']; else echo '400' ?>" class="regular-text" /> <span class="description"><?php _e('The minimum length of comments that will have DoFollow links', 'smart-dofollow') ?></span></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><?php _e('Range','smart-dofollow') ?></th>
                    <td>
                        <fieldset>
                            <label for="in_nickname"><input name="in_nickname" type="checkbox" id="in_nickname" value="1"<?php if($SmartDoFollow['in_nickname']=='1') echo 'checked' ?> /> <?php _e('The author\'s signature','smart-dofollow') ?> <span class="description">(<?php _e('the link provided in the comment form','smart-dofollow') ?>)</span></label><br />
                            <label for="in_content"><input name="in_content" type="checkbox" id="in_content" value="1"<?php if($SmartDoFollow['in_content']=='1') echo 'checked' ?> /> <?php _e('Comment\'s content','smart-dofollow') ?> <span class="description">(<?php _e('links in the comment','smart-dofollow') ?>)</span></label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save','smart-dofollow') ?>"  /></p>
        </form>
        
     <h3><?php _e('My other plugins','smart-dofollow') ?></h3>
        <div class="left20">
            <ul style="margin-left: 10px;">
                <li><a href="http://wordpress.org/extend/plugins/social-slider/">Social Slider</a></li>
                <li><a href="http://wordpress.org/extend/plugins/socialfit/">Social Fit</a></li>
                <li><a href="http://commentify.info/">Commentify</a></li>
                <li><a href="http://wordpress.org/extend/plugins/thank-you/">Thank You</a></li>
            </ul>
        </div>
        
        
      <h3><?php _e('Translations','smart-dofollow') ?></h3>
        <div class="left20">
            <ul style="margin-left: 10px;">
                <li><strong><?php _e('English','smart-dofollow') ?></strong> - <a href="http://tomasz.topa.pl">Tomasz Topa</a></li>
                <li><strong><?php _e('Polish','smart-dofollow') ?></strong> - <a href="http://mydiy.pl">Łukasz Więcek</a></li>
            </ul>
        </div>
    </div>
    <?php }

// Usuwanie nofollow
if($SmartDoFollow['in_nickname']=='1')  add_filter('get_comment_author_link', 'SmartDoFollowComment');
if($SmartDoFollow['in_content']=='1')   add_filter('get_comment_text', 'SmartDoFollowComment');

function SmartDoFollowComment($c)
	{
    global $comment, $SmartDoFollow;
    if(strlen(strip_tags($comment->comment_content)) >= $SmartDoFollow['count'])
        {
        $c = str_replace('external nofollow', 'external', $c);
        $c = str_replace('nofollow', 'external', $c);
        }
    return $c;
	}