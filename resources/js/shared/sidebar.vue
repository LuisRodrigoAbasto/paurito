<template>
  <div class="sidebar">
    <nav class="sidebar-nav">
      <ul class="nav">
        <!-- <li class="nav-item">
          <a class="nav-link" href="index.html">
            <i class="nav-icon icon-speedometer"></i> Dashboard
            <span class="badge badge-primary">NEW</span>
          </a>
        </li> -->
        <!-- <li class="nav-title">SISTEMA</li> -->
        <li class="nav-item nav-dropdown">
          <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="nav-icon icon-bag"></i> Almacén
          </a>
          <ul class="nav-dropdown-items">
            <li class="nav-item">
              <a class="nav-link" href="formulas">
                <i class="nav-icon icon-grid"></i> Formulas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="productos">
                <i class="nav-icon fa fa-product-hunt"></i> Productos
              </a>
            </li>
          </ul>
        </li>
       <li class="nav-item">
                    <a class="nav-link" href="ventas"><i class="nav-icon icon-basket-loaded"></i> Venta</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="compras"><i class="nav-icon icon-handbag"></i> Compra</a>
                </li>
                
                <li class="nav-item">
                <a class="nav-link" href="ingresos"><i class="nav-icon fa fa-money"></i> Ingreso</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="egresos"><i class="nav-icon fa fa-money"></i> Egreso</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="cuentas"><i class="nav-icon icon-briefcase"></i> Plan de Cuentas</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="balance_general"><i class="nav-icon icon-chart"></i> Balance General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="estado_resultado"><i class="nav-icon icon-chart"></i> Estado Resultado</a>
                    </li>
                <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon icon-people"></i> Acceso</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="nav-icon icon-user"></i> Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="nav-icon icon-user-following"></i> Roles</a>
                            </li>
                        </ul>
                    </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="nav-icon icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"> <i class=" fa fa-ban"></i><i class="nav-icon icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
                </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="typography.html">
            <i class="nav-icon icon-pencil"></i> Typography
          </a>
        </li> -->
      </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
  </div>
</template>
<script>
export default {};
</script>