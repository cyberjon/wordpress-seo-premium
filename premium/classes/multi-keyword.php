<?php
/**
 * WPSEO Premium plugin file.
 *
 * @package WPSEO\Premium\Classes
 */

/**
 * Implements multi keyword int he admin.
 */
class WPSEO_Multi_Keyword implements WPSEO_WordPress_Integration {

	/**
	 * Constructor. Adds WordPress hooks.
	 */
	public function register_hooks() {
		global $pagenow;

		// Term overview is needed to properly save term meta data.
		if (
			WPSEO_Taxonomy::is_term_edit( $pagenow ) ||
			WPSEO_Taxonomy::is_term_overview( $pagenow ) ||
			WPSEO_Metabox::is_post_edit( $pagenow )
		) {
			$this->add_filters();
		}
	}

	/**
	 * Adds WordPress filters for multiple keyphrases.
	 */
	private function add_filters() {
		add_filter( 'wpseo_metabox_entries_general', array( $this, 'add_focus_keywords_input' ) );
		add_filter( 'wpseo_metabox_entries_general', array( $this, 'add_keyword_synonyms_input' ) );

		add_filter( 'wpseo_taxonomy_content_fields', array( $this, 'add_focus_keywords_taxonomy_input' ) );
		add_filter( 'wpseo_taxonomy_content_fields', array( $this, 'add_keyword_synonyms_taxonomy_input' ) );
		add_filter( 'wpseo_add_extra_taxmeta_term_defaults', array( $this, 'register_taxonomy_metafields' ) );
	}

	/**
	 * Add field in which we can save multiple keywords
	 *
	 * @param array $field_defs The current fields definitions.
	 *
	 * @return array Field definitions with our added field.
	 */
	public function add_focus_keywords_input( $field_defs ) {
		if ( is_array( $field_defs ) ) {
			$field_defs['focuskeywords'] = array(
				'type'  => 'hidden',
				'title' => 'focuskeywords',
			);
		}

		return $field_defs;
	}

	/**
	 * Add field in which we can save multiple keyword synonyms
	 *
	 * @param array $field_defs The current fields definitions.
	 *
	 * @return array Field definitions with our added field.
	 */
	public function add_keyword_synonyms_input( $field_defs ) {
		if ( is_array( $field_defs ) ) {
			$field_defs['keywordsynonyms'] = array(
				'type'  => 'hidden',
				'title' => 'keywordsynonyms',
			);
		}

		return $field_defs;
	}

	/**
	 * Adds a field to the taxonomy metabox in which we can save multiple keywords.
	 *
	 * @param array $fields The current fields.
	 *
	 * @return array $fields with our added field.
	 */
	public function add_focus_keywords_taxonomy_input( $fields ) {
		if ( is_array( $fields ) ) {
			$fields['focuskeywords'] = array(
				'label'       => '',
				'description' => '',
				'type'        => 'hidden',
				'options'     => '',
			);
		}

		return $fields;
	}

	/**
	 * Adds a field in which we can save multiple keyword synonyms.
	 *
	 * @param array $fields The current fields.
	 *
	 * @return array $fields with our added field.
	 */
	public function add_keyword_synonyms_taxonomy_input( $fields ) {
		if ( is_array( $fields ) ) {
			$fields['keywordsynonyms'] = array(
				'label'       => '',
				'description' => '',
				'type'        => 'hidden',
				'options'     => '',
			);
		}

		return $fields;
	}

	/**
	 * Extends the taxonomy defaults.
	 *
	 * @param array $defaults The defaults to extend.
	 *
	 * @return array $defaults The extended defaults.
	 */
	public function register_taxonomy_metafields( $defaults ) {
		$defaults['wpseo_focuskeywords']   = '';
		$defaults['wpseo_keywordsynonyms'] = '';

		return $defaults;
	}
}
