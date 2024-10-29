<?php

/*
Plugin Name: AS-PDF
Description: This plug-in generates PDF documents from posts. Very useful if you plan to share your posts in PDF format
Version: 0.3
Author: Aleksander Stacherski
*/

//link in post to generate PDF
add_filter( 'the_content', 'as_pdf_link' );
function as_pdf_link( $strContent )
{
    global $wp_query;
    
    $strHtml = '
                            <div id="aspdf">
                                <a href="' . get_bloginfo('wpurl') . '/wp-content/plugins/as-pdf/generate.php?post=' . $wp_query->post->ID . '">
                                    <span>' . stripslashes( get_option( 'as_pdf_linktext' ) ) . '</span>
                                </a>
                            </div>
                        ';
            
    return $strContent . $strHtml;
}


//add css
$blnUseDefaultCss = get_option( 'as_pdf_use_default_css' );

    if( $blnUseDefaultCss == 'true' )
    {
        add_action( 'wp_head', 'as_pdf_wp_head' );
    }
    
    
    function as_pdf_wp_head()
    {
        echo '<link rel="stylesheet" type="text/css" media="screen" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/as-pdf/as-pdf.css" />'."\n";
    }



//------------------------ wp admin-------------------------

// Hook the admin_menu display to add admin page
add_action('admin_menu', 'as_pdf_admin_menu');
function as_pdf_admin_menu()
{
    add_submenu_page( 'options-general.php', 'AS-PDF', 'AS-PDF', 8, 'AS-PDF', 'as_pdf_submenu' );
}


function as_pdf_message($message) {
    echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}

// The admin page
function as_pdf_submenu()
{
    // update options in db if requested
    if( $_POST['Submit'] )
    {
        // update linktext
        if( !$_POST['linktext'] )
        {
            $_POST['linktext'] = 'Download as PDF';
        }
            
        update_option('as_pdf_linktext', $_POST['linktext'] );
        
        // update download type
        if( !$_POST['download_type'] )
        {
            $_POST['download_type'] = 'I';
        }
            
        update_option('as_pdf_download_type', $_POST['download_type'] );
        
        
        // update use css
        update_option('as_pdf_use_default_css', $_POST['use_default_css'] );
        
        
        // update font size
        if( !$_POST['main_font_size'] )
        {
            $_POST['main_font_size'] = '10';
        }
            
        update_option('as_pdf_main_font_size', $_POST['main_font_size'] );

        
        as_pdf_message(__("Saved changes.", 'as-pdf'));
    }

    // load options from db to display
    $strLinkText = stripslashes( get_option( 'as_pdf_linktext' ) );
    $strDownloadType = stripslashes( get_option( 'as_pdf_download_type' ) );
    $strMainFontSize = stripslashes( get_option( 'as_pdf_main_font_size' ) );
    $blnUseDefaultCss = stripslashes( get_option( 'as_pdf_use_default_css' ) );
    
    // display options
?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<div class="wrap">
    <h2>AS-PDF Options</h2>
    <table class="form-table">
    
    <tr>
        <th scope="row" valign="top">
            Blog options:
        </th>
        <td>
            
            <label for="linktext">Link text:</label>
            <input type="text" id="linktext" name="linktext" value="<?php echo htmlspecialchars( $strLinkText ); ?>" />
            
            </br></br>
            
            <label for="use_default_css">Use default css:</label>
            <input type="checkbox" name="use_default_css" id="use_default_css" value="true" <?php if( $blnUseDefaultCss == 'true' ) echo 'checked="checked"'; ?> />
            
            </br></br>
            
            <label for="download_type">Download type:</label>
            <select name="download_type" id="download_type">
                <option value="I" <?php if( $strDownloadType =='I' ) echo 'selected="selected"'; ?> ><?php _e( 'Show in browser window', 'as-pdf'); ?></option>
                <option value="D" <?php if( $strDownloadType =='D' ) echo 'selected="selected"'; ?> ><?php _e( 'Force download', 'as-pdf'); ?></option>
            </select>
            
            
            
        </td>
    </tr>
    
    <tr>
        <th scope="row" valign="top">
            PDF generation:
        </th>
        <td>
            <select name="main_font_size">
                <option value="8" <?php if( $strMainFontSize =='8' ) echo 'selected="selected"'; ?> >8</option>
                <option value="9" <?php if( $strMainFontSize =='9' ) echo 'selected="selected"'; ?> >9</option>
                <option value="10" <?php if( $strMainFontSize =='10' ) echo 'selected="selected"'; ?> >10</option>
                <option value="11" <?php if( $strMainFontSize =='11' ) echo 'selected="selected"'; ?> >11</option>
                <option value="12" <?php if( $strMainFontSize =='12' ) echo 'selected="selected"'; ?> >12</option>
            </select>
        </td>
    </tr>
    
</table>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" class="button" />
</p>

</div>

</form>

<?php
}


















?>
