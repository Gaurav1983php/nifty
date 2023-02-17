<?php
/*
Includes shortocdes
Plugin: Current Year and Symbols Shortcode
Since: 2.3
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cys_year( $atts ){
    $atts = shortcode_atts(
    array(
        'format' => 'error',
        'offset' => 'none',
    ), $atts, 'y' );
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'y' || $atts['format'] == 'Y') {
          if ($atts['offset'] != 'none') {
            return date_i18n($atts['format'], strtotime('+' . $atts['offset'] . ' years'));
          } else {
            return date_i18n($atts['format']);
          }
        } else {
          return $atts['format'] . ' is not a valid year format!';
        }
    } else {
      if ($atts['offset'] != 'none') {
        return date_i18n("Y", strtotime('+' . $atts['offset'] . ' years'));
      } else {
        return date_i18n("Y");
      }
    }
}
add_shortcode( 'y', 'cys_year' );

function cys_month( $atts ){
    $atts = shortcode_atts(
    array(
        'format' => 'error',
        'offset' => 'none',
    ), $atts, 'm' );
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'F' || $atts['format'] == 'm' || $atts['format'] == 'M' || $atts['format'] == 'n') {
          if ($atts['offset'] != 'none') {
            return date_i18n($atts['format'], strtotime('+' . $atts['offset'] . ' months'));
          } else {
            return date_i18n($atts['format']);
          }
        } else {
          return $atts['format'] . ' is not a valid month format!';
        }
    } else {
      if ($atts['offset'] != 'none') {
        return date_i18n("F", strtotime('+' . $atts['offset'] . ' months'));
      } else {
        return date_i18n("F");
      }
    }
}
add_shortcode( 'm', 'cys_month' );

function cys_day( $atts ){
    $atts = shortcode_atts(
    array(
        'format' => 'error',
        'offset' => 'none',
    ), $atts, 'd' );
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'd' || $atts['format'] == 'D' || $atts['format'] == 'j' || $atts['format'] == 'N' || $atts['format'] == 'S' || $atts['format'] == 'w' || $atts['format'] == 'z' || $atts['format'] == 't') {
          if ($atts['offset'] != 'none') {
            return date_i18n($atts['format'], strtotime('+' . $atts['offset'] . ' days'));
          } else {
            return date_i18n($atts['format']);
          }
        } else {
          return $atts['format'] . ' is not a valid day format!';
        }
    } else {
      if ($atts['offset'] != 'none') {
        return date_i18n("d", strtotime('+' . $atts['offset'] . ' days'));
      } else {
        return date_i18n("d");
      }
    }
}
add_shortcode( 'd', 'cys_day' );

function cys_current_date( $atts ){
  $atts = shortcode_atts(
  array(
      'format' => 'error',
      'offset' => 'none',
  ), $atts, 'dmy' );
  if ($atts['format'] != 'error') {
      if ($atts['offset'] != 'none') {
        return date_i18n($atts['format'], strtotime($atts['offset']));
      } else {
        return date_i18n($atts['format']);
      }
  } else {
    if ($atts['offset'] != 'none') {
      return date_i18n("d/m/Y", strtotime($atts['offset']));
    } else {
      return date_i18n("d/m/Y");
    }
  }
}
add_shortcode( 'dmy', 'cys_current_date' );

function csy_copy_year( $atts ) {
    $atts = shortcode_atts(
    array(
        'format' => 'error',
    ), $atts, 'cy' );
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'y' || $atts['format'] == 'Y') {
          return '©' . date_i18n($atts['format']);
        } else {
          return $atts['format'] . ' is not a valid year format!';
        }
    } else {
      return '©' . date_i18n("Y"); 
    }
}
add_shortcode( 'cy', 'csy_copy_year' );

function csy_copy_year_year( $atts ) {
	$atts = shortcode_atts(
		array(
			'year'   => 'error enter first year, show guide',
      'format' => 'error',
		), $atts, 'cyy' );
    
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'y') {
            if (date_i18n("y") == $atts['year']) {
               return '©' . date_i18n("y");
            } else {
               return '©' . $atts['year'] . '-' . date_i18n("y");
            }
        } elseif ($atts['format'] == 'Y') {
            if (date_i18n("Y") == $atts['year']) {
               return '©' . date_i18n("Y");
            } else {
               return '©' . $atts['year'] . '-' . date_i18n("Y");
            }
        } else {
          return $atts['format'] . ' is not a valid year format!';
        }
    } else {
        if (date_i18n("Y") == $atts['year']) {
           return '©' . date_i18n("Y");
        } else {
           return '©' . $atts['year'] . '-' . date_i18n("Y");
        }
    }
}
add_shortcode( 'cyy', 'csy_copy_year_year' );

function csy_copy_year_year_long( $atts ) {
	$atts = shortcode_atts(
		array(
			'year'   => 'error enter first year, show guide',
      'format' => 'error',
		), $atts, 'cyyl' );
    
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'y') {
            if (date_i18n("y") == $atts['year']) {
               return 'Copyright ' . date_i18n("y");
            } else {
               return 'Copyright ' . $atts['year'] . '-' . date_i18n("y");
            }
        } elseif ($atts['format'] == 'Y') {
            if (date_i18n("Y") == $atts['year']) {
               return 'Copyright ' . date_i18n("Y");
            } else {
               return 'Copyright ' . $atts['year'] . '-' . date_i18n("Y");
            }
        } else {
          return $atts['format'] . ' is not a valid year format!';
        }
    } else {
        if (date_i18n("Y") == $atts['year']) {
           return 'Copyright ' . date_i18n("Y");
        } else {
           return 'Copyright ' . $atts['year'] . '-' . date_i18n("Y");
        }
    }
}
add_shortcode( 'cyyl', 'csy_copy_year_year_long' );

function csy_copy_year_year_long_symbol( $atts ) {
	$atts = shortcode_atts(
		array(
			'year'   => 'error enter first year, show guide',
      'format' => 'error',
		), $atts, 'cyyls' );
    
    if ($atts['format'] != 'error') {
        if ($atts['format'] == 'y') {
            if (date_i18n("y") == $atts['year']) {
               return '©Copyright ' . date_i18n("y");
            } else {
               return '©Copyright ' . $atts['year'] . '-' . date_i18n("y");
            }
        } elseif ($atts['format'] == 'Y') {
            if (date_i18n("Y") == $atts['year']) {
               return '©Copyright ' . date_i18n("Y");
            } else {
               return '©Copyright ' . $atts['year'] . '-' . date_i18n("Y");
            }
        } else {
          return $atts['format'] . ' is not a valid year format!';
        }
    } else {
        if (date_i18n("Y") == $atts['year']) {
           return '©Copyright ' . date_i18n("Y");
        } else {
           return '©Copyright ' . $atts['year'] . '-' . date_i18n("Y");
        }
    }
}
add_shortcode( 'cyyls', 'csy_copy_year_year_long_symbol' );

function cys_copy( $atts ){
  return '©';
}
add_shortcode( 'c', 'cys_copy' );

function cys_copylong( $atts ){
  return 'Copyright';
}
add_shortcode( 'cc', 'cys_copylong' );

function cys_registered_trademark( $atts ){
  return '®';
}
add_shortcode( 't', 'cys_registered_trademark' );

function cys_trademark( $atts ){
  return '™';
}
add_shortcode( 'tm', 'cys_trademark' );

function cys_servicemark_trademark( $atts ){
  return '℠';
}
add_shortcode( 'sm', 'cys_servicemark_trademark' );

function cys_retrieve_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return apply_filters('wpb_get_ip', $ip);
}
add_shortcode('show_user_ip', 'cys_retrieve_ip');