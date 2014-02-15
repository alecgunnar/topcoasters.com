<?php

namespace Application\Lib;

class Utility {
	/**
	 * Standard error messages
	 *
	 * @var array
	 */
	private static $errorMessages = array('no_login' => 'You must be logged in to access this page.');

    /**
     * Generates an seo title
     *
     * @param  string  $name
     * @param  integer $id=''
     * @return string
     */
    public static function generateSeoTitle($name) {
        $seoTitle  = '';
        $maxLength = 50;

        $name = preg_replace(array('~[^a-z0-9 ]~i', '~&#?[a-z0-9]+;~i'), '', html_entity_decode($name, ENT_QUOTES));

        $expName = explode(' ', $name);
        $length  = 0;

        foreach($expName as $word) {
            $length += strlen($word) + 1;

            if($length <= $maxLength) {
                $seoTitle .= $word . '-';
            }
        }

        $seoTitle = trim($seoTitle, '-');

        return strtolower($seoTitle);
    }

    /**
     * Shows the error page
     *
     * @param string $errorMessage=''
     */
    public static function showError($errorMessage='') {
        $errorMessage = $errorMessage ?: 'There was an error';

		if(array_key_exists($errorMessage, self::$errorMessages)) {
			$errorMessage = self::$errorMessages[$errorMessage];
		}

        \Maverick\Lib\Router::loadController('Errors_Page', array('message' => $errorMessage))->printOut();

        exit;
    }

    /**
     * Gets a numbers for pagination
     *
     * @param  integer $totalResults
     * @param  integer $maxPerPage
     * @param  integer $currentPage
     * @return array
     */
    public static function calculatePagination($totalResults, $maxPerPage, $currentPage) {
        $pages = ceil($totalResults / $maxPerPage);

        if($currentPage > $pages) {
            $currentPage = $pages;
        }

        if($currentPage < 1) {
            $currentPage = 1;
        }

        $start = $maxPerPage * ($currentPage - 1);

        return array($pages, $currentPage, $start);
    }

    /**
     * Builds the links for the pagination
     *
     * @param  integer $page
     * @param  integer $pages
     * @return array
     */
    public static function getPaginationLinks($url, $page, $pages) {
        $links = array();

        if($page > 1) {
            $links[] = array(sprintf($url, $page - 1), '&larr; Prev');
        }

        if($page < $pages) {
            $links[] = array(sprintf($url, $page + 1), 'Next &rarr;');
        }

        return array('page'  => $page,
                     'pages' => $pages,
                     'links' => $links);
    }

    public static function checkFileAtUrl($url) {
        $ch = curl_init($url);    
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        } else {
            $status = false;
        }

        curl_close($ch);

        return $status;
    }
}