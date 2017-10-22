 <?php
 class Pagination { 
    public $table = 'posts_list';
    public $limit;
    public $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function paging($query, $limit)
    {
        $this->limit = $limit;
        $starting_position = 0;
        if (isset($_GET['pg'])) {
            $starting_position = ($_GET['pg'] - 1) * $this->limit;
        }
        return $query . " LIMIT $starting_position, $this->limit";
    }
    public function createLinks($links, $listClass ) 
    {
        //$this->limit = $recordPerPage;
        $query = "SELECT * FROM {$this->table}";
        $this->page = 1;
        if (isset($_GET['pg'])) {
            $this->page = $_GET['pg'];
        }
        if ( $this->limit == 'all' ) {
            return '';
        }

        //$rowStart = ( ( $this->page - 1 ) * $recordPerPage );

        $total = $this->db->totalRecords($query);
        $last = ceil( $total / $this->limit );
        
        //calculate start of range for link printing
        $start = ( ( $this->page - $links ) > 0 ) ? $this->page - $links : 1;
        
        //calculate end of range for link printing
        $end = ( ( $this->page + $links ) < $last ) ? $this->page + $links : $last;
        
       // debugging
       /* echo '$total: '.$total.' | '; //total rows
        echo '$row_start: '.$rowStart.' | '; //total rows
        echo '$limit: '.$recordPerPage.' | '; //total rows per query
        echo '$start: '.$start.' | '; //start printing links from
        echo '$end: '.$end.' | '; //end printing links at
        echo '$last: '.$last.' | '; //last page
        echo '$page: '.$this->_page.' | '; //current page
        echo '$links: '.$links.' <br /> '; //links */

        //ul boot strap class - "pagination pagination-sm"
        $html = '<ul class="' . $listClass . '">';

        $class = ( $this->page == 1 ) ? "disabled" : ""; //disable previous page link <<<
        
        //create the links and pass limit and page as $_GET parameters

        //$this->_page - 1 = previous page (<<< link )
        $previousPage = ( $this->page == 1 ) ? 
        '<a href=""><li class="' . $class . '">&laquo;</a></li>' : //remove link from previous button
        '<li class="' . $class . '"><a href="?limit=' . $this->limit . '&pg=' . ( $this->page - 1 ) . '">&laquo;</a></li>';

        $html .= $previousPage;

        if ( $start > 1 ) { //print ... before (previous <<< link)
            $html .= '<li><a href="?limit=' . $this->limit . '&pg=1">First</a></li>'; //print first page link
            $html .= '<li class="disabled"><span>...</span></li>'; //print 3 dots if not on first page
        }

        //print all the numbered page links
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class = ( $this->page == $i ) ? "active" : ""; //highlight current page
            $html .= '<li class="' . $class . '"><a href="?limit=' . $this->limit . '&pg=' . $i . '">' . $i . '</a></li>';
        }

        if ( $end < $last ) { //print ... before next page (>>> link)
            $html .= '<li class="disabled"><span>...</span></li>'; //print 3 dots if not on last page
            $html .= '<li><a href="?limit=' . $this->limit . '&pg=' . $last . '">Last</a></li>'; //print last page link
        }

        $class = ( $this->page == $last ) ? "disabled" : ""; //disable (>>> next page link)
        
        //$this->_page + 1 = next page (>>> link)
        $nextPage = ( $this->page == $last) ? 
        '<li class="' . $class . '"><a href="">&raquo;</a></li>' : //remove link from next button
        '<li class="' . $class . '"><a href="?limit=' . $this->limit . '&pg=' . ( $this->page + 1 ) . '">&raquo;</a></li>';
        $html .= $nextPage;
        $html .= '</ul>';
        
        return $html;
    }
}
