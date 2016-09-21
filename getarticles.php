<?php
    include "connect.php";
    $getArticles = mysqli_query($con, "SELECT id, title, date, img FROM articles");
    $articles = mysqli_num_rows($getArticles); 
    $articlesPerPage = 8;
    $pages = ceil($articles / $articlesPerPage);
    
    if(mysqli_num_rows($getArticles) > 0){
        $articlesArray = array();
        
        while($articleRow = mysqli_fetch_array($getArticles)){
            $articlesArray[] = $articleRow;
        }
        
        usort($articlesArray, function($a, $b) {
            $ad = new DateTime($a['date']);
            $bd = new DateTime($b['date']);

            if ($ad == $bd) {
                return 0;
            }

            return $ad > $bd ? -1 : 1;
        });
    }
    
    mysqli_close($con);
    echo json_encode($articlesArray);
?>