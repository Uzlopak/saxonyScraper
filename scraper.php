<?
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful

require 'scraperwiki.php';

$TotalPageCount = 417;
$currentPage = 1;
$matches;
// require 'scraperwiki/simple_html_dom.php';
//
// // Read in a page
$idpattern = '/BHW&amp;id=(.*)!0/';
while ($currentPage <= 2) {
  $html = scraperwiki::scrape("http://amt24.sachsen.de/ZFinder/search.do;jsessionid=IQbMQTxaj+vjA89rF7-+-a04.zufi2_1?modul=WE&searchtextdone=&searchtext=***&filter=3&page=".$currentPage);
  print $html;
  preg_match_all($idpattern, $html, $matches);
  var_dump($matches);
  foreach ($matches[1] as $value){
      echo $value;
      scraperwiki::save_sqlite(array('id'), array('id' => $value));
  }
  $currentPage++;
}

//
// // Find something on the page using css selectors
// $dom = new simple_html_dom();
// $dom->load($html);
// print_r($dom->find("table.list"));
//
// // Write out to the sqlite database using scraperwiki library
// scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));
//
// // An arbitrary query against the database
// scraperwiki::select("* from data where 'name'='peter'")

// You don't have to do things with the ScraperWiki library. You can use whatever is installed
// on Morph for PHP (See https://github.com/openaustralia/morph-docker-php) and all that matters
// is that your final data is written to an Sqlite database called data.sqlite in the current working directory which
// has at least a table called data.
?>
