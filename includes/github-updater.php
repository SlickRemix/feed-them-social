<?php namespace feedthemsocial;
/**
 * Github Updater
 *
 * This is useful for testing new features and bug fixes before pushing them to the WordPress repository.
 * It checks the latest version on a specified branch or tag of the GitHub repository and compares it to the local version.
 * If the remote version is newer, it will prompt the user to update the plugin.
 *
 * @version  4.3.4
 * @package  FeedThemSocial/Core
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Github_Updater {
    private $plugin_data;
    private $github_username = 'SlickRemix';
    private $github_repository = 'feed-them-social';
    private $github_token = ''; // Optional: Add a personal access token for private repos.
    private $github_branch_or_tag = '4.3.4'; // Specify the branch or tag to pull updates from, option: master, 4.3.4, etc.

    public function __construct() {
        $this->plugin_file = 'feed-them-social/feed-them-social.php';

        add_filter('pre_set_site_transient_update_plugins', [$this, 'checkForUpdate']);
        add_filter('plugins_api', [$this, 'pluginInfo'], 10, 3);
        add_filter('upgrader_source_selection', [$this, 'renamePluginFolder'], 10, 1);
        add_action('upgrader_process_complete', [$this, 'cleanupTemporaryFolders'], 10, 2);
    }

    public function checkForUpdate($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $this->plugin_data = $this->getPluginData();

        $remote_version = $this->getRemoteVersion();

        if (!$remote_version) {
            return $transient;
        }

        $local_version = $this->plugin_data['Version'];

        if (version_compare($local_version, $remote_version, '<')) {
            $plugin = [
                'slug'        => plugin_basename($this->plugin_file),
                'new_version' => $remote_version,
                'url'         => "https://github.com/{$this->github_username}/{$this->github_repository}",
                'package'     => $this->getDownloadUrl()
            ];

            $transient->response[plugin_basename($this->plugin_file)] = (object) $plugin;
        }

        return $transient;
    }

    public function pluginInfo($result, $action, $args) {
        if ($action !== 'plugin_information' || $args->slug !== plugin_basename($this->plugin_file)) {
            return $result;
        }

        $remote_version = $this->getRemoteVersion();

        if (!$remote_version) {
            return $result;
        }

        $result = (object) [
            'name'          => $this->plugin_data['Name'],
            'slug'          => plugin_basename($this->plugin_file),
            'version'       => $remote_version,
            'author'        => $this->plugin_data['AuthorName'],
            'homepage'      => "https://github.com/{$this->github_username}/{$this->github_repository}",
            'download_link' => $this->getDownloadUrl(),
            'sections'      => [
                'description' => 'This plugin is updated directly from the specified branch or tag.',
            ]
        ];

        return $result;
    }

    public function renamePluginFolder($source) {
        $destination_folder = trailingslashit(WP_PLUGIN_DIR) . $this->github_repository;

        // Step 1: Log the source folder for debugging.
        error_log("Source folder: $source");

        // Step 2: Delete the existing plugin folder if it exists.
        if (is_dir($destination_folder)) {
            if (!$this->deleteFolder($destination_folder)) {
                error_log("Failed to delete existing folder: $destination_folder");
                return $source; // Abort renaming if deletion fails.
            }
        }

        // Step 3: Move the extracted folder to the plugins directory.
        if (rename($source, $destination_folder)) {
            error_log("Folder renamed successfully: $source to $destination_folder");
            return $destination_folder; // Return the new folder path.
        } else {
            error_log("Failed to rename folder: $source to $destination_folder");
            return $source; // Return the original folder path if renaming fails.
        }
    }

    public function cleanupTemporaryFolders($hook_extra) {
        // Ensure $hook_extra is an array and the type is 'plugin'
        if (is_array($hook_extra) && isset($hook_extra['type']) && $hook_extra['type'] === 'plugin') {
            $upgrade_folder = WP_CONTENT_DIR . '/upgrade';
            $folders = glob($upgrade_folder . '/feed-them-social-*', GLOB_ONLYDIR);

            foreach ($folders as $folder) {
                error_log("Cleaning up temporary folder: $folder");
                $this->deleteFolder($folder);
            }
        } else {
            error_log("Unexpected upgrader options");
        }
    }

    private function deleteFolder($folder) {
        if (!is_dir($folder)) {
            return false;
        }

        $files = array_diff(scandir($folder), ['.', '..']);
        foreach ($files as $file) {
            $file_path = $folder . DIRECTORY_SEPARATOR . $file;
            if (is_dir($file_path)) {
                $this->deleteFolder($file_path);
            } else {
                unlink($file_path);
            }
        }

        error_log("Deleting folder: $folder");
        return rmdir($folder);
    }

    private function getPluginData() {
        if (!$this->plugin_data) {
            $this->plugin_data = get_plugin_data($this->plugin_file);
        }
        return $this->plugin_data;
    }

    private function getRemoteVersion() {
        $url = sprintf(
            'https://api.github.com/repos/%s/%s/branches/%s',
            $this->github_username,
            $this->github_repository,
            $this->github_branch_or_tag
        );

        $args = [
            'headers' => array_filter([
                'Accept'        => 'application/vnd.github.v3+json',
                'Authorization' => $this->github_token ? 'token ' . $this->github_token : '',
            ]),
        ];

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            error_log('GitHub API error: ' . $response->get_error_message());
            return false;
        }

        $data = json_decode(wp_remote_retrieve_body($response));

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data->name)) {
            error_log('Invalid JSON from GitHub API: ' . wp_remote_retrieve_body($response));
            return false;
        }

        return $data->name; // Use the branch or tag name as the version.
    }

    private function getDownloadUrl() {
        return sprintf(
            'https://github.com/%s/%s/archive/%s.zip',
            $this->github_username,
            $this->github_repository,
            $this->github_branch_or_tag
        );
    }
}
