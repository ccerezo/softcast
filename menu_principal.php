
    <ul class="nav navbar-nav alinear_menu">
        <?php 
            for($i = 0; $i < count($_SESSION['id_modulo']); $i++){
                if($_SESSION['id_modulo'][$i] === '1'){
        ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Contabilidad<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo $servidor; ?>/modulos/contabilidad/plan-de-cuentas.php">Plan de Cuentas</a></li>
                          <li class="divider"></li>
                          <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Registros Contables</a>
                            <ul class="dropdown-menu">
                              <li><a href="<?php echo $servidor; ?>/modulos/contabilidad/asiento-de-diario.php">Asientos de Diario</a></li>
                              <li><a href="#">Asientos de Ajustes</a></li>
                              <li><a href="#">Asientos de Reclasificaciones</a></li>
                              <li><a href="#">Asientos de Cierres</a></li>
                            </ul>
                          </li>
                          <li class="divider"></li>
                          <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Reportes</a>
                            <ul class="dropdown-menu">
                              <li><a href="#">Libro Diario</a></li>
                              <li><a href="#">Balance General</a></li>
                              <li><a href="<?php echo $servidor; ?>/modulos/contabilidad/mayor-contable.php">Mayor Contable</a></li>
                              <li><a href="#">Balance de Comprobación</a></li>
                              <li><a href="#">Estado de Resultados</a></li>
                              <li><a href="#">Estado de Cambio en Patrimonio</a></li>
                              <li><a href="#">Estado de Flujo de Efectivo</a></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                <?php 
                    break;
                }
            }
        ?>
        
        <?php 
            for($i = 0; $i < count($_SESSION['id_modulo']); $i++){
                if($_SESSION['id_modulo'][$i] === '2'){
        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Bancos<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="<?php echo $servidor; ?>/modulos/bancos/bancos.php">Bancos</a></li>
                              <li class="divider"></li>
                              <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Movimientos Bancarios</a>
                                <ul class="dropdown-menu">
                                  <li><a tabindex="-1" href="<?php echo $servidor; ?>/modulos/bancos/deposito-bancario.php">Depósitos</a></li>

                                  <li><a href="<?php echo $servidor; ?>/modulos/bancos/egreso-bancario.php">Egreso Bancario</a></li>
                                    <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#">Transferencias</a>
                                        <ul class="dropdown-menu">
                                          <li><a tabindex="-1" href="<?php echo $servidor; ?>/modulos/bancos/transferencias-enviadas.php">Enviadas</a></li>
                                          <li><a href="<?php echo $servidor; ?>/modulos/bancos/transferencias-recibidas.php">Recibidas</a></li>
                                        </ul>
                                    </li>
                                  <li><a href="<?php echo $servidor; ?>/modulos/bancos/notas-debito.php">Notas de Débito</a></li>
                                  <li><a href="<?php echo $servidor; ?>/modulos/bancos/notas-credito.php">Notas de Crédito</a></li>
                                </ul>
                              </li>
                              <li class="divider"></li>
                              <li><a href="#">Reportes</a></li>

                            </ul>
                          </li>
        <?php
                    break;
                }    
            }
            
        ?>
        
        <?php 
            for($i = 0; $i < count($_SESSION['id_modulo']); $i++){
                if($_SESSION['id_modulo'][$i] === '3'){
        ?>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cuentas por Pagar<span class="caret"></span></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo $servidor; ?>/modulos/pagar/maestro_proveedores.php">Maestro de Proveedores</a></li>
                        <li><a href="#">Provisión Factura Proveedores</a></li>
                      </ul>
                    </li>
        <?php
                    break;
                }
            }
        ?>
                    
         <?php 
            for($i = 0; $i < count($_SESSION['id_modulo']); $i++){
                if($_SESSION['id_modulo'][$i] === '4'){
        ?>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cuentas por Cobrar<span class="caret"></span></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Emisión de Facturas</a></li>
                        <li><a href="#">Notas de Crédito</a></li>
                        
                      </ul>
                    </li>
        <?php
                    break;
                }
            }
        ?>

    </ul>
	
	<ul class="bienvenido nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Bienvenido <?php echo $_SESSION['usuario']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $servidor; ?>/logout.php">Cerrar Sesión</a></li>
          </ul>
        </li>
    </ul>
