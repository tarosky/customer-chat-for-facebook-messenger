<?php

namespace FB_Customer_Chat;

function get_page_id() {
	$page_id = get_settings( 'page_id' );
	if ( defined( 'FB_CUSTOMER_CHAT_PAGE_ID' ) && FB_CUSTOMER_CHAT_PAGE_ID ) {
		$page_id = FB_CUSTOMER_CHAT_PAGE_ID;
	}

	if ( preg_match( "/^[0-9]+$/", $page_id )) {
		return $page_id;
	} else {
		return "";
	}
}

function is_minimized() {
	$minimized = get_settings( 'minimized' );
	if ( 'auto' === $minimized ) {
		return "";
	} else {
		return $minimized;
	}
}

function get_activated() {
	return get_settings( 'activated' );
}

function get_ref() {
	return get_settings( 'ref' );
}

function get_theme_color() {
	return get_settings( 'theme_color' );
}

function get_logged_in_greeting() {
	return get_settings( 'logged_in_greeting' );
}

function get_logged_out_greeting() {
	return get_settings( 'logged_out_greeting' );
}

function get_app_id() {
	$app_id = get_settings( 'app_id' );
	if ( defined( 'FB_CUSTOMER_CHAT_APP_ID' ) && FB_CUSTOMER_CHAT_APP_ID ) {
		$app_id = FB_CUSTOMER_CHAT_APP_ID;
	}

	if ( preg_match( "/^[0-9]+$/", $app_id )) {
		return $app_id;
	} else {
		return "";
	}
}

function get_lang() {
	$lang = get_settings( 'lang' );
	if ( empty( $lang ) ) {
		return "en_US";
	} else {
		return get_settings( 'lang' );
	}
}

function can_active_chat() {
	if ( get_activated() ) {
		if ( get_page_id() && get_app_id() ) {
			return true;
		}
	}

	return false;
}

function get_settings( $key ) {
	$settings = (array) get_option( 'fb-customer-chat', array() );
	if ( ! empty( $settings[ $key ] ) ) {
		return $settings[ $key ];
	} else {
		return null;
	}
}
