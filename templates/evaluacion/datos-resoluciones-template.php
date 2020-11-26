<?php
$columna = array_column($ranking, 're');
array_multisort($columna, SORT_DESC,  $name, SORT_ASC, $ranking);
$grupos = array();
foreach($ranking as $key => $value){
    $grupos[$value['re']][] = $value['id'];
}
/* echo '<pre>';
var_dump($ranking);
echo '</pre>'; */
$titularizados = false;
$podio = array();

?>
<div class="row">
    <div class="col-sm-12">
        <div class="py-3 bg-light w-100 border d-flex flex-nowrap justify-content-center align-items-center">
            <?php 
                if($grupos){
                    $i = 0;
                    foreach($grupos as $grupo => $value){   
                        if($i <= 2){
                            $value[0];
                            $podio_img = 'http://placehold.it/100x100?text=Podio';
                            if(
                                $winner_img = get_the_post_thumbnail_url(
                                    $value[0], 
                                    'thumbnail'
                                )
                            ){
                                $podio_img = $winner_img;
                            }
            ?>
            <div class="podio-profile order-<?php 
                    echo ($i == 0) ? '2 podio-first' : (($i == 2) ? '3' : $i); 
                ?> bd-highlight">
                <img class="img-fluid rounded-circle" src="<?php echo $podio_img; ?>">
            </div>
            <?php
                        }
                        $i++;
                    }
                }
            ?>
        </div>
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <!--
                    <th style="text-align:center; line-height:1" scope="col" width="20"><span class="fs-12">Rank</span></th>
                    -->
                    <th class="align-middle" style="text-align:center; line-height:1" scope="col"><span class="fs-16">Nombre</span></th>
                    <th class="align-middle" style="text-align:center; line-height:1" scope="col" width="300"><span class="fs-16">Organización política</span></th>
                    <th class="align-middle" style="text-align:center; line-height:1" scope="col" width="300"><span class="fs-16">Proyectos de resoluciones</span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($ranking as $index => $rank){
                        $titularizado = '';
                        if ($rank['titularizado']){
                            $titularizado = '*';
                            $titularizados = true;
                        }
                ?>
                <tr <?php echo ($index > 2) ? 'class="hidden-row d-none" ' : ''?>>
                    <!--
                    <th class="align-middle" style="text-align:center;" scope="row"><?php echo $indice; ?></th>
                    -->
                    <td class="align-middle"><strong><?php echo $rank['title'] . ' ' . $titularizado ?></strong></td>
                    <td class="align-middle" style="text-align:center;"><?php echo ($rank['partido']) ? '<img width="50" src="'.$rank['partido'].'">' : ''; ?></td>
                    <td class="align-middle" style="text-align:center;"><?php echo $rank['re'] ?></td>
                </tr>
                <?php 
                    if ($index == 4){ break; }
                } 
                ?>
            </tbody>
        </table>
        <p class="text-right"><a class="table-show-more fs-12 text-muted" href="#">Mostrar más</a></p>
        <?php if($titularizados){ ?>
        <?php } ?>
    </div>
</div>