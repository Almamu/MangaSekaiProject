<?php declare(strict_types=1);
    namespace MangaSekai\AniList;
    
    class Matcher implements \MangaSekai\Media\Matcher
    {
        const ANILIST_URL = 'https://anilist.co/graphql';
        
        private function buildGraphQLmatch (): string
        {
            return '
query (
    $page: Int = 1,
    $type: MediaType,
    $isAdult: Boolean = false,
    $search: String,
    $format: MediaFormat
    $status: MediaStatus,
    $countryOfOrigin: CountryCode,
    $source: MediaSource,
    $season: MediaSeason,
    $year: String,
    $onList: Boolean,
    $yearLesser: FuzzyDateInt,
    $yearGreater: FuzzyDateInt,
    $licensedBy: [String],
    $includedGenres: [String],
    $excludedGenres: [String],
    $includedTags: [String],
    $excludedTags: [String],
    $sort: [MediaSort] = [SCORE_DESC, POPULARITY_DESC]
) {
    Page (page: $page, perPage: 20) {
        pageInfo {
            total
            perPage
            currentPage
            lastPage
            hasNextPage
        }
        ANIME: media (
            type: $type,
            season: $season,
            format: $format,
            status: $status,
            countryOfOrigin: $countryOfOrigin,
            source: $source,
            search: $search,
            onList: $onList,
            startDate_like: $year,
            startDate_lesser: $yearLesser,
            startDate_greater: $yearGreater,
            licensedBy_in: $licensedBy,
            genre_in: $includedGenres,
            genre_not_in: $excludedGenres,
            tag_in: $includedTags,
            tag_not_in: $excludedTags,
            sort: $sort,
            isAdult: $isAdult
        ) {
            id
            title {
                userPreferred
            }
            coverImage {
                large: extraLarge
                color
            }
            startDate {
                year
                month
                day
            }
            endDate {
                year
                month
                day
            }
            season
            description
            type
            format
            status
            genres
            isAdult
            averageScore
            popularity
            mediaListEntry {
                status
            }
            nextAiringEpisode {
                airingAt
                timeUntilAiring
                episode
            }
            studios (isMain: true) {
                edges {
                    isMain
                    node {
                        id
                        name
                    }
                }
            }
        }
    }
}';
        }
        
        /**
         * @param string $search The serie's name to search
         *
         * @return array List of results sorted by match
         * @throws \Exception
         */
        function match (string $search): array
        {
            $request = array (
                'variables' => array (
                    'page' => 1,
                    'type' => 'MANGA',
                    'search' => $search,
                    'sort' => 'SEARCH_MATCH'
                ),
                'query' => $this->buildGraphQLmatch ()
            );
            
            $json_request = json_encode ($request);
            
            $curl = curl_init (self::ANILIST_URL);
            
            curl_setopt ($curl, CURLOPT_POST, true);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, $json_request);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($curl, CURLOPT_HTTPHEADER, array (
                'Content-Type: application/json'
            ));
            
            $result = curl_exec ($curl);
            
            if ($result === false)
            {
                curl_close ($curl);
                throw new \Exception ('Cannot request manga information');
            }
            
            curl_close ($curl);
            
            $resultList = array ();
            
            foreach ($result ['data'] ['Page'] ['ANIME'] as $series)
            {
                $startDate = $series ['startDate'] ['year'] . '/' . $series ['startDate'] ['month'] . '/' . $series ['startDate'] ['day'];
                $endDate = '';
                
                if (is_null ($series ['endDate'] ['year']) == false)
                {
                    $endDate = $series ['endDate'] ['year'] . '/' . $series ['endDate'] ['month'] . '/' . $series ['endDate'] ['day'];
                }
                
                $resultList [] = array (
                    'id'            => $series ['id'],
                    'name'          => $series ['title'] ['userPreferred'],
                    'cover'         => $series ['cover'] ['large'],
                    'description'   => $series ['description'],
                    'genres'        => $series ['genres'],
                    'score'         => $series ['averageScore'],
                    'start'         => $startDate,
                    'end'           => $endDate
                );
            }
            
            return $resultList;
        }
    };