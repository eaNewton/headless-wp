<?php

function create_movies() {
  register_post_type(
    'movie',
    array(
      'labels'              => array(
        'name'          => __( 'Movies' ),
        'singular_name' => __( 'Movie' ),
      ),
      'public'                => true,
      'publicly_queryable'    => true,
      'exclude_from_search'   => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_icon'             => 'dashicons-video-alt',
      'query_var'             => true,
      'has_archive'           => true,
      'capability_type'       => 'post',
      'hierarchical'          => false,
      'show_in_rest'          => true,
      'rest_controller_class' => 'WP_REST_Posts_Controller',
      'rest_base'             => 'movies',
      'supports'              => array( 'title', 'thumbnail' ),
    )
  );
}

// Attribution meta box
add_action( 'add_meta_boxes', 'add_movie_data_box' );

function add_movie_data_box( $post ) {
  add_meta_box(
    'movie_data', // ID, should be a string.
    'Movie Data', // Meta Box Title.
    'movie_data_meta_box', // Your call back function, this is where your form field will go.
    'movie', // The post type you want this to show up on, can be post, page, or custom post type.
    'normal', // The placement of your meta box, can be normal or side.
    'core' // The priority in which this will be displayed.
  );
}

function movie_data_meta_box( $post ) {
  wp_nonce_field( 'praxent_movie_data_nonce', 'movie_data_nonce' );
  $custom             = get_post_custom( $post->ID );
  $movie_director     = get_post_meta( $post->ID, 'movie_director', true );
  $movie_release_date = get_post_meta( $post->ID, 'movie_release_date', true );
  $movie_rating       = get_post_meta( $post->ID, 'movie_rating', true );
  $movie_description  = get_post_meta( $post->ID, 'movie_description', true );
?>

  <label for="movie_director">Director</label>
  <input type="text" id="movie_director" name="movie_director" style="display: block; width: 100%;" value="<?php echo esc_attr( $movie_director ); ?>"/><br/>

  <div>
    <div style="display: inline-block">
      <label for="movie_release_date" style="display: block">Release Date</label>
      <input type="text" id="movie_release_date" name="movie_release_date" style="display: inline-block; width: 75%;" value="<?php echo esc_attr( $movie_release_date ); ?>"/>
    </div>

    <div style="display: inline-block">
      <label for="movie_rating" style="display: block">Rating</label>
      <select id="movie_rating" name="movie_rating">
        <?php
          $ratings = array( 'g', 'pg', 'pg13', 'r', 'nc17' );
          foreach ( $ratings as $rating ) {
            if ( $movie_rating && $movie_rating === $rating ) {
              echo '<option selected value="' . esc_html( $rating ) . '">' . esc_html( strtoupper( $rating ) ) . '</option>';
            } elseif ( $movie_rating && $movie_rating !== $rating ) {
              echo '<option value="' . esc_html( $rating ) . '">' . esc_html( strtoupper( $rating ) ) . '</option>';
            }
          }
        ?>
      </select>
    </div>
  </div>
  <br/>

  <label for="movie_description">Description</label>
  <textarea id="movie_description" name="movie_description" rows="5" cols="30" style="display: block; width: 100%;"><?php echo esc_attr( $movie_description ); ?></textarea>

<?php
}

add_action( 'save_post', 'save_movie_data' );

function save_movie_data( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;
  if ( ! isset( $_POST['movie_data_nonce'] ) || ! wp_verify_nonce( $_POST['movie_data_nonce'], 'praxent_movie_data_nonce' ) )
      return;
  if ( ( isset( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] ) ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return;
    }
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }

  // saves director value
  if ( isset( $_POST['movie_director'] ) ) {
    $movie_director_value = $_POST['movie_director'];
    update_post_meta( $post_id, 'movie_director', $movie_director_value );
  } else {
    delete_post_meta( $post_id, 'movie_director' );
  }

  // saves release date value
  if ( isset( $_POST['movie_release_date'] ) ) {
    $movie_release_date_value = $_POST['movie_release_date'];
    update_post_meta( $post_id, 'movie_release_date', $movie_release_date_value );
  } else {
    delete_post_meta( $post_id, 'movie_release_date' );
  }

  // saves rating value
  if ( isset( $_POST['movie_rating'] ) ) {
    $movie_rating_value = $_POST['movie_rating'];
    update_post_meta( $post_id, 'movie_rating', $movie_rating_value );
  } else {
    delete_post_meta( $post_id, 'movie_rating' );
  }

  // saves description value
  if ( isset( $_POST['movie_description'] ) ) {
    $movie_description_value = $_POST['movie_description'];
    update_post_meta( $post_id, 'movie_description', $movie_description_value );
  } else {
    delete_post_meta( $post_id, 'movie_description' );
  }
}
