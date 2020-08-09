<div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        <li @click="menu=0" class="nav-item">
                            <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
                        </li>
                        <li class="nav-title">
                            M E N Ú
                        </li>
         
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-notebook"></i> Registros</a>
                            <ul class="nav-dropdown-items">
                                <li @click="menu=7" class="nav-item">
                                    <a class="nav-link" href="#"><i class="icon-notebook"></i> Méritos</a>
                                </li>
                                <li @click="menu=8" class="nav-item">
                                    <a class="nav-link" href="#"><i class="icon-notebook"></i> Deméritos</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                            <ul class="nav-dropdown-items">
                                <li @click="menu=11" class="nav-item">
                                    <a class="nav-link" href="#"><i class="icon-chart"></i> Lista de Franco de Honor</a>
                                </li>
                                <li @click="menu=12" class="nav-item">
                                    <a class="nav-link" href="#"><i class="icon-chart"></i> Hoja de Control de Meritos y Demeritos</a>
                                </li>
                            </ul>
                        </li>
                        <li @click="menu=12" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-book-open"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
                        </li>
                        <li @click="menu=13" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-info"></i> Acerca de...<span class="badge badge-info">IT</span></a>
                        </li>
                    </ul>
                </nav>
                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>