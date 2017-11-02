<?php
class Post extends DB
{
    public function index()
    {
		$query = "SELECT * from posts_list";
    	$this->query($query);
    	return $this->resultSet();
    }

    public function show($id)
    {
        $query = "SELECT * FROM posts_list WHERE id=:id LIMIT 1";
        $this->query($query);
        $this->bind(':id', $id );
        return $this->single();
    }

    public function relatedPost($cat, $id)
    {
        $query = "SELECT * from posts_list WHERE cat_name=:category AND id NOT IN(:id) LIMIT 6";
        $this->query($query);
        $this->bind(':category', $cat );
        $this->bind(':id', $id );
        return $this->resultSet();
    }


    public function paginate($limit)
    {
        $this->limit = $limit;
        $query = "SELECT * FROM posts_list";
        $recordPerPage = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : $this->limit; //movies per page
        $page = ( isset( $_GET['pg'] ) ) ? $_GET['pg'] : 1; //starting page
        //$links = 1;
        $pagingQuery = $this->paging( $query, $recordPerPage );
        $this->query( $pagingQuery );
        return $this->resultSet();
    }

    public function links( $links, $listClass )
    {
    	return $this->createLinks( $links, $listClass );
    }
}
