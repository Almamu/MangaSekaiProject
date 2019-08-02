<?php declare(strict_types=1);
    namespace MangaSekai\Media;

    /**
     * Interface that any metadata-matcher should inherit to properly download and interpret
     *
     * @package MangaSekai\Media
     * @author Alexis Maiquez <almamu@almamu.com>
     */
    interface Matcher
    {
        /**
         * @param string $search The serie's name to search
         *
         * @return Match[]
         */
        function match (string $search): array;
    };