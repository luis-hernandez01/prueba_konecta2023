<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->

<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->

    <!-- Top header -->

    <?php $sql="SELECT p.id, p.nombre_producto, p.referencia, p.precio, CONCAT(p.peso, u.sigla) As pesos, c.nombre AS nombre_categoria , p.stock,p.fecha_creacion FROM productos p, unidad_masa u, categoria c
WHERE p.id_unidad_masa=u.id AND p.id_categoria=c.id";
$productos = $db->select_all($sql);
 ?>
    

<!-- Cards container -->
    <div class="container text-center">
        <div class="row">

            <?php foreach ($productos as $key => $v) { ?>
                
            

<!-- Card #1, Starter -->
            <div class="col-lg-4 col-md-6 col-sm-10 pb-4 d-block ">
                <div class="pricing-item" style="box-shadow: 0px 0px 30px -7px rgba(0,0,0,0.29);">
                    <!-- Indicator of subscription type -->
                    <div class="pt-4 pb-3" style="letter-spacing: 2px">
                        <h4><?php echo $v['nombre_producto']; ?></h4>
                    </div>
                    <!-- Price class -->
                    <div class="pricing-price pb-1 text-primary color-primary-text ">
                        <h1 style="font-weight: 1000; font-size: 3.5em;">
                            <span style="   font-size: 20px;">$</span><?php echo $v['precio']; ?>
                        </h1>
                    </div>
                    <!-- Perks of said subscription -->
                    <div class="pricing-description">
                        <ul class="list-unstyled mt-3 mb-4"><h4>Peso</h4>
                            <li class="pl-3 pr-3"><?php echo $v['pesos']; ?></li>
                        </ul>
                        <ul class="list-unstyled mt-3 mb-4"><h4>Categoria</h4>
                            <li class="pl-3 pr-3"><?php echo $v['nombre_categoria']; ?></li>
                        </ul>
                        <ul class="list-unstyled mt-3 mb-4"><h4>Stock</h4>
                            <li class="pl-3 pr-3"><?php echo $v['stock']; ?></li>
                        </ul>
                    </div>
                    <!-- Button -->
                    <div class="pricing-button pb-4">
                        <a href="detalle-compra?q=<?php echo $v['id'] ?>" target="_blank" class="btn btn-lg btn-primary w-75">Ir al detalle</a>

                    </div>
                </div>
            </div>

        <?php } ?>

        </div>
        </div>


    
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"
        integrity="sha384-pjaaA8dDz/5BgdFUPX6M/9SUZv4d12SUPF0axWc+VRZkx5xU3daN+lYb49+Ax+Tl"
        crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script> -->

