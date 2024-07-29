<?php
session_start();

//membuat koneksi
$conn = mysqli_connect("localhost","root","","stockbarang");

//menambah barang baru
if(isset($_POST['barangstock'])){
    $namabarang = $_POST['namabarang'];
    $kategori   = $_POST['kategori'];
    $satuan   = $_POST['satuan'];
    $stock      = $_POST['stock'];

    $addtotable = mysqli_query($conn,"insert into stock (namabarang, kategori, satuan, stock) values('$namabarang','$kategori','$satuan','$stock')");
    if($addtotable){
        header('location:stock.php');
    } else {
        echo 'Gagal';
        header('location:stock.php');
    }
};

//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya  = $_POST['barangnya'];
    $satuan     = $_POST['satuan'];
    $admin      = $_POST['admin'];
    $qty        = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya     = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk  = mysqli_query($conn,"insert into masuk (idbarang, satuan, qty, admin) values('$barangnya','$satuan','$qty', '$admin')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//menambah barang keluar
if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima  = $_POST['penerima'];
    $qty       = $_POST['qty'];
    $admin     = $_POST['admin'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya     = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if ($stocksekarang >= $qty) {
        //kalau barangnya cukup
        $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;
 
        $addtokeluar  = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty, admin) values('$barangnya','$penerima','$qty', '$admin')");
        $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtokeluar&&$updatestockmasuk){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        //kalau barangnya ga cukup
        echo '
        <script>
            alert("Stock saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>
        ';
    }

}

//update info barang stock
if(isset($_POST['updatebarang'])) {
    $idb        = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $kategori  = $_POST['kategori'];
    $satuan  = $_POST['satuan'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', kategori='$kategori', satuan='$satuan' where idbarang='$idb'");
    if($update){
        header('location:stock.php');
    } else {
        echo 'Gagal';
        header('location:stock.php');
    }
}

//menghapus barang dari stock
if(isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn,"delete from stock where idbarang='$idb'");
    if($hapus){
    header('location:stock.php');
} else {
    echo 'Gagal';
    header('location:stock.php');
    }
};

//mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])) {
    $idb     = $_POST['idb'];
    $idm     = $_POST['idm'];
    $satuan  = $_POST['satuan'];
    $qty     = $_POST['qty'];
    $admin   = $_POST['admin'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya   = mysqli_fetch_array($lihatstock);
    $stockskrg  = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk ='$idm'");
    $qtynya  = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih  = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set admin='$admin', qty='$qty', satuan='$satuan' where idmasuk ='$idm'");
            if ($kurangistocknya&&$updatenya) {
                header('location:masuk.php');
            } else {
                echo 'Gagal';
                header('location:masuk.php');
        }
    } else {
        $selisih  = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set admin='$admin', qty='$qty', satuan='$satuan' where idmasuk ='$idm'");
            if ($kurangistocknya&&$updatenya) {
                header('location:masuk.php');
                } else {
                echo 'Gagal';
                header('location:masuk.php');
            }
    }
}

//menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock-$qty;
    
    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");

    if ($update&&$hapusdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }

}

//mengubah data keluar
if(isset($_POST['updatebarangkeluar'])) {
    $idb      = $_POST['idb'];
    $idk      = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty      = $_POST['qty'];
    $admin    = $_POST['admin'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya   = mysqli_fetch_array($lihatstock);
    $stockskrg  = $stocknya['stock'];

    $qtyskrg    = mysqli_query($conn, "select * from keluar where idkeluar ='$idk'");
    $qtynya     = mysqli_fetch_array($qtyskrg);
    $qtyskrg    = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih  = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set admin='$admin', qty='$qty', penerima='$penerima' where idkeluar ='$idk'");
            if ($kurangistocknya&&$updatenya) {
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php');
        }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update keluar set admin='$admin', qty='$qty', penerima='$penerima' where idkeluar ='$idk'");
            if ($kurangistocknya&&$updatenya) {
                header('location:keluar.php');
                } else {
                echo 'Gagal';
                header('location:keluar.php');
                }   
    }
}

//menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data   = mysqli_fetch_array($getdatastock);
    $stock  = $data['stock'];

    $selisih = $stock+$qty;

    $update     = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata  = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");

    if ($update&&$hapusdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }

}

//menambah admin baru
if(isset($_POST['addadmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn,"insert into login (username, password) values ('$username','$password')");

    if($queryinsert){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

//edit admin
if(isset($_POST['updateadmin'])) {
    $userbaru = $_POST['useradmin'];
    $passwordbaru = $_POST['newpassword'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn,"update login set username='$userbaru', password='$passwordbaru' where iduser='$idnya'");

    if($queryupdate){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

//menambah admin baru
if(isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn,"delete from login where iduser='$id'");

    if($querydelete){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

//peminjaman barang
if(isset($_POST['pinjam'])) {
    $idbarang = $_POST['barangnya'];
    $qty      = $_POST['qty'];
    $penerima = $_POST['penerima'];
    $admin    = $_POST['admin'];

    //ambil stock sekarang
    $stok_saat_ini = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
    $stok_nya = mysqli_fetch_array($stok_saat_ini);
    $stok = $stok_nya['stock'];

    //kurangin stock
    $new_stock = $stok-$qty;

    //mulai query insert
    $insertpinjam = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
    $stok_nya = mysqli_fetch_array($stok_saat_ini);
    $stok     = $stok_nya['stock'];

    //kurangin stock
    $new_stock = $stok-$qty;

    //penambahan data
    $insertpinjam = mysqli_query($conn,"INSERT INTO peminjaman (idbarang, qty, penerima, admin) values
    ('$idbarang','$qty','$penerima','$admin')");

    //mengurangi stock di tabel stock
    $kurangistock = mysqli_query($conn,"update stock set stock='$new_stock' where idbarang='$idbarang'");

    if($insertpinjam&&$kurangistock){
    //jika berhasil
        echo '
        <script>
            alert("Berhasil");
            window.location.href="peminjaman.php";
        </script>
        ';
    } else {
        echo '
        <script>
        alert("Gagal");
        window.location.href="peminjaman.php";
    </script>
    ';
    }
}

//selesai peminjaman
if(isset($_POST['barangkembali'])) {
    $idpinjam = $_POST['idpinjam'];
    $idbarang = $_POST['idbarang'];

    //eksekusi
    $update_status = mysqli_query($conn,"update peminjaman set status='Kembali' where idpeminjaman='$idpinjam'");

    //ambil stock sekarang
    $stok_saat_ini = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
    $stok_nya = mysqli_fetch_array($stok_saat_ini);
    $stok = $stok_nya['stock'];

    //ambil qty dari si idpinjam sekarang
    $stok_saat_ini1 = mysqli_query($conn,"select * from peminjaman where idpeminjaman='$idpinjam'");
    $stok_nya1 = mysqli_fetch_array($stok_saat_ini1);
    $stok1 = $stok_nya1['qty'];

    //kurangin stock
    $new_stock = $stok1+$stok;

    //kembalikan stocknya
    $kembalikan_stock = mysqli_query($conn,"update stock set stock='$new_stock' where idbarang='$idbarang'");

    if($insertpinjam&&$kembalikan_stock){
        //jika berhasil
            echo '
            <script>
                alert("Berhasil");
                window.location.href="peminjaman.php";
            </script>
            ';
        } else {
            echo '
            <script>
            alert("Gagal");
            window.location.href="peminjaman.php";
        </script>
        ';
        }
//mencetak laporan

    }
?>