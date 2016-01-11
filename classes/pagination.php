<?php

/**
* Main Pagination model
*/
class Pagination
{	
	/**
	* Get the offset with fixed limit
	*
	* @param int $limit is the limit for the movies which will be displayed on one page
	*
	* @return int 
	*/
	public function getOffset($limit)
	{
		$offset = 0;
		if (isset($_GET['page_no'])) {
			$offset = ($_GET['page_no'] - 1) * $limit;
		}
		return $offset;
	}

	/**
	* Creates the paging links
	*
	* @param int $numberOfRecords is the number of the movie
	* @param int $limit is the limit for the movies which will be displayed on one page
	*
	*/
	public function pagingLink($numberOfRecords, $limit)
	{
		$self = $_SERVER['PHP_SELF'];
	
		if ($numberOfRecords > 0) {
			$numberOfPages = ceil($numberOfRecords / $limit);
			$currentPage = 1;
			if (isset($_GET['page_no'])) {
				$currentPage = $_GET['page_no'];
			}

			if ($currentPage != 1) {
				$previous = $currentPage - 1;
				echo "<li><a href='" . $self . "?page_no=1'>First</a></li>";
				echo "<li><a href='" . $self . "?page_no=" . $previous . "'>Previous</a></li>";
			}

			for ($i=1; $i<$numberOfPages; $i++) {
				if ($i == $currentPage) {
					echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>";
				} else {
					echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>"; 
				}
			}

			if ($currentPage != $numberOfPages) {
				$next = $currentPage + 1 ;
				echo "<li><a href='" . $self . "?page_no=" . $next . "'>Next</a></li>";
				echo "<li><a href='" . $self . "?page_no=" . $numberOfPages . "'>Last</a></li>";
			}
		}
	}
}