<?php
require "includes/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Блог IT_Минималиста!</title>

    <!-- Bootstrap Grid -->
    <link rel="stylesheet" type="text/css" href="/media/assets/bootstrap-grid-only/css/grid12.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Custom -->
    <link rel="stylesheet" type="text/css" href="/media/css/style.css">
</head>
<body>

<div id="wrapper">

    <?php include "includes/header.php" ?>
    <?php $article = mysqli_query($connection, 'SELECT * FROM `articles` WHERE id =' . (int)$_GET['id']);
    if (mysqli_num_rows($article) == 0) {
        ?>
        <div id="content">
            <div class="container">
                <div class="row">
                    <section class="content__left col-md-8">
                        <div class="block">
                            <h3>Статья не найдена!</h3>
                            <div class="block__content">
                                <img src="/media/images/post1.jpg">
                                <div class="full-text">
                                    Возможно данной статьи не существует!
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="content__right col-md-4">
                        <?php include "includes/sidebar.php" ?>
                    </section>
                </div>
            </div>
        </div>
        <?php
    } else {
        $art = mysqli_fetch_assoc($article);
        mysqli_query($connection, "UPDATE `articles` SET `views` = `views` + 1 WHERE id =" . (int)$art['id']) ?>
        <div id="content">
            <div class="container">
                <div class="row">
                    <section class="content__left col-md-8">
                        <div class="block">
                            <a><?php echo $art['views'] ?></a>
                            <h3><?php echo $art['title'] ?></h3>
                            <div class="block__content">
                                <img alt="Image doesn't exist" src="/static/images/<?php echo $art['image'] ?>">
                                <div class="full-text">
                                    <?php echo $art['text'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <a href="#comment-add-form">Добавить свой</a>
                            <h3>Комментарии</h3>
                            <div class="block__content">
                                <div class="articles articles__vertical">
                                    <?php $comments = mysqli_query($connection, 'SELECT * FROM comments WHERE `articles_id` ='. (int)$art['id']);
                                    while ($comment = mysqli_fetch_assoc($comments)) { ?>
                                        <article class="article">
                                            <div class="article__image"
                                                 style="background-image: <?php if ($comment['avatar'] == false) {
                                                     echo "url(/media/images/post1.jpg)";
                                                 } else {
                                                     echo "url(/static/avatar/{$comment['avatar']})";
                                                 }
                                                 ?>"></div>
                                            <div class="article__info">
                                                <a href="#"><?php echo $comment['author'] ?></a>
                                                <div class="article__info__meta">
                                                </div>
                                                <div class="article__info__preview"><?php echo strip_tags($comment['text']); ?>
                                                </div>
                                            </div>
                                        </article>
                                    <?php }; ?>
                                </div>
                            </div>
                        </div>
                        <div class="block" id="comment-add-form">
                            <h3>Добавить комментарий</h3>
                            <div class="block__content">
                                <form class="form" method="post" action="article.php?id=<?php echo $art['id']?>#comment-add-form">
                                    <?php
                                    if(isset($_POST['do_post']))
                                    {
                                        $errors = array();
                                        if($_POST['name']==''){
                                            $errors[]='Введите имя';
                                        }
                                        if($_POST['nickname']==''){
                                            $errors[]='Введите ваш никнейм';
                                        }
                                        if($_POST['email']==''){
                                            $errors[]='Введите ваш Email';
                                        }
                                        if($_POST['text']==''){
                                            $errors[]='Введите текст коментария!';
                                        }
                                        if(empty($errors)){
                                            mysqli_query($connection,"INSERT INTO `comments` (`author`,`text`,`pubdate`,`articles_id`) VALUES ('{$_POST['name']}','{$_POST['text']}',NOW(),'{$art['id']}') ");
                                            echo '<span style="color:green;display:block;margin-bottom: 10px">Коментарий успешно добавлен</span>';
                                            unset($_POST);
                                            echo "<script>window.location.replace(\"http://blog.ru/article.php?id={$art['id']}\")</script>";//Добавление комментария
                                        }else{
                                            echo '<span style="color:red;display:block;margin-bottom: 10px">'.$errors[0].'</span>';
                                        }
                                    }
                                    ?>
                                    <div class="form__group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" class="form__control" name="name" placeholder="Имя" value="<?php echo $_POST['name']; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form__control" name="nickname" placeholder="Никнейм" value="<?php echo $_POST['nickname']; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="email" class="form__control" name="email" placeholder="Email" value="<?php echo $_POST['email']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form__group">
                                        <textarea name="text" class="form__control" placeholder="Текст комментария ..."><?php echo $_POST['text']; ?></textarea>
                                    </div>
                                    <div class="form__group">
                                        <input type="submit" class="form__control" name="do_post" value="Добавить комментарий">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>

                    <section class="content__right col-md-4">
                        <?php include "includes/sidebar.php" ?>
                    </section>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <?php include "includes/footer.php"; ?>

</div>

</body>
</html>