<?php

/**
	>>> DEPENDENCIES <<<

	NONE!
 */
class Pagination
{
	
	function __construct()
	{
	}

	public function GeneratePagination($currentPageNumber, $maxPageNumber)
	{
		if($currentPageNumber > $maxPageNumber)
			echo "<script>alert('Error! Current page number (" . $currentPageNumber . ") is bigger than max page number (" . $maxPageNumber . ")!');</script>";
		if($currentPageNumber <= 0 || $maxPageNumber <= 0)
			echo "<script>alert('Error! Current page number (" . $currentPageNumber . ") and max page number (" . $maxPageNumber . ") must be greater than zero!');</script>";
		for ($pageNum = 1; $pageNum <= $maxPageNumber; $pageNum++) { 
			# code...
		}
	}
}

?>
<nav aria-label="...">
  <ul class="pagination">
    <li class="page-item disabled">
      <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item active" aria-current="page">
      <a class="page-link" href="#">2</a>
    </li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#">Next</a>
    </li>
  </ul>
</nav>	