<?php declare(strict_types=1);
    namespace MangaSekai\Media;
    
    class Match
    {
        /** @var string Visible name for the match */
        private $name;
        /** @var string URL for the manga's cover */
        private $cover;
        /** @var string Description for the manga */
        private $description;
        /** @var array List of genres */
        private $genres;
        /** @var int Average score */
        private $score;
        /** @var string Start date */
        private $start;
        /** @var string End date */
        private $end;
        /** @var array Extra info for the matcher that found this manga */
        private $extrainfo;
        
        public function __construct (string $name, string $cover, string $description, array $genres, int $score, string $start, string $end, array $extrainfo = array ())
        {
            $this->name = $name;
            $this->cover = $cover;
            $this->description = $description;
            $this->genres = $genres;
            $this->score = $score;
            $this->start = $start;
            $this->end = $end;
            $this->extrainfo = $extrainfo;
        }
    
        /**
         * @return string
         */
        public function getName (): string
        {
            return $this->name;
        }
    
        /**
         * @return string
         */
        public function getCover (): string
        {
            return $this->cover;
        }
    
        /**
         * @return string
         */
        public function getDescription (): string
        {
            return $this->description;
        }
    
        /**
         * @return array
         */
        public function getGenres (): array
        {
            return $this->genres;
        }
    
        /**
         * @return int
         */
        public function getScore (): int
        {
            return $this->score;
        }
    
        /**
         * @return string
         */
        public function getStart (): string
        {
            return $this->start;
        }
    
        /**
         * @return string
         */
        public function getEnd (): string
        {
            return $this->end;
        }
    
        /**
         * @return array
         */
        public function getExtrainfo (): array
        {
            return $this->extrainfo;
        }
        
    };