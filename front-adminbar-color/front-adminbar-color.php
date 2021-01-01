<?php
/*
  Plugin Name: Front Adminbar Color
  Plugin URI:
  Description: WEBサイト上部に表示されるAdminbarに色をつけます。
  Version: 1.0.0
  Author: Mafumafu Ayaoriko
  Author URI: https://github.com/ayaoriko/wp-plugin_front-adminbar-color.git
 */


class FrontAdminbarColor {
    function __construct() {
        add_action('admin_menu', array($this, 'add_pages'));
        add_action( 'wp_head',array($this,  'add_front_css'));
    }
    function add_pages() {
        add_menu_page('Admin Bar 設定','Admin Bar 設定',  'level_8', __FILE__, array($this,'show_text_option_page'), '', 26);
    }
    function show_text_option_page() {
        //$_POST['admin_bar_design_options'])があったら保存
        if ( isset($_POST['admin_bar_design_options'])) {
            check_admin_referer('shoptions');
            $opt = $_POST['admin_bar_design_options'];
            update_option('admin_bar_design_options', $opt);
?>
       <div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
<?php
        }
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div><h2>Admin Bar デザイン設定</h2>
    <form action="" method="post">
        <?php
        wp_nonce_field('shoptions');
        $opt = get_option('admin_bar_design_options');
        $show_bgCol = isset($opt['bgCol']) ? $opt['bgCol']: null;
        $show_fontCol = isset($opt['fontCol']) ? $opt['fontCol']: null;
        $show_styleFlag = isset($opt['styleFlag']) ? $opt['styleFlag']: 'hide';
        ?> 
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="inputtext">オリジナルの色に設定</label></th>
                <td>
                   <label>
                       <input type="radio" name="admin_bar_design_options[styleFlag]" value="show" <?php if($show_styleFlag == 'show'): ?>checked<?php endif; ?>>設定する</label>
                <label>
                    <input type="radio" name="admin_bar_design_options[styleFlag]" value="hide" <?php if($show_styleFlag == 'hide'): ?>checked<?php endif; ?>>設定しない</label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="inputtext">背景色</label></th>
                <td><input name="admin_bar_design_options[bgCol]" type="text"  value="<?php  echo $show_bgCol ?>" class="regular-text  color-picker" placeholder="例) #ccc"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="inputtext">フォント色</label></th>
                <td><input name="admin_bar_design_options[fontCol]" type="text"  value="<?php  echo $show_fontCol ?>" class="regular-text  color-picker" placeholder="例) #ccc"></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="Submit" class="button-primary" value="変更を保存" /></p>
    </form>
    <!-- /.wrap --></div>
<?php
    }
    function get_text($type = 'bg') {
        $opt = get_option('admin_bar_design_options');
        if($type == 'bg'){
            return isset($opt['bgCol']) ? $opt['bgCol']: null;
        }
        if($type == 'font'){
            return isset($opt['fontCol']) ? $opt['fontCol']: null;
        }
        if($type == 'flag'){
            return isset($opt['styleFlag']) ? $opt['styleFlag']: null;
        }
    }
    function add_front_css() {
       $setBg = $this->get_text('bg');
        $setFont = $this->get_text('font');
        $setFlag = $this->get_text('flag');
        $bg_css = '';
        $font_css = '';
        if($setFlag == 'show'){
            if($setBg){
                $bg_css .= $setBg;
            }
            if($setFont){
                $font_css .= $setFont;
            }
        }else{
            $admin_color = get_user_option( 'admin_color' );
            switch($admin_color){
                case 'ectoplasm':
                    $bg_css = '#523f6d';
                    break;
                case 'midnight':
                    $bg_css = '#363b3f;';
                    break;
                case 'ocean':
                    $bg_css = '#738e96;';
                    break;
                case 'sunrise':
                    $bg_css = '#cf4944;';
                    break;
                case 'coffee':
                    $bg_css = '#59524c';
                    break;
                case 'blue':
                    $bg_css = '#52accc';
                    break;
                case 'light':
                    $bg_css = '#e5e5e5';
                    $font_css = '#333';
                    break;
                case 'modern':
                    $bg_css = '#1e1e1e';
                    break;                    
                default:
                    $bg_css = '';
                    $font_css = '';
                    break;
            }
        }
        echo '<style>#wpadminbar{background:'.$bg_css.';}#wpadminbar .ab-empty-item, #wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon{color: '.$font_css.'}#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before{color: '.$font_css.'}</style>';
    }
}

$front_adminbar_color = new FrontAdminbarColor;
