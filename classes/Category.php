<?php
class Category extends DB
{
    public function relatedPost($cat)
    {
    	$query = "SELECT * from posts_list WHERE ";
    	$this->query($query);
    	return $this->resultSet();
    }
}
