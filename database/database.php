<?php

Class database
{
    private $connection;

    public function __construct($host)
    {
        try
        {
            $this->connection = new PDO("mysql:host=$host;dbname=crawlingDB", "xxxx", "xxxx");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e)
        {
            console.log($e -> getMessage);
        }
    }

    public function get_community()
    {
        try {
            $community_array = array();

            $statement = $this->connection->query("SELECT source,count(*) cnt FROM board GROUP BY source");
            $result = $statement->fetchAll();

            foreach ($result as $key)
            {
                array_push($community_array,$key['source']);
            }
            return $community_array;
        } catch (Exception $e) {
            console.log($e -> getMessage);
        }
    }

    public function get_community_cnt($source)
    {
        try {
            $statement = $this->connection->query("SELECT count(*) cnt FROM board WHERE source = \"".$source."\"");
            $result = $statement->fetchAll();
            return $result;
        } catch (Exception $e) {
            console.log($e -> getMessage);
        }
    }

    public function get_news_board($source)
    {
        try {
            $statement = $this->connection->query("SELECT * FROM board where source = \"".$source."\"");
            $result = $statement->fetchAll();

            foreach ($result as $row)
            {
                print '<div><a href="'.$row['url'].'"target="_blank">';
                print '<h3 class="source">'.$row['source'].'</h3>';
                print '<div class="category">'.$row['category'].'</div>';
                print '<div class="title">'.$row['title'].'</div>';
                print '<div class="hits">조회수 : '.$row['hits'].'</div>';
                print '<div class="date">작성일 : '.$row['date'].'</div>';
                print '</a></div>';
            }
        }
        catch (Exception $e) {
            console.log($e -> getMessage);
        }
    }

    public function get_category($source)
    {
        try {
            $category_cnt = array();

            $statement = $this->connection->query("SELECT DISTINCT(category) FROM board where source = \"".$source."\"");
            $result = $statement->fetchAll();

            foreach ($result as $key)
            {
                array_push($category_cnt,$key['category']);
            }
            return $category_cnt;
        } catch (Exception $e) {
            console.log($e -> getMessage);
        }

    }

    public function get_category_cnt($source,$category)
    {
        try {
            $statement = $this->connection->query("SELECT count(*) cnt FROM board WHERE source = \"".$source."\" AND category = \"".$category."\"");
            $result = $statement->fetchAll();
            return $result;
        } catch (Exception $e) {
            console.log($e -> getMessage);
        }

    }
}

?>
