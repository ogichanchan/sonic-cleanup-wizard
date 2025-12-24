=== Sonic Cleanup Wizard ===
Contributors: ogichanchan
Tags: wordpress, plugin, tool, admin, performance, cleanup, database, optimization
Requires at least: 6.2
Tested up to: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
The Sonic Cleanup Wizard is a unique, PHP-only WordPress utility designed for simplicity and efficiency in database cleanup. This single-file plugin provides a straightforward admin interface under the "Tools" menu, allowing site administrators to perform various database optimization tasks with ease.

Key cleanup functionalities include:
*   **Delete Post Revisions:** Removes old versions of posts and pages to reduce database bloat.
*   **Delete Spam Comments:** Permanently deletes all comments marked as spam.
*   **Delete Unapproved Comments:** Clears out comments awaiting moderation.
*   **Delete Expired Transients:** Removes temporary data that is no longer needed, improving site performance.
*   **Delete Orphaned Post Meta:** Deletes post metadata entries that no longer link to an existing post.
*   **Delete Orphaned Comment Meta:** Removes comment metadata entries that no longer link to an existing comment.

Each cleanup action includes a confirmation prompt to prevent accidental data loss. The plugin is focused on providing essential cleanup tasks in a user-friendly, self-contained package.

This plugin is open source. Report bugs at: https://github.com/ogichanchan/sonic-cleanup-wizard

== Installation ==
1. Upload the `sonic-cleanup-wizard.php` file to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access the cleanup utility via 'Tools > Sonic Cleanup' in your WordPress admin dashboard.

== Changelog ==
= 1.0.0 =
* Initial release.