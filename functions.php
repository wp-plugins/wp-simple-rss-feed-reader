<?php
/**
 * select offset of words from text
 *
 * @param mixed $string
 * @param mixed $words
 */
function SimplRssfirstWords($string, $words = 100)
{
    $wo = explode(" ", $string);
    $c = 0;
    $return = '';
    foreach ($wo as $piece) {
        $c++;
        if ($words > $c)
            $return .= " " . $piece;
    }
    return $return;
}

/**
 * turn feed into html
 *
 * @param mixed $xml
 * @param mixed $limit
 * @param mixed $amount_of_words
 */
function SimplRssParse($xml, $limit = 10, $hide_description = 0, $hide_url = 0, $show_date = 0, $amount_of_words = 10)
{
    if (empty($xml)) return '<div>Unfortunaly, this does not work...</div>';

    $return = '';
    if($hide_url == 0 ){
        $return .= "<a href=\"" . $xml->channel->link . "\" class=\"wp-simple-rss-feed-url\" target=\"_blank\">" . $xml->channel->link . "</a><hr />";
    }
    $return .= "<ul class=\"wp-simple-rss-list\">";
    $i = 0;

    if ($xml->channel) $main = $xml->channel->item;
    if ($xml->entry) $main = $xml->entry;

    foreach ($main as $item) {
        $i++;

        $titel = $item->title;
        $link = $item->link;

        $date = '';
        if (!empty($item->pubDate)) {
            $date = date('F d, Y ', strtotime($item->pubDate));
        }

        if($hide_url == 0 ) {
            $return .= '<li class="wp-simple-rss-item"><h3 class="wp-simple-rss-h3"><a href="' . $link . '" target="_blank" title="' . ($titel) . '" class="wp-simple-rss-link">' . ($titel) . '</a></h3>';
        } else {
            $return .= '<li class="wp-simple-rss-item"><h3 class="wp-simple-rss-h3">' . ($titel) . '</h3>';
        }
        if ($show_date == 1 && !empty($date)) {
            $return .= '<span class="wp-simple-rss-date">' . $date . '</span>';
        }
        if ($hide_description == 0) {
            $return .= '<span class="wp-simple-rss-description">' . SimplRssfirstWords($item->description, (int)($amount_of_words + 1)) . '</span>';
        }
        $return .= '</li>';

        if ($i == $limit) break;
    }
    $return .= "</ul>";
    return $return;
}
