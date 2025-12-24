1.  **Plugin Name:** Sonic Cleanup Wizard

2.  **Short Description:** A simple, efficient PHP-only WordPress utility providing a wizard-like interface for various database cleanup tasks.

3.  **Detailed Description:**
    The Sonic Cleanup Wizard is a lightweight, PHP-only WordPress utility designed to help you maintain a clean and efficient database. It provides a straightforward, wizard-like interface within your WordPress admin area (under **Tools > Sonic Cleanup**) to perform essential database optimization tasks.

    This plugin focuses on simplicity and efficiency, allowing you to easily manage common sources of database bloat.

    **Key Cleanup Operations Include:**

    *   **Delete Post Revisions:** Removes old versions of posts and pages, significantly reducing database size and keeping your site lean.
    *   **Delete Spam Comments:** Permanently clears out all comments marked as spam, helping to declutter your comment section.
    *   **Delete Unapproved Comments:** Removes all comments that are pending approval. *Caution: Use this feature carefully, as it will delete comments that might be legitimate.*
    *   **Delete Expired Transients:** Removes temporary data that is no longer needed, contributing to improved overall site performance. This action is generally safe to perform.
    *   **Delete Orphaned Post Meta:** Cleans up post metadata entries that no longer belong to an existing post. These are typically safe to remove.
    *   **Delete Orphaned Comment Meta:** Removes comment metadata entries that no longer refer to an existing comment. These are generally safe to remove.

    Each cleanup action is protected by a confirmation prompt to prevent accidental deletions. After an operation, the plugin provides clear admin notices to confirm the success or failure of the task. The Sonic Cleanup Wizard helps you keep your WordPress database optimized for better performance and reduced storage needs.

4.  **GitHub URL:** https://github.com/ogichanchan/sonic-cleanup-wizard