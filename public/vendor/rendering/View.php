<?php

namespace vendor\rendering;

/**
 * Abstract View class for handling template rendering and view composition
 * Provides functionality for rendering views with layouts, components, and dynamic content
 */
abstract class View
{
    // Default template file (without .php extension)
    protected static string $temp = BASE_TEMPLATE;

    // Template section name where page title will be placed
    protected static string $title_section = "title";

    // Current page title
    protected static string $title = "";

    // Template section name where main content will be placed
    protected static string $content_section = "content";

    // Context data array passed to views and components
    protected static array $context = [];

    /**
     * Outputs the content section placeholder or sets a custom section name
     *
     * @param string|null $section_name Optional custom section name for content
     */
    public static function content(string $section_name = null) : void {
        if ($section_name) {
            self::$content_section = $section_name;
        }
        // Output placeholder that will be replaced during rendering
        echo "{{!".self::$content_section."!}}";
    }

    /**
     * Sets or changes the active template
     *
     * @param string|null $template Template name (without .php extension)
     */
    public static function template(string $template = null) : void {
        if ($template) {
            self::$temp = $template;
        }
    }

    /**
     * Outputs the title section placeholder or sets a custom section name
     *
     * @param string|null $section_name Optional custom section name for title
     */
    public static function title_section(string $section_name = null) : void {
        if ($section_name) {
            self::$title_section = $section_name;
        }
        // Output placeholder that will be replaced during rendering
        echo "{{!".self::$title_section."!}}";
    }

    /**
     * Sets or retrieves the current page title
     *
     * @param string|null $page_name Optional page title to set
     * @return string Current page title
     */
    public static function title(string $page_name = null) : string {
        if ($page_name) {
            self::$title = $page_name;
        }
        return self::$title;
    }

    /**
     * Includes a reusable component with optional data
     *
     * @param string $path Component path relative to components directory
     * @param array $data Additional data to pass to the component
     */
    public static function IncludeComponent(string $path, array $data = []) : void
    {
        $dir = $_SERVER["BASE_DIR"].COMPONENTS_DIR."/".$path.".php";

        if (file_exists($dir)) {
            // Extract context and component data for use in the component
            extract(self::$context);
            extract($data);
            include $dir;
        } else {
            // Display error if component file doesn't exist
            echo "No such file: ".$dir;
        }
    }

    /**
     * Renders a view with optional data, wrapped in a template
     *
     * @param string $view View name (without .php extension)
     * @param array $data Data to pass to the view
     */
    public static function render(string $view, array $data = []) : void
    {
        // Store and extract data for the view
        if (!empty($data)) {
            self::$context = $data;
            extract($data);
        }

        // First: render the view file to execute all setup (title, template, etc.)
        $view_dir = $_SERVER["BASE_DIR"].VIEW_DIR."/".$view.".php";

        if (file_exists($view_dir)) {
            ob_start(); // Start output buffering for view
            include $view_dir; // Execute view file
            $view_content = ob_get_clean(); // Capture view output
        } else {
            echo "No such view: ".$view_dir;
            return;
        }

        // Second: render the template file (all view setup is already done)
        $template_dir = $_SERVER["BASE_DIR"].TEMPLATES_DIR."/".self::$temp.".php";

        if (file_exists($template_dir)) {
            ob_start(); // Start output buffering for template
            include $template_dir; // Execute template file
            $output = ob_get_clean(); // Capture template output
        } else {
            echo "No such template: ".$template_dir;
            return;
        }

        // Replace content placeholder with actual view content
        $output = str_replace('{{!'.self::$content_section.'!}}', $view_content, $output);

        // Replace title placeholder with actual page title
        $output = str_replace('{{!'.self::$title_section.'!}}', self::$title, $output);

        // Output the final rendered HTML
        echo $output;
    }
}