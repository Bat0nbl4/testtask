<?php

/**
 * Abstract utility class for password hashing and data encryption
 * Provides secure methods for one-way password hashing and two-way data encryption
 */
abstract class Hash {
    // Default hashing algorithm (uses bcrypt in current PHP versions)
    private const HASH_ALGO = PASSWORD_DEFAULT;

    // Configuration options for password hashing
    // Higher cost = more secure but slower computation
    private const HASH_OPTIONS = [
        'cost' => 12 // Recommended value: balances security and performance
    ];

    /**
     * Creates a secure one-way hash of a password
     * Uses PHP's built-in password_hash() with bcrypt by default
     * Hash includes salt and algorithm information automatically
     *
     * @param string $password Plain text password to hash
     * @return string Hashed password (60+ characters) ready for database storage
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, self::HASH_ALGO, self::HASH_OPTIONS);
    }

    /**
     * Verifies if a plain text password matches a stored hash
     * Uses timing-attack safe comparison internally
     *
     * @param string $password Plain text password to verify
     * @param string $hash Previously stored password hash
     * @return bool True if password matches the hash
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Checks if a password hash needs to be recreated
     * Useful when hashing algorithm or options have changed
     *
     * @param string $hash Existing password hash to check
     * @return bool True if hash should be recreated with current settings
     */
    public static function needsRehash(string $hash): bool {
        return password_needs_rehash($hash, self::HASH_ALGO, self::HASH_OPTIONS);
    }

    /**
     * Encrypts data using AES-256-CBC symmetric encryption
     * Returns base64-encoded string containing IV + encrypted data
     * IV (Initialization Vector) is randomly generated for each encryption
     *
     * @param string $data Plain text data to encrypt
     * @param string $key Encryption key (keep secure, at least 32 characters recommended)
     * @return string Base64-encoded encrypted data
     * @throws \Exception If encryption fails
     */
    public static function encryptData(string $data, string $key): string {
        // Generate random Initialization Vector for CBC mode
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        // Encrypt data using AES-256-CBC
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

        // Combine IV and encrypted data, then base64 encode for safe storage/transmission
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypts data previously encrypted with encryptData()
     *
     * @param string $data Base64-encoded encrypted data from encryptData()
     * @param string $key Same encryption key used for encryption
     * @return string|false Decrypted plain text or false on failure
     */
    public static function decryptData(string $data, string $key) {
        // Decode base64 and extract IV from beginning of data
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        // Decrypt using same algorithm and key
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}