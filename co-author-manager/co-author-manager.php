<?php
/*
Plugin Name: Co-Authors for The Wesleyan Argus
Description: A simple plugin to manage co-authors for posts.
Version: 1.0
Author: The Wesleyan Argus Web Editors
*/

// Add Co-Author to a post
function add_co_author($post_id, $user_id) {
    $current_authors = get_post_meta($post_id, 'co_author_ids', true);

    // If there are already co-authors, add the new one
    if ($current_authors) {
        $current_authors = explode(',', $current_authors);
        if (!in_array($user_id, $current_authors)) {
            $current_authors[] = $user_id;
        }
    } else {
        // If no co-authors yet, set this as the first
        $current_authors = array($user_id);
    }

    // Save the co-authors
    update_post_meta($post_id, 'co_author_ids', implode(',', $current_authors));
}

// Add the co-author meta box to the post editor
function add_co_author_meta_box() {
    add_meta_box('co_authors', 'Co-Authors', 'co_author_meta_box', 'post', 'side', 'default');
}
add_action('add_meta_boxes', 'add_co_author_meta_box');

// Get users who have editor or admin roles
function get_editors_admins() {
    $args = array(
        'role__in' => array('editor', 'administrator'),  // Get users with the "editor" or "administrator" role
    );
    
    return get_users($args);
}

// Display the co-authors input field in the meta box
function co_author_meta_box($post) {
    // Get current co-authors
    $co_authors = get_post_meta($post->ID, 'co_author_ids', true);
    $co_author_ids = explode(',', $co_authors);
    
    // Get all users
    $users = get_editors_admins();
    echo '<div>';
    foreach ($users as $user) {
        $checked = in_array($user->ID, $co_author_ids) ? 'checked' : '';
        echo '<label>';
        echo '<input type="checkbox" name="co_authors[]" value="' . $user->ID . '" ' . $checked . '>';
        echo ' ' . esc_html($user->display_name);
        echo '</label><br>';
    }
    echo '</div>';
}

// Save the selected co-authors when the post is saved
function save_co_author_meta($post_id) {
    if (isset($_POST['co_authors'])) {
        // Get the currently selected co-authors
        $co_authors = $_POST['co_authors'];
        
        // Convert the array of selected co-authors into a comma-separated string
        $co_authors = implode(',', $co_authors);
        
        // Update the post meta with the new list of co-authors
        update_post_meta($post_id, 'co_author_ids', $co_authors);
    } else {
        // If no co-authors are selected, delete the post meta
        delete_post_meta($post_id, 'co_author_ids');
    }
}
add_action('save_post', 'save_co_author_meta');