<style>
    #snackbar {
        visibility: hidden;
        min-width: 250px;

        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        left: 50%;
        top: 60px;
        font-size: 17px;
    }

    #snackbar.show {
        visibility: visible;
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    @-webkit-keyframes fadein {
        from {top: 0; opacity: 0;}
        to {top: 30px; opacity: 1;}
    }

    @keyframes fadein {
        from {top: 0; opacity: 0;}
        to {top: 30px; opacity: 1;}
    }

    @-webkit-keyframes fadeout {
        from {top: 30px; opacity: 1;}
        to {top: 0; opacity: 0;}
    }

    @keyframes fadeout {
        from {top: 30px; opacity: 1;}
        to {top: 0; opacity: 0;}
    }
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/pointOfSale/pointOfSale-grid.js"></script>



<?php $allow_delete = crudRole::getCrudrole(4); ?>

<div>
    <div ng-controller="manageProduct">

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>


        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Point Of Sale
                </a>
            </li>

        </ul>
         <!--{{productList}}-->
        <div class="table-responsive" style="margin-top: 20px">
            <ul class="breadcrumb" style="margin-left: 10px;margin-right: 10px;">
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/PosShop/crudPosShop">Pos Shop</a></li>
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/pos/pointOfSale">Sale Form</a></li>
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/PosStockReceived/PosStockTransfered">Stock Transfer To Shop</a></li>
            </ul>
            <div class="dataTables_length  col-md-12" id="datatable_products_length">
                <div class="alert alert-warning" role="alert">
                   There is no shop assign to this user....
                </div>
            </div>
        </div>
    </div>
</div>

