<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('db.php');

process_actions();

function process_actions() {
    global $DB;

    $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
    $action = (isset($_GET['action'])) ? $_GET['action'] : 'articles';

    if ($action == 'savepost') {
        $form = (isset($_POST['form'])) ? $_POST['form'] : null;

        if ($form !== null) {

            if ($form['id'] > 0) {
                $DB->dbUpdate('articles', $form, array('id'=>$form['id']));
            } else {
                $form['userid'] = 1;
                $form['timecreated'] = time();
                $form['status'] = 1;
                $postID = $DB->dbInsert('articles', $form);
            }
        }
        header("Location: http://kr/index.php?action=manage");

    } else if ($action == 'delete' and $id > 0) {

        $DB->dbDelete('articles', array('id'=>$id));

        header("Location: http://kr/index.php?action=manage");
    }
}

function print_content() {
    $action = (isset($_GET['action'])) ? $_GET['action'] : 'articles';

    if ($action == 'create') {
        if ($id > 0) {
            $post = get_post($id);
        }
        include('Z:/home/kr/www//include/forms/edit.php');
    } else if ($action == 'manage') {
        $posts = get_manage_posts();
        include('Z:/home/kr/www/include/tables/manage.php');
    } 
    else {
        echo print_posts();
    }
}


function get_manage_posts() {
    global $DB;

    $sql = "SELECT p.*
              FROM pfx_articles p
              WHERE p.userid = :userid
           ORDER BY p.timecreated ";
    $posts = $DB->dbGetAll($sql, array('userid'=>1));

    return $posts;
}



function get_posts($cat_id = 0) {
    global $DB;

    $where = '';
    $params = array('status'=>0);
    if ($cat_id > 0) {
        $where = ' AND cat_id = :cat_id';
        $params['cat_id'] = $cat_id;
    }

    $sql = "SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) as author, c.cat_name
              FROM pfx_articles p
         LEFT JOIN pfx_users u ON u.id = p.userid 
         RIGHT JOIN pfx_articles_categories c ON c.id = p.cat_id
         WHERE p.status > :status $where
           ORDER BY p.timecreated";
    $posts = $DB->dbGetAll($sql, $params);

    return $posts;
}

function get_all_posts() {
    global $DB;
    $sql = "SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) as author, c.cat_name
              FROM pfx_articles p
         LEFT JOIN pfx_users u ON u.id = p.userid 
         RIGHT JOIN pfx_articles_categories c ON c.id = p.cat_id
         WHERE p.status > 0 
           ORDER BY p.timecreated";
    $posts = $DB->dbGetAll($sql, array('status'=>0));

    return $posts;
}

function get_post($id){
    global $DB;

    $sql = "SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) as author, c.cat_name
              FROM pfx_articles p
         LEFT JOIN pfx_users u ON u.id = p.userid
         RIGHT JOIN pfx_articles_categories c ON c.id = p.cat_id
              WHERE p.id = :id";
    $post = $DB->dbGetOne($sql, array('id'=>$id));

    return $post;
}


function print_posts($post = null){
    $id = (isset($_GET['id'])) ? $_GET['id'] : 0;
    $cat_id = (isset($_GET['cat_id'])) ? $_GET['cat_id'] : 0;

    $output = '';

    if(!$cat_id)
    {
        if($id)
    { 
        $posts = get_post($id);
        if (!$posts){
            return '<div>Стаття не існує</div>';
    }
    $output .= '<div>';
        
            $output .= print_one_post(null,$id);
        
    $output .= '</div>';
    }
        else{$posts = get_all_posts($cat_id);
    if (!count($posts)){
        return '<div>Немає статтей</div>';
    }
    $output .= '<div>';
        foreach ($posts as $post) {
            $output .= print_post($post);
        }
    $output .= '</div>';
    }}

    if($cat_id)
    {
    $posts = get_posts($cat_id);
    if (!count($posts)){
        return '<div>Немає статтей</div>';
    }
    $output .= '<div>';
        foreach ($posts as $post) {
            $output .= print_post($post);
        }
    $output .= '</div>';
        }


    return $output;
}


function print_post($post = null, $cat_id = 0){
    $output = '';

    if ($post == null and $id == 0) {
        $post = get_all_post();
    }

    if ($post == null and $cat_id > 0){
        $post = get_posts($cat_id);
    }

    if (!$post){
        return '';
    }

       $output = '<hr>';
    

     $output .= '<article class = "post_min"> 
                <a href="index.php?id='.$post->id.'" target="_blank"> <h2>'.$post->title.' </h2></a>';
        $output .='<h5><span class="glyphicon glyphicon-time"></span> Пост написав '.$post->author.', '.date('d F, Y', strtotime($post->timecreated)).'.</h5>';
        $output .= '<h5><span class="label label-danger">'.$post->cat_name.'</span></h5><br>
      <p>';
                $output .= substr($post->description, 0, 150).' <a href="index.php?id='.$post->id.'">Читати більше</a>'; ' 
      <br><br>
      </article>';


    return $output;
}

function print_one_post($post = null, $id = 0){

    $output = '';
    if ($post == null and $id > 0) {
        $post = get_post($id);
    }


    if (!$post){
        return '';
    }
       $output = '<hr> ';
    

     $output .= '<article class = "post_min"> 
                <a href="index.php?id='.$post->id.'" target="_blank"> <h2>'.$post->title.' </h2></a>';
        $output .='<h5><span class="glyphicon glyphicon-time"></span> Пост написав '.$post->author.', '.date('d F, Y', strtotime($post->timecreated)).'.</h5>';
        $output .= '<h5><span class="label label-danger">'.$post->cat_name.'</span></h5><br>
      <p>';
                $output .= $post->description .'<br>'.$post->text; ' 
      <br><br>
      </article>';


    return $output;
}

/*Cotegories print*/
function get_categories() {
    global $DB;

    $sql = "SELECT * FROM pfx_articles_categories";

    $categories = $DB->dbGetAll($sql);
    return $categories;
}

function get_categoris($id)
{
    global $DB;

    $sql = "SELECT cat_name FROM 'pfx_articles_categories'";

    $categories = $DB->dbGetAll($sql);
    return $categories;
}

function print_categories($post = null, $cat_id = 0){
    $cat_id = (isset($_GET['cat_id'])) ? $_GET['cat_id'] : 0;
    $output = '';
//error_reporting(0);
    $categories = get_categories($cat_id);

    if (count($categories)) {
        if(!$cat_id){
            $output .= ' <li class="active"><a href="index.php">Домашня сторінка</a></li>';
        } 
        else{
            $output .= ' <li><a href="index.php">Домашня сторінка</a></li>';
        }
        foreach ($categories as $category) {
            $output .= ' <li class="'.(($cat_id == $category->id) ? 'active' : '').'"><a href="index.php?cat_id='.$category->id.'">'.$category->cat_name.'</a></li>';      
        }
    }
  
    return $output;
}

/* Print top post*/

function print_top_content() {
 
            echo  print_top_posts();
    
}

function get_top_posts() {
    global $DB;
    $sql = "SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) as author, c.cat_name
              FROM pfx_articles p
         LEFT JOIN pfx_users u ON u.id = p.userid 
         RIGHT JOIN pfx_articles_categories c ON c.id = p.cat_id
         WHERE p.status > 0 
           ORDER BY p.view DESC";
    $posts = $DB->dbGetAll($sql, array('status'=>0));

    return $posts;
}

function print_top_posts($post = null){
    $output = '';
    $posts = get_top_posts();

    if (!count($posts)){
        return '<div>Немає постів</div>';
    }

    $output .= '<div>';
        foreach ($posts as $post) {
            $output .= print_top_post($post);
        }
    $output .= '</div>';

    return $output;
}

function print_top_post($post = null, $id = 0){
    $output = '';
    error_reporting(0);
    if ($post == null and $id > 0) {
        $post = get_top_post($id);
    }

    if (!$post){
        return '';
    }

    if ($id > 0){
       $output = '<hr>';
    }

    $output .= '<article class = "post_min"> 
                <a href="index.php?id='.$post->id.'" target="_blank"> <h2>'.$post->title.' </h2></a>';
        $output .='<h5><span class="glyphicon glyphicon-time"></span> Пост написав '.$post->author.', '.date('d m, Y', strtotime($post->timecreated)).'.</h5>';
        $output .= '<h5><span class="label label-danger">'.$post->cat_name.'</span></h5><br>
      <p>';
      if ($id > 0) {
                $output .= $post->description .'<br>'.$post->text;

            } else {
                $output .= substr($post->description, 0, 150).' <a href="index.php?id='.$post->id.'">Читати більше</a>';

            } ' 
      <br><br>
      </article>';


    return $output;
}






/*
function print_categories_name($post = null){
    $output = '';
    $posts = get_categories();

    if (!count($posts)){
        return '<div>Немає категорій</div>';
    }

    $output .= '<div>';
        foreach ($posts as $post) {
            $output .= print_categories_name($post);
        }
    $output .= '</div>';

    return $output;
}
function print_categoris_name($post = null, $id = 0){
    $output = '';
//error_reporting(0);
    if ($post == null and $id > 0) {
        $post = get_categoris($id);
    }
    if (!$post){
        return '';
    }
  $output = ' <li><a href="index.php?id='.$categories->id.'">'.$categories->cat_name.'</a></li>';
    return $output;
}*/

?>
