<?php

    //require files
    require_once("../../../wp-config.php");
    require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');


    //get post data
    $objPost = get_post( $_GET['post'] );
    
    //check if post exists
    if( !$objPost )
    {
        exit( 'Post not exists' );
    }
    
    
//     echo wpautop( $objPost->post_content, 0 );
//     echo wpautop( $objPost->post_content, 1 );
//     exit;
    
    
    //author
    $objAuthor = get_userdata( $objPost->post_author );
    $strPermalink = get_permalink( $objPost->ID );
    
        if( $objAuthor->first_name || $objAuthor->last_name )
        {
            $strAuthor = $objAuthor->first_name .' '. $objAuthor->last_name . ' ' . $objAuthor->user_email;
        }
        else
        {
            $strAuthor = $objAuthor->user_nicename . ' ' . $objAuthor->user_email;
        }
    
    
    //generate PDF document
        
        // create new PDF document
        $objTcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
        
        // set document information
        $objTcpdf->SetCreator(PDF_CREATOR);
        $objTcpdf->SetAuthor( $strAuthor );
        $objTcpdf->SetTitle( get_option('blogname') );
        $objTcpdf->SetSubject( $objPost->post_title );
//         $objTcpdf->SetKeywords("TCPDF, PDF, example, test, guide");
        
        // set default header data
        $objTcpdf->SetHeaderData(null, null, get_option('blogname') . ' | ' . $objPost->post_title, 'Copyright ' . $strAuthor . "\n" . $strPermalink );
        
        // set header and footer fonts
        $objTcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $objTcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        //set margins
        $objTcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $objTcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $objTcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        //set auto page breaks
        $objTcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        //set image scale factor
        $objTcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
        
        //set some language-dependent strings
        $objTcpdf->setLanguageArray($l); 
        
        //initialize document
        $objTcpdf->AliasNbPages();
        
        // add a page
        $objTcpdf->AddPage();
        
        // set font
        $objTcpdf->SetFont( PDF_FONT_NAME_MAIN, '', get_option( 'as_pdf_main_font_size' ) );
        
        // ---------------------------------------------------------
        
        $strHtml = '<h1>' . $objPost->post_title . '</h1>' . wpautop( $objPost->post_content, true );
        
        // output the HTML content
        $objTcpdf->writeHTML( $strHtml , true, 0, true, 0);
        
        
        // ---------------------------------------------------------
        
        //output PDF document
        $objTcpdf->Output( get_option('blogname') . '-' . $objPost->post_title . '.pdf', get_option( 'as_pdf_download_type' ) );
        

    
?>
