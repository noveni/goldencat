<?php
/**
 * Custom Options Page
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class GoldenCatTaxonomyMetaField 
{


    // Allowed field type
    private $fields_type = array( 'text', 'select', 'theme_color', 'image');

    public $fields_config_example = array(
        array(
            'taxonomy' => array( 'category', 'post_tag' ),
            'fields' => array(
                array(
                    'id' => 'global_field_text',
                    'label' => 'Text Field',
                    'type' => 'text',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'
                ),
                array(
                    'id' => 'global_field_select',
                    'label' => 'Select Field',
                    'type' => 'select',
                    'choices' => array( '-1' => 'No Choices', 1 => 'Choice 1', 2 => 'Choice 2'),
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'
                ),
            )
        ),
        array(
            'taxonomy' => array( 'category' ),
            'fields' => array(
                array(
                    'id' => 'only-category-image',
                    'label' => 'Image',
                    'type' => 'image',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'
                ),
             )
        )
    );

    public $fields_theme_config;

    public function __construct( $fields_config ) {

        add_action('admin_enqueue_scripts', function($hook) {
			GoldenCatThemeScripts::toEnqueueScript('admin/taxonomy-meta-field');
        });

        // add_action( 'admin_menu', [$this, 'addPage']);

        // We Get the config from the mains functions of theme
        if ( $fields_config != null ) {

            $this->fields_theme_config = $fields_config;
            $this->initFields();
        }

        // // Just for the dev/demo purpose
        // if ( $fields_config == null ) {
        //     $this->fields_theme_config = $this->fields_config_example;
        //     $this->initFields();
        // }
    }

    public function initFields()
    {
        foreach ($this->fields_theme_config as $fields_group) {

            if ( !empty( $fields_group['taxonomy'] ) && !empty( $fields_group['fields'] ) ) {

                // Each Group of fields as a taxonomy attribute
                // We need to iterate over each taxonomy to add action to 
                //  - Add Field in Taxonomy Add Form 
                //  - Add Field in Taxonomy Edit Form
                //  - Save / Update field in meta for creation and edit taxonomy

                foreach( $fields_group['taxonomy'] as $taxonomy_slug ) {
                    add_action( $taxonomy_slug . '_add_form_fields', function($taxonomy) use ($fields_group) {
                        $this->add_edit_form_fields( $fields_group );
                    });
                    add_action( $taxonomy_slug . '_edit_form_fields', function( $term, $taxonomy ) use ($fields_group) {
                        $this->add_edit_form_fields( $fields_group, $term );
                    }, 10, 2);
                    add_action( 'created_' . $taxonomy_slug, function( $term_id ) use ($fields_group) {
                        $this->save_term_field( $term_id, $fields_group );
                    });
                    add_action( 'edited_' . $taxonomy_slug, function( $term_id ) use ($fields_group) {
                        $this->save_term_field( $term_id, $fields_group );
                    });
                }
            }
        }
    }

    /**
     * Callback action for adding a field to add form taxonomy
     */
    public function add_edit_form_fields( $fields_group, $term = null )
    {
        foreach ($fields_group['fields'] as $field) {

            $description = isset($field['description']) ? $field['description'] : ''; 
            $label = isset($field['label']) ? $field['label'] : ''; 
            $choices = isset($field['choices']) ? $field['choices'] : false; 

            // Ensure we can handle the field
            if ( in_array($field['type'], $this->fields_type ) ) {


                $field_meta_key = $field['id'];

                $value = false;
                if ( $term !== null ) {
                    $value = get_term_meta( $term->term_id, $field_meta_key, true );
                }

                // Serve to hold the input field (text, select, image etc)
                $the_input = '';

                switch ($field['type']) {
                    case 'text':
                        $the_input = $this->textField($field_meta_key, $value);
                        break;
                    case 'select':
                        $the_input = $this->selectField($field_meta_key, $choices, $value);
                        break;
                    case 'theme_color':
                        $the_input = $this->colorField($field_meta_key, $choices, $value);
                        break;
                    case 'image':
                        $the_input = $this->imageField($field_meta_key, $value);
                        break;
                }

                if ( $term !== null ) {
                    $form_field = $this->editFieldWrapper( $field_meta_key, $the_input, $label, $description );
                } else {
                    $form_field = $this->fieldWrapper( $field_meta_key, $the_input, $label, $description );
                }

                echo $form_field;
            }

        }
    }

    /**
     * Callback action for creating/updating a field in meta 
     */
    public function save_term_field( $term_id, $fields_group )
    {
        foreach ($fields_group['fields'] as $field) {
            update_term_meta(
                $term_id,
                $field['id'],
                sanitize_text_field( $_POST[ $field['id'] ] )
            );
        }
    }

    /**
     * Create a row for the taxonomy Add Form
     */
    public function fieldWrapper( $key, $input, $label = '', $description = '' )
    {
        $form_field = '<div class="form-field">
            <label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>
            ' . $input . '
            <p>' . esc_html( $description ) . '</p>
        </div>';

        return $form_field;
    }

    /**
     * Create a row for the taxonomy Edit Form
     */
    public function editFieldWrapper( $key, $input, $label = '', $description = '' )
    {
        $edit_form_field = '<tr class="form-field">
            <th>
                <label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>
            </th>
            <td>
            ' . $input . '
                <p>' . esc_html( $description ) . '</p>
            </td>
        </tr>';

        return $edit_form_field;
    }

    public function textField( $key, $value = false )
    {
        $value_attribute = $value !== false ? 'value="' . esc_attr( $value ) . '"' : '';
        return '<input name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ). '" type="text"' . $value_attribute . '/>';
    }

    public function selectField( $key, $choices, $value = false )
    {

        $options_html = '';
        foreach ($choices as $choice_value => $choice_label) {
            $selected = '';
            if ( $value !== false ) {
                $selected = $choice_value == $value ? 'selected="selected"' : '';
            }
            $options_html .= '<option value="' . $choice_value . '" '. $selected .'>' . $choice_label . '</option>' . "\n";
        }
        $select_field = '<select name="' .  esc_attr( $key ) . '" id="' .  esc_attr( $key ) . '" class="postform">
            ' . $options_html . '
            </select>';

        return $select_field;
    }

    public function colorField( $key, $colors, $value = false )
    {

        $options_html = '';
        $options_html .= '<option value="no">Sans couleur</option>' . "\n";
        foreach ($colors as $color) {
            $selected = '';
            if ( $value !== false ) {
                $selected = $color['slug'] == $value ? 'selected="selected"' : '';
            }
            $options_html .= '<option value="' . esc_attr( $color['slug'] ) . '" '. $selected .'>' . $color['name'] . '</option>' . "\n";
        }
        $select_field = '<select name="' .  esc_attr( $key ) . '" id="' .  esc_attr( $key ) . '" class="postform">
            ' . $options_html . '
            </select>';

        return $select_field;
    }

    public function imageField( $key, $image_id = false )
    {

        $image = false;

        $upload_label = __('Upload Image', 'goldencat');
        $remove_label = __('Remove image', 'goldencat');

        if ( $image_id !== false ) {
            $image = wp_get_attachment_image_src( $image_id );
            $input_hidden = '<input type="hidden" name="' .  esc_attr( $key ) . '" value="' .  esc_attr( $image_id ) . '" />';
        } else {
            $input_hidden = '<input type="hidden" name="' .  esc_attr( $key ) . '" id="' .  esc_attr( $key ) . '" />';
        }
        $remove_image_btn = '<a href="#" class="goldencat-admin-term-field-image-remove" style="display:none">' . $remove_label . '</a>';

        if ($image) {
            $upload_label = '<img src="' . $image[0] . '" />';
            $remove_image_btn = '<a href="#" class="goldencat-admin-term-field-image-remove">' . $remove_label . '</a>';
        }

        $add_image_btn = '<a href="#" class="goldencat-admin-term-field-image-upload">' . $upload_label . '</a>';

        $image_field = $add_image_btn . $remove_image_btn  . $input_hidden;

        return $image_field;
    }
}
