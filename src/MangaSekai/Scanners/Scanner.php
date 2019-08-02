<?php declare(strict_types=1);
    namespace MangaSekai\Scanners;

    /**
     * Interface Scanner
     * @package MangaSekai\Scanners
     */
    interface Scanner
    {
        /**
         * Runs the scanner
         */
        function scan (): void;
    };