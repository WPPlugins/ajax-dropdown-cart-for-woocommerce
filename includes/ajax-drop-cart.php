<h2><?php _e('Setting', 'netbasecart'); ?></h2>
<div class="key-licen">
    <p style="display: <?php echo ($this->_active) ? 'none' : 'block'; ?>"><?php _e('Upgrade', 'netbasecart'); ?> <a href="http://cmsmart.net/wordpress-plugins/ajax-drop-down-cart-for-woocommerce"><?php _e(' premium license', 'netbasecart'); ?></a><?php _e(' to use fully options', 'netbasecart'); ?> </p>
    <div id="check-key">
        <?php if(!$this->_active): ?>
        <input type="text" name="license" value="" placeholder="Enter your license">
        <button id="active_license" class="button-primary" <?php echo ($this->_active) ? 'disabled' : ''; ?>><?php _e('Active', 'netbasecart'); ?></button>
        <?php endif; ?>
        <button id="remove_license" class="button-primary"><?php _e('Remove', 'netbasecart'); ?></button> 
        <span style="display: <?php echo ($this->_active) ? 'inline-block' : 'none'; ?>"><?php _e('Remove license domain', 'netbasecart'); ?></span>
        <img id="image_loading" src="<?php echo NB_AJAX_DROP_CART_URL . 'assets/images/loading.gif'; ?>" style="display: none;"/>
    </div>
    <span id="message"></span>
</div>
<div class="wrap adcart">
    <?php
    if (isset($_GET['tab']))
        $this->nbadcart_admin_tabs($_GET['tab']);
    else
        $this->nbadcart_admin_tabs('adcart-default');

    if (isset($_GET['tab']))
        $tab = $_GET['tab'];
    else
        $tab = 'adcart-default';
    ?>

    <form id="myform" method="post" action="<?php admin_url('admin.php?page=netbasecart-settings'); ?>" enctype="multipart/form-data">
        <table class="form-table">

            <?php
            wp_nonce_field("nbadcart-settings-page");

            if ($pagenow == 'admin.php' && $_GET['page'] == 'netbasecart-settings') {

                switch ($tab) {

                    case'adcart-default':
                        ?>
                        <tr>
                            <th><?php echo esc_html__("Select style", "netbasecart"); ?> </th>
                            <td>
                                <fieldset name="check-style" id="jform_params_use_skin" class="radio ">
                                    <input type="radio" id="jform_params_use_skin0" name="jform" <?php echo ($settings['adcart-style'] == 'skil') ? " checked='checked'" : ""; ?>value="skil">  <?php echo esc_html__("Available style", "netbasecart"); ?>

                                    <input type="radio" id="jform_params_use_skin1" name="jform"<?php echo ($settings['adcart-style'] == 'cus') ? " checked='checked'" : ""; ?>value="cus"> <?php echo esc_html__("Custom color", "netbasecart"); ?>
                                </fieldset>

                            </td>
                        </tr>
                        <tr id="desc" class="skin-skil">
                            <th> <?php echo esc_html__("Select skin", "netbasecart"); ?></th>
                            <td>
                                <select name="adcart-skin" id="adcart-skin">
                                    <option <?php echo ($settings['adcart-skin'] == 'pink') ? " selected='selected'" : ""; ?>value="pink"><?php echo esc_html__("Pink", "netbasecart"); ?></option>  
                                    <option <?php echo ($settings['adcart-skin'] == 'dark') ? " selected='selected'" : ""; ?>value="dark"><?php echo esc_html__("Dark", "netbasecart"); ?></option>
                                    <option <?php echo ($settings['adcart-skin'] == 'blue') ? " selected='selected'" : ""; ?>value="blue"><?php echo esc_html__("Blue", "netbasecart"); ?></option>
                                    <option <?php echo ($settings['adcart-skin'] == 'red') ? " selected='selected'" : ""; ?>value="red"><?php echo esc_html__("Red", "netbasecart"); ?></option>
                                    <option <?php echo ($settings['adcart-skin'] == 'orange') ? " selected='selected'" : ""; ?>value="orange"><?php echo esc_html__("Orange", "netbasecart"); ?></option>


                                </select>
                            </td>
                        </tr>

                        <tr class="skin-cus" id="desc">
                            <th><?php echo esc_html__("Text color", "netbasecart"); ?> </th>
                            <td>
                                <div id="colorSelector-t" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-text-color" id="colorSelVal-t" value="<?php echo $settings['adcart-text-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-t').ColorPicker({
                                        color: '#<?php echo $settings['adcart-text-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-t div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-t').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-t div').css('background-color', '#<?php echo $settings['adcart-text-color']; ?>');
                                    jQuery('#colorSelVal-t').val('<?php echo $settings['adcart-text-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Link color", "netbasecart"); ?></th>
                            <td>
                                <div id="colorSelector-l" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-link-color" id="colorSelVal-l" value="<?php echo $settings['adcart-link-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-l').ColorPicker({
                                        color: '#<?php echo $settings['adcart-link-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-l div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-l').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-l div').css('background-color', '#<?php echo $settings['adcart-link-color']; ?>');
                                    jQuery('#colorSelVal-l').val('<?php echo $settings['adcart-link-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Link hover color", "netbasecart"); ?></th>
                            <td>
                                <div id="colorSelector-2" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-link-hover-color" id="colorSelVal-2" value="<?php echo $settings['adcart-link-hover-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-2').ColorPicker({
                                        color: '#<?php echo $settings['adcart-link-hover-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-2 div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-2').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-2 div').css('background-color', '#<?php echo $settings['adcart-link-hover-color']; ?>');
                                    jQuery('#colorSelVal-2').val('<?php echo $settings['adcart-link-hover-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Button text color", "netbasecart"); ?></th>
                            <td>
                                <div id="colorSelector-btc" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-button-text-color" id="colorSelVal-btc" value="<?php echo $settings['adcart-button-text-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-btc').ColorPicker({
                                        color: '#<?php echo $settings['adcart-button-text-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-btc div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-btc').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-btc div').css('background-color', '#<?php echo $settings['adcart-button-text-color']; ?>');
                                    jQuery('#colorSelVal-btc').val('<?php echo $settings['adcart-button-text-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Button background color", "netbasecart"); ?> </th>
                            <td>
                                <div id="colorSelector-btbg" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-button-bg-color" id="colorSelVal-btbg" value="<?php echo $settings['adcart-button-bg-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-btbg').ColorPicker({
                                        color: '#<?php echo $settings['adcart-button-bg-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-btbg div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-btbg').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-btbg div').css('background-color', '#<?php echo $settings['adcart-button-bg-color']; ?>');
                                    jQuery('#colorSelVal-btbg').val('<?php echo $settings['adcart-button-bg-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Button background hover color", "netbasecart"); ?></th>
                            <td>
                                <div id="colorSelector-btbg-hover" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-button-bghv-color" id="colorSelVal-btbg-hover" value="<?php echo $settings['adcart-button-bghv-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-btbg-hover').ColorPicker({
                                        color: '#<?php echo $settings['adcart-button-bghv-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-btbg-hover div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-btbg-hover').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-btbg-hover div').css('background-color', '#<?php echo $settings['adcart-button-bghv-color']; ?>');
                                    jQuery('#colorSelVal-btbg-hover').val('<?php echo $settings['adcart-button-bghv-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Background color", "netbasecart"); ?> </th>
                            <td>
                                <div id="colorSelector-dc" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-background-color" id="colorSelVal-dc" value="<?php echo $settings['adcart-background-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-dc').ColorPicker({
                                        color: '#<?php echo $settings['adcart-background-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-dc div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-dc').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-dc div').css('background-color', '#<?php echo $settings['adcart-background-color']; ?>');
                                    jQuery('#colorSelVal-dc').val('<?php echo $settings['adcart-background-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Border color", "netbasecart"); ?></th>
                            <td>
                                <div id="colorSelector-dbc" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-background-border-color" id="colorSelVal-dbc" value="<?php echo $settings['adcart-background-border-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-dbc').ColorPicker({
                                        color: '#<?php echo $settings['adcart-background-border-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-dbc div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-dbc').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-dbc div').css('background-color', '#<?php echo $settings['adcart-background-border-color']; ?>');
                                    jQuery('#colorSelVal-dbc').val('<?php echo $settings['adcart-background-border-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr class="skin-cus" id="desc">
                            <th> <?php echo esc_html__("Border radius", "netbasecart"); ?></th>
                            <td>
                                <div id="radiusSlider"></div>
                                <input type="hidden" name="adcart-border-radius" value="<?php echo $settings['adcart-border-radius']; ?>" id="radiusVal"/>
                                <span id="radiusSliderVal">0</span>px
                                <script type="text/javascript">

                                    jQuery(function () {
                                        jQuery("#radiusSlider").slider({
                                            step: 1,
                                            value: <?php echo ($settings['adcart-border-radius']) ? $settings['adcart-border-radius'] : '0'; ?>,
                                            slide: function (event, ui) {

                                                jQuery('#radiusSliderVal').text(ui.value);
                                                jQuery('#radiusVal').val(ui.value);
                                            }
                                        });

                                        jQuery('#radiusSliderVal').text(<?php echo $settings['adcart-border-radius']; ?>);

                                    });

                                </script>
                            </td>
                        </tr>
                        <tr>
                            <th> <?php echo esc_html__("Cart icon", "netbasecart"); ?></th>
                            <td>
                                <fieldset id="jform_display_icon" class="radio ">
                                    <input type="radio" id="display-icon" name="jform-icon-display" <?php echo ($settings['adcart-icon-display'] == 'show') ? " checked='checked'" : ""; ?>value="show"> <?php echo esc_html__("Show", "netbasecart"); ?>

                                    <input type="radio" id="display-icon" name="jform-icon-display" <?php echo ($settings['adcart-icon-display'] == 'hide') ? " checked='checked'" : ""; ?>value="hide"> <?php echo esc_html__("Hide", "netbasecart"); ?>
                                </fieldset>
                            </td>
                        </tr>
                        <tr class="icon-display-show">
                            <th> <?php echo esc_html__("Icon style", "netbasecart"); ?></th>
                            <td>
                                <fieldset id="jform_use_icon" class="radio ">
                                    <input type="radio" id="use-icon" name="jform-icon" <?php echo ($settings['adcart-icon-skin'] == '1') ? " checked='checked'" : ""; ?>value="1"> <?php echo esc_html__("Use icon", "netbasecart"); ?>

                                    <input type="radio" id="use-icon" name="jform-icon" <?php echo ($settings['adcart-icon-skin'] == '0') ? " checked='checked'" : ""; ?>value="0"> <?php echo esc_html__("Use image", "netbasecart"); ?>
                                </fieldset>
                            </td>
                        </tr>
                        <tr class="icon-set icon-display-show" id="icon-1">
                            <th><?php echo esc_html__("Icon skin ", "netbasecart"); ?></th>
                            <td>
                                <ul class="list-icons">
            <?php
            foreach ($this->icon_styles as $key => $icon_style) :
                if ($settings['adcart-icon-style'] == $key)
                    $cls = 'activei';
                else
                    $cls = '';
                ?>
                                        <li class="<?php echo $cls; ?>">
                                            <div class="icon-adcartfont icon-<?php echo $icon_style; ?>"></div>
                                            <input type="radio" name="icon-style" value="<?php echo $key; ?>" style="display:none;"<?php echo ($settings['adcart-icon-style'] == $key + 1) ? " checked='checked'" : ""; ?> />
                                        </li>
            <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <tr class="icon-set icon-display-show" id="icon-1">
                            <th> <?php echo esc_html__("Icon color", "netbasecart"); ?></th>
                            <td>
                                <div id="colorSelector-i" class="colorSelector"><div></div></div>
                                <input type="hidden" name="adcart-icon-color" id="colorSelVal-i" value="<?php echo $settings['adcart-icon-color']; ?>"/>
                                <script type="text/javascript">
                                    jQuery('#colorSelector-i').ColorPicker({
                                        color: '#<?php echo $settings['adcart-icon-color']; ?>',
                                        onShow: function (colpkr) {
                                            jQuery(colpkr).fadeIn(500);
                                            return false;
                                        },
                                        onHide: function (colpkr) {
                                            jQuery(colpkr).fadeOut(500);
                                            return false;
                                        },
                                        onChange: function (hsb, hex, rgb) {
                                            jQuery('#colorSelector-i div').css('backgroundColor', '#' + hex);
                                            jQuery('#colorSelVal-i').val(hex);
                                        }
                                    });
                                    jQuery('#colorSelector-i div').css('background-color', '#<?php echo $settings['adcart-icon-color']; ?>');
                                    jQuery('#colorSelVal-i').val('<?php echo $settings['adcart-icon-color']; ?>');
                                </script>
                            </td>
                        </tr>
                        <tr valign="top" class="icon-set icon-display-show" id="icon-0">
                            <th scope="row"> <?php echo esc_html__("Custom icon image", "netbasecart"); ?>
                                <i style="font-size:10px; display: table;"><?php echo esc_html__("(preferred size 20x20.)", "netbasecart"); ?></i></th>

                            <td>

                                <div id="product_label_image_field" class="form-field">
                                    <label><?php _e('Image', 'netbasecart'); ?></label>

                                    <div id="product_label_image" style="float:left;margin-right:10px;">
                                        <?php if ($settings['adcart-custom-icon'] && file_exists(ABSPATH . str_replace(get_site_url() . "/", "", $settings['adcart-custom-icon']))) : ?>


                                            <img width="60" height="60" src="<?php echo $settings['adcart-custom-icon']; ?>"/>
                                            <br/>
            <?php else: ?>
                                            <img src="<?php echo woocommerce_placeholder_img_src(); ?>" width="60" height='60'/>
            <?php endif; ?>


                                    </div>
                                    <div style="line-height:60px;">

                                        <input type="hidden" id="product_label_image_id" name="product_label_image_id" />
                                        <button type="submit" class="upload_image_button button"><?php echo esc_html__("Upload/Add image", "netbasecart"); ?></button>
                                        <button type="submit" class="remove_image_button button"><?php echo esc_html__("Remove image", "netbasecart"); ?></button>
                                    </div>
                                    <script type="text/javascript">

                                        if (!jQuery('#product_label_image_id').val())
                                            jQuery('.remove_image_button').hide();

                                        var file_frame;

                                        jQuery(document).on('click', '.upload_image_button', function (event) {
                                            event.preventDefault();

                                            if (file_frame) {
                                                file_frame.open();
                                                return;
                                            }

                                            file_frame = wp.media.frames.downloadable_file = wp.media({
                                                title: '<?php _e('Choose an image', 'netbasecart'); ?>',
                                                button: {
                                                    text: '<?php _e('Use image', 'netbasecart'); ?>',
                                                },
                                                multiple: false
                                            });

                                            file_frame.on('select', function () {
                                                attachment = file_frame.state().get('selection').first().toJSON();
                                                jQuery('#product_label_image_id').val(attachment.url);
                                                jQuery('#product_label_image img').attr('src', attachment.url);
                                                jQuery('.remove_image_button').show();
                                            });

                                            file_frame.open();
                                        });

                                        jQuery(document).on('click', '.remove_image_button', function (event) {
                                            jQuery('#product_label_image img').attr('src', '<?php echo woocommerce_placeholder_img_src(); ?>');
                                            jQuery('#product_label_image_id').val('');
                                            jQuery('.remove_image_button').hide();
                                            return false;
                                        });

                                    </script>
                                    <div class="clear"></div>
                                </div>	

                            </td>
                        </tr>
                        <tr>
                            <th><?php echo esc_html__("Width dropdown cart", "netbasecart"); ?> </th>
                            <td>
                                <input style="width:50px;" size="3" type="number" min="0" step="1" name="adcart-width" value="<?php echo ($settings['adcart-width']); ?>" />
                                <i>px</i>

                            </td>
                        </tr>
            <?php
            break;
        case'nbcart-setting':
            ?>
                        <tr>
                            <th><?php echo esc_html__("Select dropdown action ", "netbasecart"); ?></th>
                            <td>
                                <select <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> name="drop-trigger" id="drop-trigger">
                                    <option <?php echo (isset($settings['adcart-drop-trigger']) && $settings['adcart-drop-trigger'] == 'click') ? " selected='selected'" : ""; ?>value="click"><?php echo esc_html__("Click", "netbasecart"); ?></option>           								  
                                    <option <?php echo (isset($settings['adcart-drop-trigger']) && $settings['adcart-drop-trigger'] == 'hover') ? " selected='selected'" : ""; ?>value="hover"><?php echo esc_html__("Hover", "netbasecart"); ?></option>
                                </select>

                            </td>
                        </tr>

                        <tr>
                            <th> <?php echo esc_html__("Show cart style", "netbasecart"); ?></th>
                            <td>
                                <input <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> type="radio" name="adcart-numsub" <?php echo ($settings['adcart-numsub'] == "sub") ? "checked='checked'" : "sub"; ?> value="sub"/>&nbsp;<?php echo esc_html__("Show quantity & subtotal", "netbasecart"); ?>
                                <input <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> type="radio" name="adcart-numsub" <?php echo ($settings['adcart-numsub'] == "num") ? "checked='checked'" : ""; ?> value="num"/>&nbsp; <?php echo esc_html__("Only quantity", "netbasecart"); ?>

                            </td>
                        </tr>
                        <tr class="icon-pos">
                            <th> <?php echo esc_html__("Icon position", "netbasecart"); ?></th>
                            <td>
                                <select <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> name="icon-position" id="icon-position">
                                    <option <?php echo (isset($settings['adcart-icon-position']) && $settings['adcart-icon-position'] == 'left') ? " selected='selected'" : ""; ?>value="left"><?php echo esc_html__("Left", "netbasecart"); ?></option>           								            <option <?php echo (isset($settings['adcart-icon-position']) && $settings['adcart-icon-position'] == 'right') ? " selected='selected'" : ""; ?>value="right"><?php echo esc_html__("Right", "netbasecart"); ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th><?php echo esc_html__("Show subtotal", "netbasecart"); ?> </th>
                            <td>
                                <input <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> type="radio" <?php echo ($settings['adcart-subtotal'] == 1) ? "checked='checked'" : ""; ?>name="adcart-subtotal" value="1"/>&nbsp;<?php echo esc_html__("Yes", "netbasecart"); ?>
                                &nbsp;&nbsp;
                                <input <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> type="radio" <?php echo ($settings['adcart-subtotal'] == 0) ? "checked='checked'" : ""; ?>name="adcart-subtotal" value="0"/>&nbsp;<?php echo esc_html__("No", "netbasecart"); ?>
                            </td>
                        </tr>

                        <tr>
                            <th> <?php echo esc_html__("Effect settings", "netbasecart"); ?></th>

                            <td>

                                <input <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> style="width:100px;" size="4" type="number" min="0" step="1" name="adcart-speed" required="required" value="<?php echo ($settings['adcart-speed']); ?>" id="speed-fly"/>

                                <b>(ms) - "Flying" speed in milliseconds</b>

                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>

                                <b>OffsetX </b> 
                                <input <?php if ($this->_active == false) {
                echo 'disabled';
            } ?> style="width:50px;" size="3" type="number" min="0" step="1" name="adcart-x" value="<?php echo ($settings['adcart-x']); ?>" />
                                <i>px</i>

                            </td>

                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <b>OffsetY </b>
                                <input <?php if ($this->_active == false) {
                echo 'disabled ';
            } ?> style="width:50px;" size="3" type="number" min="0" step="1" name="adcart-y" value="<?php echo ($settings['adcart-y']); ?>" />
                                <i>px</i>

                                <p><i>(Product "fly" to cart,you can modify locations here)</i></p>
                            </td>

                        </tr>

            <?php
            break;
    }
}
?>
        </table>
        <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'netbasecart') ?>" />
                <input type="hidden" name="nbadcart-settings-submit" value="Y"/>
        </p>

    </form>


</div>