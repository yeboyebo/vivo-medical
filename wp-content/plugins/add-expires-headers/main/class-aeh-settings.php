<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
 * Declaring Class
 */
class AEH_Settings {
  public $expires_headers_image_types = array(
    'gif' => null,
    'ico' => null,
    'jpeg'=> null,
    'jpg'=> null,
  );

  public $expires_headers_audio_types = array(
    'dct' => null,
    'gsm' => null,
    'mp3'=> null,
    'ogg'=> null,
  );

  public $expires_headers_video_types = array(
    '3gp' => null,
    'avi' => null,
    'flv'=> null,
    'mkv'=> null,
  );
	public $expires_headers_font_types = array(
    'otf' => null,
	'ttf' => null,
  );
  public $expires_headers_text_types = array(
    'css' => null,
  );

  public $expires_headers_application_types = array(
	'javascript' => null,
	'x-javascript' => null,
  );

  public $expires_headers_general_settings = array(
    'image' => null,
    'audio' => null,
    'video' => null,
	'font' => null,
    'text' => null,
    'application' => null,
  );

  public $expires_headers_days_settings = array(
    'image' => 30,
    'audio' => 30,
    'video' => 30,
	'font'=> 30,
    'text' => 30,
    'application' => 30,
  );

  private static $instance = null;
  public static function get_instance() {
    if ( ! self::$instance ) {
      self::$instance = new self();
    }

    return self::$instance;
  }
  public function parse_expires_headers_settings($settings) {
    $args = array(
      'general'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'image'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'audio'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'video'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
	'font'          => array(
		'filter' => FILTER_VALIDATE_BOOLEAN,
		'flags'  => FILTER_REQUIRE_ARRAY,
	),
      'text'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'application'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'expires_days'         => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
    );

    $settings = filter_var_array( $settings, $args );
    return $settings;
  }

	public function parse_expires_headers_main_settings($settings) {
    $args = array(
			'expires_headers' => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
      ),
		);

    $settings = filter_var_array( $settings, $args );
    return $settings;
  }

  public function init_general_defaults() {
		$defaults = array(
			'general' => array(
        'image' => null,
        'audio' => null,
        'video' => null,
				'font' => null,
        'text' => null,
        'application' => null,
			),
			'image'   => array(
        'gif' => null,
        'ico' => null,
        'jpeg'=> null,
        'jpg'=> null,
			),
			'audio'       => array(
        'dct' => null,
        'gsm' => null,
        'mp3'=> null,
        'ogg'=> null,
			),
			'video'         => array(
        '3gp' => null,
        'avi' => null,
        'flv'=> null,
        'mkv'=> null,
			),
			'font' => array(
        'otf' => null,
			),
			'text'   =>array(
        'css' => null,
      ),
	'application' => array(
		'javascript' => null,
		'x-javascript' => null,
      ),
			'expires_days' => array (
        'image' => 30,
        'audio' => 30,
        'video' => 30,
		'font' => 30,
        'text' => 30,
        'application' => 30,
      ),
		);
    return $defaults;
	}
}
