<?
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful

require 'scraperwiki.php';

$TotalPageCount = 417;
$currentPage = 1;
$matches;

//patterns
$namepattern = '/<h1>(.*)<\/h1>/iUs';
$adresspattern1 = '/<ul class="kontaktliste2">\s*<li class="post">\s*Hausanschrift:<br \/>\s*(.*)<\/li>/iUs';
$adresspattern2 = '/<ul class="kontaktliste2">\s*<li class="post">\s*Postanschrift:<br \/>\s*(.*)<\/li>/iUs';
$phonepattern = '/<ul class="kontaktliste">\s*<li class="telefon">(.*)<\/li>/iUs';
$faxpattern = '/<ul class="kontaktliste">\s*<li class="telefax">(.*)<\/li>/iUs';
$emailpattern = '/<ul class="kontaktliste">.*<li class="email">.*<a href="mailto:.*">(.*)<\/a>.*<\/li>/iUs';
$urlpattern = '/<ul class="kontaktliste">.*<li class="internet">.*<a href="(.*)".*>.*<\/a>.*<\/li>/iUs';

$idpattern = '/BHW&amp;id=(.*)!0/';
while ($currentPage <= 2) {
  $html = scraperwiki::scrape("http://amt24.sachsen.de/ZFinder/search.do;jsessionid=QLr92E0v7nVp+yix1AQyd5Vn.zufi2_1?modul=WE&searchtextdone=&searchtext=***&filter=3&page=".$currentPage);
  preg_match_all($idpattern, $html, $matches);
  foreach ($matches[1] as $value){
        $output = scraperwiki::scrape("http://amt24.sachsen.de/ZFinder/behoerden.do?action=showdetail&modul=BHW&id=".$value);
      
        preg_match($namepattern, $output, $temp);
        $name = (isset($temp[1])) ? str_replace(';', ' -',trim(preg_replace('/\s+/', ' ', $temp[1]))) : '';
        
        preg_match($faxpattern, $output, $temp);
        $fax = (isset($temp[1])) ? trim(preg_replace('/\s+/', ' ', $temp[1])) : '';
        
        preg_match($phonepattern, $output, $temp);
        $telefon = (isset($temp[1])) ? trim(preg_replace('/\s+/', ' ', $temp[1])) : '';
        
        preg_match($emailpattern, $output, $temp);
        $email =  (isset($temp[1])) ? trim(preg_replace('/\s+/', ' ', $temp[1])) : '';
        
        preg_match($adresspattern1, $output, $temp);
        $adress1 = (isset($temp[1])) ? str_replace(';',',',trim(preg_replace('/\s+/', ' ', $temp[1]))) : '';
        $adress1 = str_ireplace('<br />', ',', $adress1);
	      $adress1 = strip_tags($adress1);

        preg_match($adresspattern2, $output, $temp);
        $adress2 = (isset($temp[1])) ? str_replace(';',',',trim(preg_replace('/\s+/', ' ', $temp[1]))) : '';
        $adress2 = str_ireplace('<br />', ',', $adress2);
        $adress2 = strip_tags($adress2);

        $adress = (isset($temp[1])) ? $adress2 : $adress1;


        preg_match($urlpattern, $output, $temp);
        $url = (isset($temp[1])) ? trim(preg_replace('/\s+/', ' ', $temp[1])) : '';

	$phonestring = (strlen(trim($telefon)) != 0) ? trim($telefon) : '';
	$faxstring = (strlen(trim($fax)) != 0) ? trim($fax) : '';
	$contactconnector = (strlen($phonestring) > 0 && strlen($faxstring) > 0) ? ', ': '';
	$contact = $phonestring . $contactconnector . $faxstring;
	$jurisdiction__slug = 'saxony';
      
      scraperwiki::save_sqlite(array('data'), array('name' => $name,'email' => $email, 'address' => $adress, 'contact' => $contact, 'jurisdiction__slug' => $jurisdiction__slug));
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
