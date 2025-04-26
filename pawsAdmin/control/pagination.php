<?php
$rows_per_page = 10;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$total_pages = ceil(count($data) / $rows_per_page);
$start = ($current_page - 1) * $rows_per_page;
$end = $start + $rows_per_page;

// Generate page
function pagination($current_page, $total_pages, $page_name)
{
  $page_link = '';
  for ($page = 1; $page <= $total_pages; $page++) {
    $page_active = ($page == $current_page) ? 'page-active' : '';
    $page_link .= "<a class='$page_active' href='./$page_name?page=$page'> $page </a>";
  }

  return $page_link;
}
