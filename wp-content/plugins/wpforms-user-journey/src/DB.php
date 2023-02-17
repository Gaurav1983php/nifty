<?php

namespace WPFormsUserJourney;

/**
 * The User Journey DB stores records in a custom database.
 *
 * @since 1.0.0
 */
class DB extends \WPForms_DB {

	/**
	 * Primary key (unique field) for the database table.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $primary_key = 'id';

	/**
	 * Database type identifier.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'user_journey';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->table_name = self::get_table_name();
	}

	/**
	 * Get the DB table name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_table_name() {

		global $wpdb;

		return $wpdb->prefix . 'wpforms_user_journey';
	}

	/**
	 * Get table columns.
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {

		return [
			'id'         => '%d',
			'entry_id'   => '%d',
			'form_id'    => '%d',
			'post_id'    => '%d',
			'url'        => '%s',
			'parameters' => '%s',
			'external'   => '%d',
			'title'      => '%s',
			'duration'   => '%d',
			'step'       => '%d',
			'date'       => '%s',
		];
	}

	/**
	 * Default column values.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_column_defaults() {

		return [
			'entry_id'   => '',
			'form_id'    => '',
			'post_id'    => '',
			'url'        => '',
			'parameters' => '',
			'external'   => '',
			'title'      => '',
			'duration'   => '',
			'step'       => '',
			'date'       => gmdate( 'Y-m-d H:i:s' ),
		];
	}

	/**
	 * Get rows from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args  Optional args.
	 * @param bool  $count Flag to return count instead of results.
	 *
	 * @return array|int
	 */
	public function get_rows( $args = [], $count = false ) {

		global $wpdb;

		$defaults = [
			'number'   => 999,
			'offset'   => 0,
			'id'       => 0,
			'entry_id' => 0,
			'form_id'  => 0,
			'post_id'  => 0,
			'orderby'  => 'id',
			'order'    => 'ASC',
		];

		$args = wp_parse_args( $args, $defaults );

		if ( $args['number'] < 1 ) {
			$args['number'] = PHP_INT_MAX;
		}

		$where = $this->build_where(
			$args,
			[ 'id', 'entry_id', 'form_id', 'post_id' ]
		);

		// Orderby.
		$args['orderby'] = ! array_key_exists( $args['orderby'], $this->get_columns() ) ? $this->primary_key : $args['orderby'];

		// Offset.
		$args['offset'] = absint( $args['offset'] );

		// Number.
		$args['number'] = absint( $args['number'] );

		// Order.
		if ( 'ASC' === strtoupper( $args['order'] ) ) {
			$args['order'] = 'ASC';
		} else {
			$args['order'] = 'DESC';
		}

		if ( true === $count ) {

			$results = absint( $wpdb->get_var( "SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};" ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		} else {

			$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
				"SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT {$args['offset']}, {$args['number']};" // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			);
		}

		return $results;
	}

	/**
	 * Create custom user journey table. Used on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function create_table() {

		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = '';

		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate .= "DEFAULT CHARACTER SET {$wpdb->charset}";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE {$wpdb->collate}";
		}

		$sql = "CREATE TABLE {$this->table_name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			entry_id bigint(20) NOT NULL,
			form_id bigint(20) NOT NULL,
			post_id bigint(20) NOT NULL,
			url varchar(2083) NOT NULL,
			parameters varchar(2000) NOT NULL,
			external tinyint(1) DEFAULT 0,
			title varchar(256) NOT NULL,
			duration int NOT NULL,
			step tinyint NOT NULL,
			date datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY entry_id (entry_id)
		) {$charset_collate};";

		dbDelta( $sql );
	}
}
