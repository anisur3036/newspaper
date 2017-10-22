<?php
class Post extends App
{
    public function index()
    {
		$query = "SELECT * from posts_list";
    	$this->db->query($query);
    	return $this->db->resultSet();
    }

    public function paginate($limit)
    {
        $this->db->limit = $limit;
        $query = "SELECT * FROM posts_list";
        $recordPerPage = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : $this->db->limit; //movies per page
        $page = ( isset( $_GET['pg'] ) ) ? $_GET['pg'] : 1; //starting page
        //$links = 1;
        $pagingQuery = $this->db->paging( $query, $recordPerPage );
        $this->db->query( $pagingQuery );
        return $this->db->resultSet();
    }
    public function links( $links, $listClass )
    {
    	return $this->db->createLinks( $links, $listClass );
    }
}
