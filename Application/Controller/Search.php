<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Search extends \Maverick\Lib\Controller {
    private $resp = <<<EOF
{
 "kind": "customsearch#search",
 "url": {
  "type": "application/json",
  "template": "https://www.googleapis.com/customsearch/v1?q={searchTerms}&num={count?}&start={startIndex?}&lr={language?}&safe={safe?}&cx={cx?}&cref={cref?}&sort={sort?}&filter={filter?}&gl={gl?}&cr={cr?}&googlehost={googleHost?}&c2coff={disableCnTwTranslation?}&hq={hq?}&hl={hl?}&siteSearch={siteSearch?}&siteSearchFilter={siteSearchFilter?}&exactTerms={exactTerms?}&excludeTerms={excludeTerms?}&linkSite={linkSite?}&orTerms={orTerms?}&relatedSite={relatedSite?}&dateRestrict={dateRestrict?}&lowRange={lowRange?}&highRange={highRange?}&searchType={searchType}&fileType={fileType?}&rights={rights?}&imgSize={imgSize?}&imgType={imgType?}&imgColorType={imgColorType?}&imgDominantColor={imgDominantColor?}&alt=json"
 },
 "queries": {
  "nextPage": [
   {
    "title": "Google Custom Search - maverick",
    "totalResults": "53",
    "searchTerms": "maverick",
    "count": 10,
    "startIndex": 11,
    "inputEncoding": "utf8",
    "outputEncoding": "utf8",
    "safe": "off",
    "cx": "012240979130840958263:5p4r96bw5uk"
   }
  ],
  "request": [
   {
    "title": "Google Custom Search - maverick",
    "totalResults": "53",
    "searchTerms": "maverick",
    "count": 10,
    "startIndex": 1,
    "inputEncoding": "utf8",
    "outputEncoding": "utf8",
    "safe": "off",
    "cx": "012240979130840958263:5p4r96bw5uk"
   }
  ]
 },
 "context": {
  "title": "Top Coasters"
 },
 "searchInformation": {
  "searchTime": 0.404367,
  "formattedSearchTime": "0.40",
  "totalResults": "53",
  "formattedTotalResults": "53"
 },
 "items": [
  {
   "kind": "customsearch#result",
   "title": "Maverick at Cedar Point - Top Coasters",
   "htmlTitle": "\u003cb\u003eMaverick\u003c/b\u003e at Cedar Point - Top Coasters",
   "link": "http://www.topcoasters.com/database/roller-coaster/6/maverick",
   "displayLink": "www.topcoasters.com",
   "snippet": "More Information. First roller coaster to include a horseshoe-roll element, also the \n500th roller coaster design produced by designer Werner Stengel. Maverick ...",
   "htmlSnippet": "More Information. First roller coaster to include a horseshoe-roll element, also the \u003cbr\u003e\n500th roller coaster design produced by designer Werner Stengel. \u003cb\u003eMaverick\u003c/b\u003e&nbsp;...",
   "cacheId": "Fbubv7kMS3kJ",
   "formattedUrl": "www.topcoasters.com/database/roller-coaster/6/maverick",
   "htmlFormattedUrl": "www.topcoasters.com/database/roller-coaster/6/\u003cb\u003emaverick\u003c/b\u003e",
   "pagemap": {
    "cse_image": [
     {
      "src": "http://i1.ytimg.com/vi/YbyB9N4mvq4/sddefault.jpg"
     }
    ],
    "cse_thumbnail": [
     {
      "width": "259",
      "height": "194",
      "src": "https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSH-UTMu6Ssvw2VCnS1YKAvIcbcYjxbHdanHoYOuugwCm-yAAprfqOkk8-1"
     }
    ]
   }
  },
  {
   "kind": "customsearch#result",
   "title": "Pictures of Maverick - Top Coasters",
   "htmlTitle": "Pictures of \u003cb\u003eMaverick\u003c/b\u003e - Top Coasters",
   "link": "http://www.topcoasters.com/gallery/album/6/coaster",
   "displayLink": "www.topcoasters.com",
   "snippet": "Maverick's Database Page. Pictures of Maverick. Maverick from the Boardwalk. \n1gGZRrLX7nK1Xgj.jpg. Maverick Water Bombs. 9PDXeKgdBF7knvd.jpg. Airtime.",
   "htmlSnippet": "\u003cb\u003eMaverick&#39;s\u003c/b\u003e Database Page. Pictures of \u003cb\u003eMaverick\u003c/b\u003e. \u003cb\u003eMaverick\u003c/b\u003e from the Boardwalk. \u003cbr\u003e\n1gGZRrLX7nK1Xgj.jpg. \u003cb\u003eMaverick\u003c/b\u003e Water Bombs. 9PDXeKgdBF7knvd.jpg. Airtime.",
   "cacheId": "vsfCpE4F_PAJ",
   "formattedUrl": "www.topcoasters.com/gallery/album/6/coaster",
   "htmlFormattedUrl": "www.topcoasters.com/gallery/album/6/coaster",
   "pagemap": {
    "metatags": [
     {
      "viewport": "text/html;charset=utf-8"
     }
    ]
   }
  },
  {
   "kind": "customsearch#result",
   "title": "Cedar Point Winter Chill Out 2014 - Top Coasters",
   "htmlTitle": "Cedar Point Winter Chill Out 2014 - Top Coasters",
   "link": "http://www.topcoasters.com/forums/topic/8/cedar-point-winter-chillout-2014",
   "displayLink": "www.topcoasters.com",
   "snippet": "Feb 23, 2014 ... Also on our way, we discovered a lonely Maverick train car, just sitting in the lot \nby itself. Once we got to the maintenance building, we some ...",
   "htmlSnippet": "Feb 23, 2014 \u003cb\u003e...\u003c/b\u003e Also on our way, we discovered a lonely \u003cb\u003eMaverick\u003c/b\u003e train car, just sitting in the lot \u003cbr\u003e\nby itself. Once we got to the maintenance building, we some&nbsp;...",
   "cacheId": "TjgnAzoyp2UJ",
   "formattedUrl": "www.topcoasters.com/forums/.../8/cedar-point-winter-chillout-2014",
   "htmlFormattedUrl": "www.topcoasters.com/forums/.../8/cedar-point-winter-chillout-2014",
   "pagemap": {
    "cse_image": [
     {
      "src": "http://i842.photobucket.com/albums/zz342/Gunnar2894/P1000843_zps2c337ebd.jpg"
     }
    ],
    "cse_thumbnail": [
     {
      "width": "276",
      "height": "183",
      "src": "https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcQ_OMDzM1R6jk2c5LhIXZaihhZG90uEkAOPQBMwWpmZ8hGca60yHHPd4jg"
     }
    ]
   }
  },
  {
   "kind": "customsearch#result",
   "title": "Top Coasters - Experience the Top Thrill!",
   "htmlTitle": "Top Coasters - Experience the Top Thrill!",
   "link": "http://www.topcoasters.com/index.php?app=root&section=search&where=everything&query=&noseo",
   "displayLink": "www.topcoasters.com",
   "snippet": "... Beast at Kings Island · Maverick at Cedar Point · Millennium Force at Cedar \nPoint · Maverick at Cedar Point · Mantis at Cedar Point · The Racer at Kings \nIsland.",
   "htmlSnippet": "... Beast at Kings Island &middot; \u003cb\u003eMaverick\u003c/b\u003e at Cedar Point &middot; Millennium Force at Cedar \u003cbr\u003e\nPoint &middot; \u003cb\u003eMaverick\u003c/b\u003e at Cedar Point &middot; Mantis at Cedar Point &middot; The Racer at Kings \u003cbr\u003e\nIsland.",
   "cacheId": "yU-SfTsapRsJ",
   "formattedUrl": "www.topcoasters.com/index.php?app=root&section...",
   "htmlFormattedUrl": "www.topcoasters.com/index.php?app=root&amp;section...",
   "pagemap": {
    "metatags": [
     {
      "viewport": "text/html;charset=utf-8"
     }
    ]
   }
  },
  {
   "kind": "customsearch#result",
   "title": "Top Coasters - Experience the Top Thrill!",
   "htmlTitle": "Top Coasters - Experience the Top Thrill!",
   "link": "http://www.topcoasters.com/index.php?app=database&section=coasters&filters=c_designer%3ACurtis+D.+Summers%3Bc_layout%3AFull+Circut%3Bc_twin_tracked%3A1%3Bc_cars%3Astandup%3Bc_track%3Asteel%3Bc_status:defunc;&noseo",
   "displayLink": "www.topcoasters.com",
   "snippet": "Top Coasters © 2009 - 2014 Alec Carpenter · Terms of Use & Privacy Policy. Top. \nTop Thrill Dragster at Cedar Point. Maverick at Cedar Point. Lake Erie Eagles ...",
   "htmlSnippet": "Top Coasters © 2009 - 2014 Alec Carpenter · Terms of Use &amp; Privacy Policy. Top. \u003cbr\u003e\nTop Thrill Dragster at Cedar Point. \u003cb\u003eMaverick\u003c/b\u003e at Cedar Point. Lake Erie Eagles&nbsp;...",
   "cacheId": "2lKn0F28GB8J",
   "formattedUrl": "www.topcoasters.com/index.php?app...filters...",
   "htmlFormattedUrl": "www.topcoasters.com/index.php?app...filters..."
  },
  {
   "kind": "customsearch#result",
   "title": "Top Coasters - Experience the Top Thrill!",
   "htmlTitle": "Top Coasters - Experience the Top Thrill!",
   "link": "http://www.topcoasters.com/index.php?app=database&section=coasters&filters=c_designer%3AReverchon%3Bc_status:relocated;&noseo",
   "displayLink": "www.topcoasters.com",
   "snippet": "Power Tower at Cedar Point; Maverick at Cedar Point ...",
   "htmlSnippet": "Power Tower at Cedar Point; \u003cb\u003eMaverick\u003c/b\u003e at Cedar Point&nbsp;...",
   "cacheId": "ncCWEHYxYy4J",
   "formattedUrl": "www.topcoasters.com/index.php?app=database&section...",
   "htmlFormattedUrl": "www.topcoasters.com/index.php?app=database&amp;section..."
  },
  {
   "kind": "customsearch#result",
   "title": "Alec's Profile",
   "htmlTitle": "Alec&#39;s Profile",
   "link": "http://www.topcoasters.com/profile/alec",
   "displayLink": "www.topcoasters.com",
   "snippet": "Top Roller Coasters. First Coaster, Double Loop at Geauga Lake. Favorite \nCoaster, Maverick at Cedar Point. Favorite Steel Coaster, GateKeeper at Cedar \nPoint.",
   "htmlSnippet": "Top Roller Coasters. First Coaster, Double Loop at Geauga Lake. Favorite \u003cbr\u003e\nCoaster, \u003cb\u003eMaverick\u003c/b\u003e at Cedar Point. Favorite Steel Coaster, GateKeeper at Cedar \u003cbr\u003e\nPoint.",
   "cacheId": "9YKPWUM9e1EJ",
   "formattedUrl": "www.topcoasters.com/profile/alec",
   "htmlFormattedUrl": "www.topcoasters.com/profile/alec"
  },
  {
   "kind": "customsearch#result",
   "title": "Top Coasters - Experience the Top Thrill!",
   "htmlTitle": "Top Coasters - Experience the Top Thrill!",
   "link": "http://www.topcoasters.com/index.php?app=database&section=coasters&filters=c_opened%3A2001%3Bc_twin_tracked:1;&noseo",
   "displayLink": "www.topcoasters.com",
   "snippet": "Maverick at Cedar Point · GateKeeper at Cedar Point · Maverick ...",
   "htmlSnippet": "\u003cb\u003eMaverick\u003c/b\u003e at Cedar Point &middot; GateKeeper at Cedar Point &middot; \u003cb\u003eMaverick\u003c/b\u003e&nbsp;...",
   "cacheId": "lGRzPGm5P0cJ",
   "formattedUrl": "www.topcoasters.com/index.php?app=database...filters...",
   "htmlFormattedUrl": "www.topcoasters.com/index.php?app=database...filters..."
  },
  {
   "kind": "customsearch#result",
   "title": "Top Coasters - Experience the Top Thrill!",
   "htmlTitle": "Top Coasters - Experience the Top Thrill!",
   "link": "http://www.topcoasters.com/index.php?app=database&section=coasters&filters=c_designer%3ABolliger+%26+Mabillard%3Bc_cars%3Acombo_wf%3Bc_restraints%3Alap%3Bc_status%3Adefunc%3Bc_track%3Awood%3Bc_layout:Shuttle;&noseo",
   "displayLink": "www.topcoasters.com",
   "snippet": "... GateKeeper at Cedar Point · Maverick at Cedar Point; Power ...",
   "htmlSnippet": "... GateKeeper at Cedar Point &middot; \u003cb\u003eMaverick\u003c/b\u003e at Cedar Point; Power&nbsp;...",
   "cacheId": "2eEIKutl5d8J",
   "formattedUrl": "www.topcoasters.com/index.php?app=database...",
   "htmlFormattedUrl": "www.topcoasters.com/index.php?app=database...",
   "pagemap": {
    "cse_image": [
     {
      "src": "http://www.topcoasters.com/assets/uploads/exchange/screenshots/54007c48d18f91e5a2719f21c7f01269.PNG"
     }
    ],
    "cse_thumbnail": [
     {
      "width": "296",
      "height": "170",
      "src": "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTEAZZ0rIQwMmbBcnQL_RauhS2RH84vNAHmNQw4NFhKjy5-tpu-q5aX6vys"
     }
    ]
   }
  },
  {
   "kind": "customsearch#result",
   "title": "Alec's Profile - Top Coasters",
   "htmlTitle": "Alec&#39;s Profile - Top Coasters",
   "link": "http://www.topcoasters.com/profile/alec/track-record",
   "displayLink": "www.topcoasters.com",
   "snippet": "Maverick. at Cedar Point. 23. Mantis. at Cedar Point. 21. Magnum XL-200. at \nCedar Point. 16. Wicked Twister. at Cedar Point. 15. Mean Streak. at Cedar Point.",
   "htmlSnippet": "\u003cb\u003eMaverick\u003c/b\u003e. at Cedar Point. 23. Mantis. at Cedar Point. 21. Magnum XL-200. at \u003cbr\u003e\nCedar Point. 16. Wicked Twister. at Cedar Point. 15. Mean Streak. at Cedar Point.",
   "cacheId": "wFUtiOcFjMUJ",
   "formattedUrl": "www.topcoasters.com/profile/alec/track-record",
   "htmlFormattedUrl": "www.topcoasters.com/profile/alec/track-record",
   "pagemap": {
    "cse_image": [
     {
      "src": "http://www.topcoasters.com/assets/uploads/profile_pictures/1.png"
     }
    ],
    "cse_thumbnail": [
     {
      "width": "160",
      "height": "160",
      "src": "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQ5ze4zUzDCQYyjBpOAjC8BCj1UTkOGuSWC23JtLMLqAa3IFUFRkDadlaM"
     }
    ]
   }
  }
 ]
}
EOF;

    public function main($what='') {
        Output::setPageTitle('Search');

        if(!array_key_exists('q', $_GET) || !$_GET['q']) {
            \Application\Lib\Utility::showError('You must enter something to search for!');
        }

        if(strlen($_GET['q']) < 4) {
            \Application\Lib\Utility::showError('Please enter a bigger search string.');
        }

        $query        = $this->trimItUp($_GET['q']);
        $results      = array('items' => array());
        $totalResults = 0;

        Output::setGlobalVariable('search_query', $query);

        $rest = new \Maverick\Lib\Http_REST('https://www.googleapis.com/customsearch/v1');

        $rest->addParameters(array('key' => 'AIzaSyBxnjKgID_bosz-GDzUWPfDDr443fL577g',
                                   'cx'  => '012240979130840958263:5p4r96bw5uk',
                                   'q'   => urlencode($query)));

        if($rest->get()) {
            $results = json_decode($rest->getResponse(), true);
        }

        $this->setVariables(array('total_results' => count($results['items']),
                                  'results'       => $results['items']));
    }

    private function trimItUp($query) {
        return preg_replace('~[^a-z0-9" ]~i', '', $query);
    }
}