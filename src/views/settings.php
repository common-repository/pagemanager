<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 16.08.17
 * Time: 12:02
 */
?>
<div class="wrap">
    <div style="background-color: #FFFFFF; padding-left: 10px;padding-top: 5px;">
        <span style="float:right;padding: 0px 5px;">v<?php echo PAGEMANAGER_PLUGIN_VERSION;?></span>
        <img src="<?php echo PAGEMANAGER_PLUGIN_URL.'images/pagemanager_logo_greenblue.png'; ?>" style="height: 70px;">
    </div>
    <h2 class="nav-tab-wrapper printful-tabs" style="background-color: #FFFFFF;">
        <a href="?page=pagemanager" class="nav-tab">Pagemanager</a>
        <a href="?page=settings_pagemanager" class="nav-tab  nav-tab-active">Settings</a>
    </h2>
    <h1><?php echo __('Settings') ?> > <?php echo __('Page Manager Settings') ?></h1>
    <?php if (!isset($settings['pagetype']) && isset($errormessage)){ ?>
    <div class="update-nag notice notice-error inline"><?php echo esc_html($errormessage); ?></div>
    <?php } ?>

    <section id="welcome">
        <div class="wp-block-columns">
            <div class="wp-block-column">
                <span id="more-30"></span>
                <p class="has-medium-font-size">First steps:</p>
                <ol class="has-medium-font-size">
                    <li>Setup the PageManager initially. <a href="https://wp-pagemanager.com/docs/getting-started/how-to-setup-the-pagemanager-plugin-initially/" target="_blank" rel="noreferrer noopener">Learn more</a></li>
                    <li>Create your first Page Blocks. <a href="https://wp-pagemanager.com/docs/getting-started/how-to-setup-first-page-blocks/" target="_blank">Watch our tutorial video.</a></li>
                    <li>Use the WP Customizer to improve the styling of your pageblocks. <a href="https://wp-pagemanager.com/docs/getting-started/how-to-customize-page-blocks/" target="_blank">Learn more</a></li>

                </ol>
            </div>
            <p>For best 1st time experience we recommend installing the theme <a href="https://wordpress.org/themes/wp-bootstrap-starter/" target="_blank"><b>WP Bootstrap Starter</b></a> But every theme can be adapted easily for PageManager</p>
            <p>If you want to know how to integrate the page block templates into your theme, read our free tutorial.<br>
            <strong><a href="https://wp-pagemanager.com/subscribe-to-our-newsletter/" target="_blank">Subscribe to our newsletter and get free access to the PageManager knowledge base.</a></strong>
            </p>


            </div>
        </div>
    </section>
<form action="?page=settings_pagemanager" method="post" id="">
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><?php echo __('Page Types') ?></th>
            <td>
        <?php
        foreach ($list as $item) :?>
            <?php
            $name = $item[0];
            $label = __($item[1]);
            $ootb = array (
                    'index',
                    'category',
                    'post_tag'
            );
            $caseIdType = isset($item[2])
                ? $item[2]
                : array(0 => __('Standard'));
            ?>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php echo esc_html($label); ?></span></legend>
                    <label for="users_can_register"<?php if (in_array($name, $ootb)) echo " style=\"background-color: #93C54B;min-width: 150px;\""; ?>>
                        <input name="pagetype[<?php echo esc_attr($name); ?>]" type="checkbox" class="input-pagetype" value="1" <?php echo isset($settings['pagetype'][$name]) ? 'checked="true"' : '' ?>>
                        <?php
                        if (in_array($name, $ootb)) echo "<b>" . esc_html($label) . "</b>";
                        else echo esc_html($label) .  "</b><sup>2</sup>";
                        ?>

                    </label>
                </fieldset>
        <?php endforeach; ?>
            </td>
        </tr>
        </tbody>
    </table>
    <p><sup>2</sup>) Needs template adaption to display page blocks. <a href="https://wp-pagemanager.com/docs/getting-started/how-to-implement-pagemanager-to-your-theme-templates/" target="_blank">Please read our tutorial.</a> </p>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __("Save Settings", "pagemanager"); ?>"></p>
</form>
</div>
