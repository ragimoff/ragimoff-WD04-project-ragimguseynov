<?php

$sqlPost = 'SELECT
			posts.id, posts.title, posts.text, posts.post_img, posts.date_time, posts.cat,
    		users.firstname, users.secondname,
    		categories.cat_title 
		FROM `posts` 
		INNER JOIN categories ON posts.cat = categories.id 
		INNER JOIN users ON posts.author_id = users.id 
		WHERE posts.id = ' . $_GET['id'] . ' LIMIT 1';


$post = R::getAll( $sqlPost );
$post = $post[0];


$sqlComment = 'SELECT
			comments.text, comments.date_time, comments.user_id,
			users.firstname, users.secondname, users.avatar_small
		FROM comments
		INNER JOIN users ON comments.user_id = users.id
		WHERE comments.post_id = ' . $_GET['id'];

$comments = R::getAll( $sqlComment );

$title = $post['title'];

if ( isset($_POST['addComment']) ) {
	if (trim($_POST['commentText']) == '') {
		$errors[] = ['title' => 'Введите текст комментария'];
	}

	if ( empty($errors) ) {
		$comment = R::dispense('comments');
		$comment->postId = htmlentities($_GET['id']);
		$comment->userId = htmlentities($_SESSION['logged_user']['id']);
		$comment->text = htmlentities($_POST['commentText']);
		$comment->dateTime = R::isoDateTime();
		R::store( $comment );
		$comments = R::getAll( $sqlComment );
		// header('Location: ' . HOST . "blog/post?id=" . $_GET['id']);
		// exit();
	}
}


// Готовим контент центральной части
ob_start();
include ROOT. "templates/_parts/_header.tpl";
include ROOT."templates/blog/blog-post.tpl";
/*include ROOT. "templates/main/main.tpl";*/
$content = ob_get_contents();
ob_end_clean();


include ROOT. "templates/_parts/_head.tpl";
include ROOT. "templates/template.tpl";
include ROOT. "templates/_parts/_footer.tpl";
include ROOT. "templates/_parts/_foot.tpl";

?>