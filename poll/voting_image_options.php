<?php

  /*****************************************************
  ** Title........: Configuration File
  ** Filename.....: config.php
  ** Author.......: Ralf Stadtaus
  ** Homepage.....: http://www.stadtaus.com/
  ** Contact......: mailto:info@stadtaus.com
  ** Version......: 0.5
  ** Notes........: This file contains the configuration
  ** Last changed.: 2004-02-23
  ** Last change..: Image options
  *****************************************************/





  /*****************************************************
  ** Script configuration - for the documentation of
  ** following variables please take a look at the
  ** documentation file in folder 'docu'.
  *****************************************************/

          
          $vote_title             = 'Favourite Smiley';
          $vote_text              = 'What is your favourite smiley?';
          
          $vote_option[]          = './templates/smilies/1.gif';
          $vote_option[]          = './templates/smilies/2.gif';
          $vote_option[]          = './templates/smilies/3.gif';
          $vote_option[]          = './templates/smilies/4.gif';
          $vote_option[]          = './templates/smilies/5.gif';
          $vote_option[]          = './templates/smilies/6.gif';
          $vote_option[]          = './templates/smilies/7.gif';
          
          $intern_vote_name       = 'smilies';
          $form_field_type        = 'radio_image';             // (radio, select, radio_image)
          $bar_image_name         = 'red.gif';
          $max_bar_width          = '200';               // (pixel)
          
          $check_ip_address       = 'no';                // (yes/no)
          $check_cookie           = 'no';
          
          
          $language               = 'en';                // (de, en, hu, nl, no, pl, sv, tr)

          $path['templates']      = './templates/';
          $path['logfiles']       = './logfiles/';

          $tmpl['layout']         = 'voting_image_options.tpl.html';
          
          $log['logfile']         = 'log.txt';
          
          $show_error_messages    = 'yes';
          




  /*****************************************************
  ** Add here further words, text, variables and stuff
  ** that you want to appear in the template.
  *****************************************************/
          $add_text = array(

                              'txt_additional'  => 'Additional',  //  {txt_additional}
                              'txt_more'        => 'More',        //  {txt_more}
                              
                              'txt_script_name' => 'Voting Script'

                            );
                            
                            
                            





  /*****************************************************
  ** Send safety signal to included files
  *****************************************************/
          define('IN_SCRIPT', 'true');                            




  /*****************************************************
  ** Include script code
  *****************************************************/
          $script_root = './';
          
          include($script_root . 'inc/core.inc.php');




?>