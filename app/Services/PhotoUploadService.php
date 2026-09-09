<?php

class PhotoUploadService
{
    private const MAX_SIZE = 2 * 1024 * 1024;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    public static function upload(?array $file): ?string
    {
        if (
            $file === null ||
            ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                'Erreur lors du téléchargement de la photo.'
            );
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new RuntimeException(
                'La photo ne doit pas dépasser 2 Mo.'
            );
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!isset(self::ALLOWED_MIME_TYPES[$mimeType])) {
            throw new RuntimeException(
                'Format de photo non autorisé.'
            );
        }

        $extension = self::ALLOWED_MIME_TYPES[$mimeType];

        $fileName = bin2hex(random_bytes(16))
            . '.'
            . $extension;

        $directory =
            __DIR__ . '/../../public/assets/images/trainees/';

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $destination = $directory . $fileName;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            throw new RuntimeException(
                'Impossible d’enregistrer la photo.'
            );
        }

        return 'assets/images/trainees/' . $fileName;
    }
}