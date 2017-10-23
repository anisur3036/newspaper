<?php
class DB {
    protected $dbh;
    protected $stmt;
    public $numOfRows;
    private $page;
    public $limit;
    //protected $serverName = "10.30.1.205"; //10.30.1.205 or 10.30.0.200 or 10.30.0.177

    public function __construct(){
        try {
            $this->dbh = new PDO( 'mysql:host=' .Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
             Config::get('mysql/username'), Config::get('mysql/password'));
            $this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }

    //Binds the prep statement
    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                    default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
        $this->stmt->execute();
    }

    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function totalRecords($query)
    {
        $this->query($query);
        $this->execute();
        return $this->stmt->rowCount();
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
        $query = "SELECT * FROM posts_list";
        $this->page = 1;
        if (isset($_GET['pg'])) {
            $this->page = $_GET['pg'];
        }
        if ( $this->limit == 'all' ) {
            return '';
        }

        //$rowStart = ( ( $this->page - 1 ) * $this->limit );
        $total = $this->totalRecords($query);

        //if total record  is less than $this->limit or equal
        //then reutrn null;
        if ($total <= $this->limit) {
            return;
        }
        $last = ceil( $total / $this->limit );
        
        //calculate start of range for link printing
        $start = ( ( $this->page - $links ) > 0 ) ? $this->page - $links : 1;
        
        //calculate end of range for link printing
        $end = ( ( $this->page + $links ) < $last ) ? $this->page + $links : $last;
        
       // debugging
       /* echo '$total: '.$total.' | '; //total rows
        echo '$row_start: '.$rowStart.' | '; //total rows
        echo '$limit: '.$this->limit.' | '; //total rows per query
        echo '$start: '.$start.' | '; //start printing links from
        echo '$end: '.$end.' | '; //end printing links at
        echo '$last: '.$last.' | '; //last page
        echo '$page: '.$this->page.' | '; //current page
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
