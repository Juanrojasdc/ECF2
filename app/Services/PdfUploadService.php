<?php

class PdfUploadService
{
    private const MAX_SIZE = 5 * 1024 * 1024;

    private const ALLOWED_MIME_TYPES = [
        'application/pdf' => 'pdf'
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
                'Erreur lors du téléchargement du justificatif.'
            );
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new RuntimeException(
                'Le justificatif ne doit pas dépasser 5 Mo.'
            );
        }

        // Check file content rather than the client-provided extension
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!isset(self::ALLOWED_MIME_TYPES[$mimeType])) {
            throw new RuntimeException(
                'Seuls les fichiers PDF sont autorisés.'
            );
        }

        // Generate the storage name and extension on the server
        $extension = self::ALLOWED_MIME_TYPES[$mimeType];

        $fileName = bin2hex(random_bytes(16))
            . '.'
            . $extension;

        $directory =
            __DIR__ . '/../../storage/uploads/justifications/';

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $destination = $directory . $fileName;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            throw new RuntimeException(
                'Impossible d’enregistrer le justificatif.'
            );
        }

        return 'storage/uploads/justifications/' . $fileName;
    }
}