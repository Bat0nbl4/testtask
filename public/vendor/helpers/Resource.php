<?php

namespace vendor\helpers;

/**
 * Resource path resolver helper class
 * Constructs full paths to resource files with optional base path prefix
 */
abstract class Resource
{
    /**
     * Builds full filesystem path to a resource
     * Combines resource base path with relative path and optional application base path
     *
     * @param string $path Relative path to resource from resource directory
     * @return string Full absolute path to the resource
     */
    public static function get(string $path) : string {
        // Start with base resource path
        $full_path = RESOURCE_PATH."/".$path;

        // Prepend application base path if configured
        if (USE_BASE_PATH) {
            $full_path = BASE_PATH.$full_path;
        }

        return $full_path;
    }
}