<?php
/**
 * Plugin Name: Sonic Cleanup Wizard
 * Plugin URI: https://github.com/ogichanchan/sonic-cleanup-wizard
 * Description: A unique PHP-only WordPress utility. A sonic style cleanup plugin acting as a wizard. Focused on simplicity and efficiency.
 * Version: 1.0.0
 * Author: ogichanchan
 * Author URI: https://github.com/ogichanchan
 * License: GPLv2 or later
 * Text Domain: sonic-cleanup-wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Sonic_Cleanup_Wizard Class
 *
 * Implements a single-file WordPress cleanup utility.
 * It provides an admin interface to perform various database cleanup tasks.
 */
class Sonic_Cleanup_Wizard {

    /**
     * Constructor.
     * Initializes the plugin by setting up hooks.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_post_sonic_cleanup_action', array( $this, 'process_cleanup_action' ) );
        add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
    }

    /**
     * Add the plugin's admin menu item under "Tools".
     */
    public function add_admin_menu() {
        add_management_page(
            esc_html__( 'Sonic Cleanup Wizard', 'sonic-cleanup-wizard' ),
            esc_html__( 'Sonic Cleanup', 'sonic-cleanup-wizard' ),
            'manage_options',
            'sonic-cleanup-wizard',
            array( $this, 'display_admin_page' )
        );
    }

    /**
     * Display the plugin's admin page content.
     * Includes inline CSS and JavaScript for a self-contained experience.
     */
    public function display_admin_page() {
        // Check user capability.
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $page_title = esc_html__( 'Sonic Cleanup Wizard', 'sonic-cleanup-wizard' );
        ?>
        <div class="wrap sonic-cleanup-wizard-wrap">
            <h1><?php echo $page_title; ?></h1>

            <style type="text/css">
                /* Inline CSS for the Sonic Cleanup Wizard */
                .sonic-cleanup-wizard-wrap {
                    font-family: -apple-system, BlinkMacMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                    background: #f0f2f5;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                    max-width: 960px;
                    margin-top: 20px;
                }
                .sonic-cleanup-wizard-wrap h1 {
                    color: #0073aa; /* WordPress primary blue */
                    font-size: 2em;
                    margin-bottom: 25px;
                    text-align: center;
                }
                .sonic-cleanup-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 25px;
                    margin-top: 30px;
                }
                .sonic-cleanup-card {
                    background: #fff;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    padding: 25px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.03);
                    text-align: center;
                    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                }
                .sonic-cleanup-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
                }
                .sonic-cleanup-card h2 {
                    color: #2c3e50;
                    font-size: 1.5em;
                    margin-top: 0;
                    margin-bottom: 15px;
                }
                .sonic-cleanup-card p {
                    color: #555;
                    font-size: 0.95em;
                    line-height: 1.6;
                    margin-bottom: 20px;
                }
                .sonic-cleanup-card form {
                    margin-top: 20px;
                }
                .sonic-cleanup-button {
                    background: #0073aa; /* Sonic Blue */
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    padding: 12px 25px;
                    font-size: 1em;
                    cursor: pointer;
                    transition: background 0.3s ease;
                    text-decoration: none; /* For potential future links */
                    display: inline-block;
                    line-height: 1;
                }
                .sonic-cleanup-button:hover {
                    background: #005f8d; /* Darker blue */
                }
                .sonic-cleanup-button.red {
                    background: #dc3232; /* Red for more dangerous actions */
                }
                .sonic-cleanup-button.red:hover {
                    background: #bb2f2f;
                }
                .sonic-cleanup-button.yellow {
                    background: #ffb900; /* Sonic Yellow */
                    color: #333;
                }
                .sonic-cleanup-button.yellow:hover {
                    background: #e6a700;
                }
            </style>

            <script type="text/javascript">
                // Inline JavaScript for confirmation dialogs.
                document.addEventListener('DOMContentLoaded', function() {
                    var cleanupForms = document.querySelectorAll('.sonic-cleanup-form');
                    cleanupForms.forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            var confirmationMessage = this.getAttribute('data-confirmation-message');
                            if (!confirm(confirmationMessage)) {
                                event.preventDefault();
                            }
                        });
                    });
                });
            </script>

            <div class="sonic-cleanup-grid">

                <!-- Card: Post Revisions -->
                <div class="sonic-cleanup-card">
                    <h2><?php esc_html_e( 'Delete Post Revisions', 'sonic-cleanup-wizard' ); ?></h2>
                    <p><?php esc_html_e( 'Removes old versions of posts and pages, reducing database size. This helps keep your site lean.', 'sonic-cleanup-wizard' ); ?></p>
                    <form class="sonic-cleanup-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirmation-message="<?php esc_attr_e( 'Are you sure you want to delete ALL post revisions? This action cannot be undone.', 'sonic-cleanup-wizard' ); ?>">
                        <?php wp_nonce_field( 'sonic_cleanup_revisions_nonce', 'sonic_cleanup_nonce' ); ?>
                        <input type="hidden" name="action" value="sonic_cleanup_action">
                        <input type="hidden" name="cleanup_type" value="revisions">
                        <button type="submit" class="sonic-cleanup-button"><?php esc_html_e( 'Clean Revisions', 'sonic-cleanup-wizard' ); ?></button>
                    </form>
                </div>

                <!-- Card: Spam Comments -->
                <div class="sonic-cleanup-card">
                    <h2><?php esc_html_e( 'Delete Spam Comments', 'sonic-cleanup-wizard' ); ?></h2>
                    <p><?php esc_html_e( 'Permanently removes all comments marked as spam. A quick way to clear out junk comments.', 'sonic-cleanup-wizard' ); ?></p>
                    <form class="sonic-cleanup-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirmation-message="<?php esc_attr_e( 'Are you sure you want to delete ALL spam comments? This action cannot be undone.', 'sonic-cleanup-wizard' ); ?>">
                        <?php wp_nonce_field( 'sonic_cleanup_spam_comments_nonce', 'sonic_cleanup_nonce' ); ?>
                        <input type="hidden" name="action" value="sonic_cleanup_action">
                        <input type="hidden" name="cleanup_type" value="spam_comments">
                        <button type="submit" class="sonic-cleanup-button red"><?php esc_html_e( 'Clean Spam', 'sonic-cleanup-wizard' ); ?></button>
                    </form>
                </div>

                <!-- Card: Unapproved Comments -->
                <div class="sonic-cleanup-card">
                    <h2><?php esc_html_e( 'Delete Unapproved Comments', 'sonic-cleanup-wizard' ); ?></h2>
                    <p><?php esc_html_e( 'Removes all comments that are pending approval. Be careful, this deletes comments that might be legitimate.', 'sonic-cleanup-wizard' ); ?></p>
                    <form class="sonic-cleanup-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirmation-message="<?php esc_attr_e( 'Are you sure you want to delete ALL unapproved comments? This action cannot be undone.', 'sonic-cleanup-wizard' ); ?>">
                        <?php wp_nonce_field( 'sonic_cleanup_unapproved_comments_nonce', 'sonic_cleanup_nonce' ); ?>
                        <input type="hidden" name="action" value="sonic_cleanup_action">
                        <input type="hidden" name="cleanup_type" value="unapproved_comments">
                        <button type="submit" class="sonic-cleanup-button red"><?php esc_html_e( 'Clean Unapproved', 'sonic-cleanup-wizard' ); ?></button>
                    </form>
                </div>

                <!-- Card: Expired Transients -->
                <div class="sonic-cleanup-card">
                    <h2><?php esc_html_e( 'Delete Expired Transients', 'sonic-cleanup-wizard' ); ?></h2>
                    <p><?php esc_html_e( 'Removes temporary data that is no longer needed, improving performance. Generally safe to delete.', 'sonic-cleanup-wizard' ); ?></p>
                    <form class="sonic-cleanup-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirmation-message="<?php esc_attr_e( 'Are you sure you want to delete ALL expired transients? This action is generally safe.', 'sonic-cleanup-wizard' ); ?>">
                        <?php wp_nonce_field( 'sonic_cleanup_expired_transients_nonce', 'sonic_cleanup_nonce' ); ?>
                        <input type="hidden" name="action" value="sonic_cleanup_action">
                        <input type="hidden" name="cleanup_type" value="expired_transients">
                        <button type="submit" class="sonic-cleanup-button yellow"><?php esc_html_e( 'Clean Transients', 'sonic-cleanup-wizard' ); ?></button>
                    </form>
                </div>

                <!-- Card: Orphaned Post Meta -->
                <div class="sonic-cleanup-card">
                    <h2><?php esc_html_e( 'Delete Orphaned Post Meta', 'sonic-cleanup-wizard' ); ?></h2>
                    <p><?php esc_html_e( 'Removes post metadata entries that no longer belong to an existing post. These are usually safe to remove.', 'sonic-cleanup-wizard' ); ?></p>
                    <form class="sonic-cleanup-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirmation-message="<?php esc_attr_e( 'Are you sure you want to delete orphaned post meta? This action cannot be undone.', 'sonic-cleanup-wizard' ); ?>">
                        <?php wp_nonce_field( 'sonic_cleanup_orphaned_post_meta_nonce', 'sonic_cleanup_nonce' ); ?>
                        <input type="hidden" name="action" value="sonic_cleanup_action">
                        <input type="hidden" name="cleanup_type" value="orphaned_post_meta">
                        <button type="submit" class="sonic-cleanup-button red"><?php esc_html_e( 'Clean Orphaned Post Meta', 'sonic-cleanup-wizard' ); ?></button>
                    </form>
                </div>

                <!-- Card: Orphaned Comment Meta -->
                <div class="sonic-cleanup-card">
                    <h2><?php esc_html_e( 'Delete Orphaned Comment Meta', 'sonic-cleanup-wizard' ); ?></h2>
                    <p><?php esc_html_e( 'Removes comment metadata entries that no longer belong to an existing comment. These are typically safe to remove.', 'sonic-cleanup-wizard' ); ?></p>
                    <form class="sonic-cleanup-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirmation-message="<?php esc_attr_e( 'Are you sure you want to delete orphaned comment meta? This action cannot be undone.', 'sonic-cleanup-wizard' ); ?>">
                        <?php wp_nonce_field( 'sonic_cleanup_orphaned_comment_meta_nonce', 'sonic_cleanup_nonce' ); ?>
                        <input type="hidden" name="action" value="sonic_cleanup_action">
                        <input type="hidden" name="cleanup_type" value="orphaned_comment_meta">
                        <button type="submit" class="sonic-cleanup-button red"><?php esc_html_e( 'Clean Orphaned Comment Meta', 'sonic-cleanup-wizard' ); ?></button>
                    </form>
                </div>

            </div> <!-- .sonic-cleanup-grid -->
        </div> <!-- .wrap -->
        <?php
    }

    /**
     * Process cleanup actions submitted via POST request.
     * Handles nonce verification, capability checks, and calls the appropriate cleanup method.
     */
    public function process_cleanup_action() {
        // Check user capability.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'sonic-cleanup-wizard' ) );
        }

        $cleanup_type = isset( $_POST['cleanup_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cleanup_type'] ) ) : '';
        $nonce_action = 'sonic_cleanup_' . $cleanup_type . '_nonce';

        // Verify nonce.
        if ( ! isset( $_POST['sonic_cleanup_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['sonic_cleanup_nonce'] ), $nonce_action ) ) {
            wp_die( esc_html__( 'Security check failed. Please try again.', 'sonic-cleanup-wizard' ) );
        }

        $success_message = '';
        $error_message = '';
        $count = 0; // Initialize count for cleanup operations.

        switch ( $cleanup_type ) {
            case 'revisions':
                $count = $this->_cleanup_revisions();
                if ( false !== $count ) {
                    $success_message = sprintf( esc_html__( 'Successfully deleted %d post revisions!', 'sonic-cleanup-wizard' ), $count );
                } else {
                    $error_message = esc_html__( 'Failed to delete post revisions.', 'sonic-cleanup-wizard' );
                }
                break;
            case 'spam_comments':
                $count = $this->_cleanup_spam_comments();
                if ( false !== $count ) {
                    $success_message = sprintf( esc_html__( 'Successfully deleted %d spam comments!', 'sonic-cleanup-wizard' ), $count );
                } else {
                    $error_message = esc_html__( 'Failed to delete spam comments.', 'sonic-cleanup-wizard' );
                }
                break;
            case 'unapproved_comments':
                $count = $this->_cleanup_unapproved_comments();
                if ( false !== $count ) {
                    $success_message = sprintf( esc_html__( 'Successfully deleted %d unapproved comments!', 'sonic-cleanup-wizard' ), $count );
                } else {
                    $error_message = esc_html__( 'Failed to delete unapproved comments.', 'sonic-cleanup-wizard' );
                }
                break;
            case 'expired_transients':
                $count = $this->_cleanup_expired_transients();
                if ( false !== $count ) {
                    $success_message = sprintf( esc_html__( 'Successfully deleted %d expired transients (and their timeouts)!', 'sonic-cleanup-wizard' ), $count );
                } else {
                    $error_message = esc_html__( 'Failed to delete expired transients.', 'sonic-cleanup-wizard' );
                }
                break;
            case 'orphaned_post_meta':
                $count = $this->_cleanup_orphaned_post_meta();
                if ( false !== $count ) {
                    $success_message = sprintf( esc_html__( 'Successfully deleted %d orphaned post meta entries!', 'sonic-cleanup-wizard' ), $count );
                } else {
                    $error_message = esc_html__( 'Failed to delete orphaned post meta.', 'sonic-cleanup-wizard' );
                }
                break;
            case 'orphaned_comment_meta':
                $count = $this->_cleanup_orphaned_comment_meta();
                if ( false !== $count ) {
                    $success_message = sprintf( esc_html__( 'Successfully deleted %d orphaned comment meta entries!', 'sonic-cleanup-wizard' ), $count );
                } else {
                    $error_message = esc_html__( 'Failed to delete orphaned comment meta.', 'sonic-cleanup-wizard' );
                }
                break;
            default:
                $error_message = esc_html__( 'Unknown cleanup action requested.', 'sonic-cleanup-wizard' );
                break;
        }

        // Store result in a transient to display on the next page load.
        if ( ! empty( $success_message ) ) {
            set_transient( 'sonic_cleanup_wizard_message', array( 'type' => 'success', 'message' => $success_message ), 30 );
        } elseif ( ! empty( $error_message ) ) {
            set_transient( 'sonic_cleanup_wizard_message', array( 'type' => 'error', 'message' => $error_message ), 30 );
        }

        // Redirect back to the plugin page.
        wp_safe_redirect( add_query_arg( 'page', 'sonic-cleanup-wizard', admin_url( 'tools.php' ) ) );
        exit;
    }

    /**
     * Display admin notices based on transient data.
     * Notices are set after a cleanup action and retrieved here.
     */
    public function display_admin_notices() {
        if ( current_user_can( 'manage_options' ) ) {
            $message_data = get_transient( 'sonic_cleanup_wizard_message' );
            if ( $message_data && is_array( $message_data ) ) {
                $type = sanitize_text_field( $message_data['type'] );
                // Message is already escaped in process_cleanup_action via sprintf/esc_html__.
                $message = $message_data['message']; 
                ?>
                <div class="notice notice-<?php echo esc_attr( $type ); ?> is-dismissible">
                    <p><strong><?php echo $message; ?></strong></p>
                </div>
                <?php
                delete_transient( 'sonic_cleanup_wizard_message' );
            }
        }
    }

    /**
     * Deletes all post revisions.
     *
     * @return int|false Number of revisions deleted, or false on failure.
     */
    private function _cleanup_revisions() {
        global $wpdb;
        $query = $wpdb->prepare(
            "DELETE FROM {$wpdb->posts} WHERE post_type = %s",
            'revision'
        );
        $result = $wpdb->query( $query );
        return ( false !== $result ) ? (int) $result : false;
    }

    /**
     * Deletes all comments marked as spam.
     *
     * @return int|false Number of spam comments deleted, or false on failure.
     */
    private function _cleanup_spam_comments() {
        global $wpdb;
        $query = $wpdb->prepare(
            "DELETE FROM {$wpdb->comments} WHERE comment_approved = %s",
            'spam'
        );
        $result = $wpdb->query( $query );
        return ( false !== $result ) ? (int) $result : false;
    }

    /**
     * Deletes all unapproved comments.
     *
     * @return int|false Number of unapproved comments deleted, or false on failure.
     */
    private function _cleanup_unapproved_comments() {
        global $wpdb;
        $query = $wpdb->prepare(
            "DELETE FROM {$wpdb->comments} WHERE comment_approved = %s",
            '0'
        );
        $result = $wpdb->query( $query );
        return ( false !== $result ) ? (int) $result : false;
    }

    /**
     * Deletes all expired transients and their corresponding timeout options.
     *
     * @return int|false Number of option rows deleted, or false on failure.
     */
    private function _cleanup_expired_transients() {
        global $wpdb;
        $deleted_count = 0;

        // Get the names of expired transient timeout options.
        // We look for _transient_timeout_ options whose value (expiration timestamp) is in the past.
        $expired_timeout_options = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s AND option_value < %s",
                $wpdb->esc_like( '_transient_timeout_%' ) . '%',
                time()
            )
        );

        if ( empty( $expired_timeout_options ) ) {
            return 0; // No expired transient timeouts found.
        }

        // Prepare the list of corresponding actual transient names (by replacing '_timeout_' with '_')
        $transient_names_to_delete = array();
        foreach ( $expired_timeout_options as $timeout_option_name ) {
            // Convert '_transient_timeout_foo' to '_transient_foo'
            $transient_names_to_delete[] = str_replace( '_transient_timeout_', '_transient_', $timeout_option_name );
        }

        // Combine both sets of option names for a single DELETE query.
        $all_option_names_to_delete = array_merge( $expired_timeout_options, $transient_names_to_delete );

        // If there are options to delete, proceed.
        if ( ! empty( $all_option_names_to_delete ) ) {
            // Create placeholders for the IN clause.
            $placeholders = implode( ',', array_fill( 0, count( $all_option_names_to_delete ), '%s' ) );

            // Construct and execute the DELETE query.
            $delete_query = $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name IN ($placeholders)",
                $all_option_names_to_delete
            );

            $result = $wpdb->query( $delete_query );

            if ( false !== $result ) {
                $deleted_count = (int) $result;
            } else {
                return false; // Query failed.
            }
        }
        return $deleted_count;
    }

    /**
     * Deletes orphaned post meta entries.
     * An orphaned post meta entry is one that refers to a post ID that no longer exists.
     *
     * @return int|false Number of orphaned post meta entries deleted, or false on failure.
     */
    private function _cleanup_orphaned_post_meta() {
        global $wpdb;
        $query = "
            DELETE pm
            FROM {$wpdb->postmeta} pm
            LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE p.ID IS NULL;
        ";
        $result = $wpdb->query( $query );
        return ( false !== $result ) ? (int) $result : false;
    }

    /**
     * Deletes orphaned comment meta entries.
     * An orphaned comment meta entry is one that refers to a comment ID that no longer exists.
     *
     * @return int|false Number of orphaned comment meta entries deleted, or false on failure.
     */
    private function _cleanup_orphaned_comment_meta() {
        global $wpdb;
        $query = "
            DELETE cm
            FROM {$wpdb->commentmeta} cm
            LEFT JOIN {$wpdb->comments} c ON c.comment_ID = cm.comment_id
            WHERE c.comment_ID IS NULL;
        ";
        $result = $wpdb->query( $query );
        return ( false !== $result ) ? (int) $result : false;
    }
}

// Initialize the plugin.
new Sonic_Cleanup_Wizard();