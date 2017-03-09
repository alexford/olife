<?php

  /*****************************************************
  ** Title........: Configuration File
  ** Filename.....: config.php
  ** Author.......: Ralf Stadtaus
  ** Homepage.....: http://www.stadtaus.com/
  ** Contact......: mailto:info@stadtaus.com
  ** Version......: 0.4
  ** Notes........: This file contains the configuration
  ** Last changed.: 2004-01-19
  ** Last change..: Vote information
  *****************************************************/





  /*****************************************************
  ** Script configuration - for the documentation of
  ** following variables please take a look at the
  ** documentation file in folder 'docu'.
  *****************************************************/

          
          $vote_title             = 'Next for Olife';
          $vote_text              = 'What do you most want to see on Olife?';
          
          $vote_option[]          = 'Picture Galleries/Hosting';
          $vote_option[]          = 'Forums';
          $vote_option[]          = 'News Features/Articles';
          $vote_option[]          = 'Chatbox';
          $vote_option[]          = 'Private Messages';
          $vote_option[]          = 'Skins';
          $vote_option[]          = 'Other (Tell me)';
          
          $intern_vote_name       = 'next_olife';
          $form_field_type        = 'radio';             // (radio, select, readio_image)
          $bar_image_name         = 'blue.gif';
          $max_bar_width          = '200';               // (pixel)
          
          $check_ip_address       = 'yes';                // (yes/no)
          $check_cookie           = 'no';
          
          
          $language               = 'en';                // (de, en, hu, nl, no, pl, sv, tr)

          $path['templates']      = './templates/';
          $path['logfiles']       = './logfiles/';

          $tmpl['layout']         = 'voting.tpl.html';
          
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
          $script_root = '';
          
          include('inc/core.inc.php');




?>