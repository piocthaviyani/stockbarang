<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Barang Masuk</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Kenes Parcel</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-10">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                    <div class="nav">
                    <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Master</div>
                            <a class="nav-link" href="stock.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-pie"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransaksi" aria-expanded="false" aria-controls="collapseTransaksi">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                                Transaksi
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseTransaksi" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="masuk.php">Barang Masuk</a>
                                    <a class="nav-link" href="keluar.php">Barang Keluar</a>
                                    <a class="nav-link" href="peminjaman.php">Peminjaman Barang</a>
                                </nav>
                            </div>
                            
                    </div>
                </nav>
            </div>
                            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Barang Masuk</h1>
                        
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Barang
                                </button>
                                <br>
                                <div class="row mt-4">
                                <div class="col">
                                <form method="post" class="form-inline">
                                    <input type="date" name="tgl_mulai" class="form-control">
                                    <input type="date" name="tgl_selesai" class="form-control ml-3">
                                    <button type="submit" name="filter_tgl" class="btn btn-info ml-3">Tampilkan</button>
                                </form>
                                </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Barang</th>
                                                <th>Satuan</th>
                                                <th>Admin</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                            if (isset($_POST['filter_tgl'])) {
                                                $mulai = $_POST['tgl_mulai'];
                                                $selesai =$_POST['tgl_selesai'];

                                                if($mulai!=null || $selesai!=null){
                                                    $ambilsemuadatastock = mysqli_query($conn,"select * from masuk m, stock s where s.idbarang = m.idbarang and tanggal BETWEEN '$mulai' and DATE_ADD('$selesai',INTERVAL 1 DAY)");
                                                } else {
                                                    $ambilsemuadatastock = mysqli_query($conn,"select * from masuk m, stock s where s.idbarang = m.idbarang");

                                                }
                                                
                                            } else {
                                                $ambilsemuadatastock = mysqli_query($conn,"select * from masuk m, stock s where s.idbarang = m.idbarang");
                                            }
                                                
                                                while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                                    $idb = $data['idbarang'];
                                                    $idm = $data['idmasuk'];
                                                    $tanggal = $data['tanggal'];
                                                    $namabarang = $data['namabarang'];
                                                    $satuan = $data['satuan'];
                                                    $admin = $data['admin'];
                                                    $qty = $data['qty'];
                                                
                                                ?>
                                                <tr>
                                                    <td><?=$tanggal;?></td>
                                                    <td><?=$namabarang;?></td>
                                                    <td><?=$satuan;?></td>
                                                    <td><?=$admin;?></td>
                                                    <td><?=$qty;?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idm;?>">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idm;?>">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal" id="edit<?=$idm;?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                        <div class="modal-body">
                                                        <input type="text" name="satuan" value="<?=$satuan;?>" class="form-control" required>
                                                        <br>
                                                        <input type="number" name="qty" value="<?=$qty;?>" class="form-control" required>
                                                        <br>
                                                        <input type="text" name="admin" value="<?=$admin;?>" class="form-control" required>
                                                        <br>
                                                        <input type="hidden" name="idb" value="<?=$idb;?>">
                                                        <input type="hidden" name="idm" value="<?=$idm;?>">
                                                        <button type="submit" class="btn btn-primary" name="updatebarangmasuk">Submit</button>
                                                        </div>
                                                        </form>
                                                        
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                                <!-- Delete Modal -->
                                                <div class="modal" id="delete<?=$idm;?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <!-- Modal body -->
                                                        <form method="post">
                                                        <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus <?=$namabarang;?>?
                                                        <input type="hidden" name="idb" value="<?=$idb;?>">
                                                        <input type="hidden" name="kty" value="<?=$qty;?>">
                                                        <input type="hidden" name="idm" value="<?=$idm;?>">
                                                        <br>
                                                        <br>
                                                        <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Hapus</button>
                                                        </div>
                                                        </form>
                                                        
                                                    </div>
                                                    </div>
                                                </div>


                                                <?php
                                                };

                                                ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>

        <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Tambah Barang Masuk</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <form method="post">
            <div class="modal-body">

            <select name="barangnya" class="form-control">
                <?php
                    $ambilsemuadatanya = mysqli_query($conn,"select * from stock");
                    while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
                        $namabarang = $fetcharray['namabarang'];
                        $idbarangnya = $fetcharray['idbarang'];
                ?>

                <option value="<?=$idbarangnya;?>"><?=$namabarang;?></option>

                <?php
                    }
                ?>
                
            </select>
            <br>
            <select name="satuannya" class="form-control">
                <?php
                    $ambilsemuadatanya = mysqli_query($conn,"select * from stock");
                    while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
                        $satuan = $fetcharray['satuan'];
                        $idbarangnya = $fetcharray['idbarang'];
                ?>

                <option value="<?=$idbarangnya;?>"><?=$satuan;?></option>

                <?php
                    }
                ?>
                
            </select>
            <br>
            <input type="text" name="admin" class="form-control" placeholder="Admin" required>
            <br>
            <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
            <br>
            <button type="submit" class="btn btn-primary" name="barangmasuk">Submit</button>
            </div>
            </form>
            
        </div>
        </div>
    </div>

</html>
