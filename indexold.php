

 <?php
  session_start(); 
$mysqli = mysqli_init();
if (!$mysqli) {
    die('mysqli_init завершилась провалом');
}
 
if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
    die('Установка MYSQLI_INIT_COMMAND завершилась провалом');
}
 
if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
    die('Установка MYSQLI_OPT_CONNECT_TIMEOUT завершилась провалом');
}
 
if (!$mysqli->real_connect('localhost', 'root','root', 'shop','3306')) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}

 
$categories = array();
if ($result = $mysqli->query('SELECT * FROM categories ')) {
    while($tmp = $result->fetch_assoc()) {
        $categories[] = $tmp;

    }
    $result->close();
}

$products = array();
$cat = isset($_REQUEST['cat']) ? (int) $_REQUEST['cat'] : 0; 
$sql = 'SELECT p.* FROM products AS p ';
if ($cat) {
   $sql .= ' INNER JOIN categories_products AS cp ON cp.id_product=p.id AND cp.id_category=' . $cat .' ORDER BY title';
}
if ($result = $mysqli->query($sql)) {
    while($tmp = $result->fetch_assoc()) {
        $products[] = $tmp;
       
    }
    $result->close();
}
?>

<?php 
    if(isset($_GET['action']) &&$_GET['action']=="add"){ 
        $id=intval($_GET['id']); 
        if(isset($_SESSION['cart'][$id])){ 
            $_SESSION['cart'][$id]['quantity']++; 
            }else{ 
            $sql_s="SELECT * FROM products 
                WHERE id={$id}"; 
          $query_s=$mysqli->query($sql_s); 
            if(mysql_num_rows($query_s)!=0){ 
                $row_s=mysql_fetch_array($query_s); 
                $_SESSION['cart'][$row_s['id']]=array( 
                        "quantity" => 1, 
                        "price" => $row_s['price'] 
                    ); 
          

            }else{ 
                $message="This product id it's invalid!"; 
            } 
        } 
    } 
?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lego acessory shop</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/shop-homepage.css" rel="stylesheet">

  </head>

  <body>


  


    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">Kids&Robots acessory shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Домой
                <span class="sr-only">(current)</span>
              </a>
            </li class="nav-item">
            <li class="nav-item">
              <a class="nav-link" href="about2.php">О сайте</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Связаться</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cart.php">☑Корзина</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

 


    <!-- Page Content -->
    <div class="container">

      <div class="row">


        <div class="col-lg-3">

          <h1 class="my-4">Категории </h1>
          <div class="list-group">

            <?php
            foreach($categories AS $category) {
                echo ' <a href="?cat=' . $category['id'] . '" class="list-group-item">' . $category['title'] . '</a>';
            }
        ?>
          </div>


          <h1>Cart</h1> 

          <?php 
    if(isset($_SESSION['cart'])){ 
        $sql="SELECT * FROM products WHERE id IN ("; 
        foreach($_SESSION['cart'] as $id => $value) { 
            $sql.=$id.","; 
            echo "$sql";
        } 
       
    } 
?>
       


 



     
        </div>

        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

          <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
              <div class="carousel-item active">
                <img class="d-block img-fluid" src="top1.png" alt="First slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="top2.png" alt="Second slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="top3.png" alt="Third slide">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>

          <div class="row">

        

<div class="row">

    <?php foreach($products AS $product) {?>
        <div class="col-lg-3 col-md-6 mb-4">
           <div class="card h-100">
                <img src="<?php echo $product['img'];?>" alt="" class="card-img-bottom">
                <div class="card-body">
                    <h6><?php echo $product['title'];?></h4>
                    <h4 class="card-title"><?php echo $product['price'];?> грн.</h5>
                    <p  class="card-text"><?php echo $product['intro'];?></p>
                        <a href="index.php?page=products&action=add&id=<?php echo $product['id'] ?>&category=<?php echo $cat ?>">Add to cart</a>
                </div>
                <div class="card-footer">
                    <p>
                        <?php
                            echo str_repeat(' <img src=sfull.png>', $product['reviews_score']);
                        ?>
                        
                     
                        
                     </p>
                 </div>
            </div>
       </div>
   <?php } ?>
</div>

     
          <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Kids&Robots &copy; Lego Inc 2018</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
