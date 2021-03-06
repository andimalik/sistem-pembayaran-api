<?php

/**
 * Integration test PeninjauComponent dengan PDO.
 *
 * @author Andi Malik <andi.malik.notifications@gmail.com>
 */
class PeninjauComponentCallPdoIntegrationTest extends MyApp_Database_TestCase {

    private $object = NULL;

    public function setUp() {
        parent::setUp();

        $this->object = new PeninjauComponent;
        $this->object->setPDO(self::$pdo);
    }

    /**
     * @group integrationTest
     * @group componentObject
     * @covers PeninjauComponent::nomorTransaksiAda()
     * @covers PeninjauComponent::nomorReferensiAda()
     * @covers PeninjauComponent::nomorRekeningAda()
     * @covers PeninjauComponent::nomorKasirAda()
     * @covers PeninjauComponent::nomorSiswaAda()
     * @covers PeninjauComponent::nomorUnitAda()
     * @covers PeninjauComponent::nomorJenisPembayaranAda()
     * @covers PeninjauComponent::hitungBanyaknyaALokasi()
     * @covers PeninjauComponent::perolehJumlahTagihanSiswa()
     * @covers PeninjauComponent::perolehIdUnitDanIdJenisPembayaranDariKodeProduk()
     * @covers PeninjauComponent::perolehIdSiswaDariNIS()
     * @covers PeninjauComponent::perolehIdTagihanTerlama()
     * @covers PeninjauComponent::perolehIdDistribusiSisaTerlama()
     * @covers PDO::setAttribute()
     */
    public function testCallSetAttribute() {
        $this->assertTrue($this->object->getPDO()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
    }

    /**
     * @group integrationTest
     * @group databaseAccess
     * @group componentObject
     * @depends testCallSetAttribute
     * @covers PeninjauComponent::nomorTransaksiAda()
     * @covers PeninjauComponent::nomorReferensiAda()
     * @covers PeninjauComponent::rekeningAda()
     * @covers PeninjauComponent::kasirAda()
     * @covers PeninjauComponent::siswaAda()
     * @covers PeninjauComponent::unitAda()
     * @covers PeninjauComponent::jenisPembayaranAda()
     * @covers PeninjauComponent::hitungBanyaknyaAlokasi()
     * @covers PeninjauComponent::perolehJumlahTagihanSiswa()
     * @covers PeninjauComponent::perolehIdUnitDanIdJenisPembayaranDariKodeProduk()
     * @covers PeninjauComponent::perolehIdSiswaDariNIS()
     * @covers PeninjauComponent::perolehIdTagihanTerlama()
     * @covers PeninjauComponent::perolehIdDistribusiSisaTerlama()
     * @covers PDO::prepare()
     */
    public function testCallPrepare() {
        $pdoStatements = array();

        foreach ($this->getMethods() as $methodName => $sql) {
            $pdoStatements[$methodName] = $this->object->getPDO()->prepare($sql);
            $this->assertInstanceOf("PDOStatement", $pdoStatements[$methodName], "Gagal prepare statement untuk method " . $methodName . ".");
        }

        return $pdoStatements;
    }

    /**
     * @group integrationTest
     * @group databaseAccess
     * @group componentObject
     * @depends testCallPrepare
     * @covers PeninjauComponent::nomorTransaksiAda()
     * @covers PeninjauComponent::nomorReferensiAda()
     * @covers PeninjauComponent::rekeningAda()
     * @covers PeninjauComponent::kasirAda()
     * @covers PeninjauComponent::siswaAda()
     * @covers PeninjauComponent::unitAda()
     * @covers PeninjauComponent::jenisPembayaranAda()
     * @covers PeninjauComponent::hitungBanyaknyaAlokasi()
     * @covers PeninjauComponent::perolehJumlahTagihanSiswa()
     * @covers PeninjauComponent::perolehIdUnitDanIdJenisPembayaranDariKodeProduk()
     * @covers PeninjauComponent::perolehIdSiswaDariNIS()
     * @covers PeninjauComponent::perolehIdTagihanTerlama()
     * @covers PeninjauComponent::perolehIdDistribusiSisaTerlama()
     * @covers PDOStatement::execute()
     */
    public function testCallExecute($pdoStatements) {
        foreach ($pdoStatements as $methodName => $pdoStatement) {
            foreach ($this->getInputParameters($methodName) as $inputParameter) {
                $this->assertTrue($pdoStatement->execute($inputParameter[0]), "Gagal execute query untuk method " . $methodName . ".");
            }
        }
    }

    /**
     * @group integrationTest
     * @group databaseAccess
     * @group componentObject
     * @depends testCallExecute
     * @covers PeninjauComponent::nomorTransaksiAda()
     * @covers PeninjauComponent::nomorReferensiAda()
     * @covers PeninjauComponent::rekeningAda()
     * @covers PeninjauComponent::kasirAda()
     * @covers PeninjauComponent::siswaAda()
     * @covers PeninjauComponent::unitAda()
     * @covers PeninjauComponent::jenisPembayaranAda()
     * @covers PeninjauComponent::hitungBanyaknyaAlokasi()
     * @covers PeninjauComponent::perolehJumlahTagihanSiswa()
     * @covers PeninjauComponent::perolehIdUnitDanIdJenisPembayaranDariKodeProduk()
     * @covers PeninjauComponent::perolehIdSiswaDariNIS()
     * @covers PeninjauComponent::perolehIdTagihanTerlama()
     * @covers PeninjauComponent::perolehIdDistribusiSisaTerlama()
     * @covers PDOStatement::fetch()
     */
    public function testCallFetch() {
        $this->mySetDataSet($this->getPresetDataset());
        $this->myDisableForeignKeyChecks();
        $this->mySetup($this->getDataSet());
        $this->myEnableForeignKeyChecks();

        $pdoStatements = array();

        foreach ($this->getMethods() as $methodName => $sql) {
            foreach ($this->getInputParameters($methodName) as $inputParameter) {
                $pdoStatement = $this->object->getPDO()->prepare($sql);
                $pdoStatement->execute($inputParameter[0]);
                $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);
                $resultKey = $this->getResultKey($methodName);

                $actual = is_array($resultKey) ? $result : $result[$resultKey];

                $this->assertEquals($inputParameter[1], $actual, "Result yang dikembalikan query pada method " . $methodName . " tidak sesuai harapan. " . $pdoStatement->queryString . " " . print_r($inputParameter[0], TRUE));
                $pdoStatements[] = $pdoStatement;
            }
        }

        return $pdoStatements;
    }

    /**
     * @group integrationTest
     * @group databaseAccess
     * @group componentObject
     * @depends testCallFetch
     * @covers PeninjauComponent::nomorTransaksiAda()
     * @covers PeninjauComponent::nomorReferensiAda()
     * @covers PeninjauComponent::rekeningAda()
     * @covers PeninjauComponent::kasirAda()
     * @covers PeninjauComponent::siswaAda()
     * @covers PeninjauComponent::unitAda()
     * @covers PeninjauComponent::jenisPembayaranAda()
     * @covers PeninjauComponent::hitungBanyaknyaAlokasi()
     * @covers PeninjauComponent::perolehJumlahTagihanSiswa()
     * @covers PeninjauComponent::perolehIdUnitDanIdJenisPembayaranDariKodeProduk()
     * @covers PeninjauComponent::perolehIdSiswaDariNIS()
     * @covers PeninjauComponent::perolehIdTagihanTerlama()
     * @covers PeninjauComponent::perolehIdDistribusiSisaTerlama()
     * @covers PDOStatement::closeCursor()
     */
    public function testCallCloseCursor($pdoStatements) {
        foreach ($pdoStatements as $pdoStatement) {
            $this->assertTrue($pdoStatement->closeCursor());
        }
    }

    private function getInputParameters($methodName) {
        $inputSet = array(
            "nomorTransaksiAda" => array(
                array(array(":nomor_transaksi" => "T001", ":id_rekening" => 1), 1),
                array(array(":nomor_transaksi" => "T001", ":id_rekening" => 2), 1),
                array(array(":nomor_transaksi" => "T001", ":id_rekening" => 3), 0)
            ),
            "nomorReferensiAda" => array(
                array(array(":nomor_referensi" => "R001", ":id_rekening" => 1), 1),
                array(array(":nomor_referensi" => "R001", ":id_rekening" => 2), 1),
                array(array(":nomor_referensi" => "R001", ":id_rekening" => 3), 0)
            ),
            "rekeningAda" => array(
                array(array(":id" => 1), 1),
                array(array(":id" => 4), 0)
            ),
            "kasirAda" => array(
                array(array(":id" => 1), 1),
                array(array(":id" => 4), 0)
            ),
            "siswaAda" => array(
                array(array(":id" => 1), 1),
                array(array(":id" => 4), 0)
            ),
            "unitAda" => array(
                array(array(":id" => 1), 1),
                array(array(":id" => 5), 0)
            ),
            "jenisPembayaranAda" => array(
                array(array(":id" => 1), 1),
                array(array(":id" => 5), 0)
            ),
            "hitungBanyaknyaAlokasi" => array(
                array(array(":id_transaksi" => 1), 2),
                array(array(":id_transaksi" => 2), 3)
            ),
            "perolehJumlahTagihanSiswa" => array(
                array(array(":id_unit" => 4, ":id_jenis_pembayaran" => 3, ":id_siswa" => 1, ":waktu_tagihan" => "2014-05-01 00:00:00"), 150000.00000),
                array(array(":id_unit" => 4, ":id_jenis_pembayaran" => 3, ":id_siswa" => 1, ":waktu_tagihan" => "2014-04-01 00:00:00"), 50000.00000),
                array(array(":id_unit" => 4, ":id_jenis_pembayaran" => 3, ":id_siswa" => 1, ":waktu_tagihan" => "2014-03-01 00:00:00"), 0.00000)
            ),
            "perolehIdUnitDanIdJenisPembayaranDariKodeProduk" => array(
                array(array(":kode_produk" => "33"), array("id_unit" => 4, "id_jenis_pembayaran" => 3)),
                array(array(":kode_produk" => "34"), array("id_unit" => 4, "id_jenis_pembayaran" => 4)),
                array(array(":kode_produk" => "99"), FALSE)
            ),
            "perolehIdSiswaDariNIS" => array(
                array(array(":nis" => "1000000001"), 1),
                array(array(":nis" => "1000000002"), 2),
                array(array(":nis" => "9999999999"), FALSE),
            ),
            "perolehIdTagihanTerlama" => array(
                array(array(":id_unit" => 4, ":id_jenis_pembayaran" => 3, ":id_siswa" => 1), 3),
                array(array(":id_unit" => 9, ":id_jenis_pembayaran" => 9, ":id_siswa" => 9), FALSE),
            ),
            "perolehIdDistribusiSisaTerlama" => array(
                array(array(":id_unit" => 4, ":id_jenis_pembayaran" => 3, ":id_siswa" => 1), 4),
                array(array(":id_unit" => 9, ":id_jenis_pembayaran" => 9, ":id_siswa" => 9), FALSE),
            )
        );

        if (array_key_exists($methodName, $inputSet)) {
            return $inputSet[$methodName];
        }

        return array();
    }

    private function getMethods() {
        return array(
            "nomorTransaksiAda" => "SELECT COUNT(*) AS count_result FROM transaksi t INNER JOIN rekening r ON (t.id_rekening = r.id) WHERE t.nomor_transaksi LIKE :nomor_transaksi AND r.id_bank = (SELECT id_bank FROM rekening WHERE id = :id_rekening)",
            "nomorReferensiAda" => "SELECT COUNT(*) AS count_result FROM transaksi t INNER JOIN rekening r ON (t.id_rekening = r.id) WHERE t.nomor_referensi LIKE :nomor_referensi AND r.id_bank = (SELECT id_bank FROM rekening WHERE id = :id_rekening)",
            "rekeningAda" => "SELECT COUNT(*) AS count_result FROM rekening WHERE id = :id",
            "kasirAda" => "SELECT COUNT(*) AS count_result FROM kasir WHERE id = :id",
            "siswaAda" => "SELECT COUNT(*) AS count_result FROM siswa WHERE id = :id",
            "unitAda" => "SELECT COUNT(*) AS count_result FROM unit WHERE id = :id",
            "jenisPembayaranAda" => "SELECT COUNT(*) AS count_result FROM jenis_pembayaran WHERE id = :id",
            "hitungBanyaknyaAlokasi" => "SELECT COUNT(*) AS count_result FROM alokasi WHERE id_transaksi = :id_transaksi",
            "perolehJumlahTagihanSiswa" => "SELECT SUM(sisa) AS sum_result FROM tagihan WHERE id_unit = :id_unit AND id_jenis_pembayaran = :id_jenis_pembayaran AND id_siswa = :id_siswa AND waktu_tagihan <= :waktu_tagihan AND sisa != 0",
            "perolehIdUnitDanIdJenisPembayaranDariKodeProduk" => "SELECT id_unit, id_jenis_pembayaran FROM produk WHERE kode_produk LIKE :kode_produk",
            "perolehIdSiswaDariNIS" => "SELECT id FROM siswa WHERE nis LIKE :nis",
            "perolehIdTagihanTerlama" => "SELECT id FROM tagihan WHERE id_unit = :id_unit AND id_jenis_pembayaran = :id_jenis_pembayaran AND id_siswa = :id_siswa AND sisa != 0 ORDER BY waktu_tagihan ASC, id ASC LIMIT 1 OFFSET 0",
            "perolehIdDistribusiSisaTerlama" => "SELECT d.id FROM distribusi d INNER JOIN alokasi a ON d.id_alokasi = a.id WHERE a.id_unit = :id_unit AND a.id_jenis_pembayaran = :id_jenis_pembayaran AND a.id_siswa = :id_siswa AND d.id_tagihan IS NULL ORDER BY id ASC LIMIT 1 OFFSET 0"
        );
    }

    private function getResultKey($methodName) {
        $resultKeys = array(
            "nomorTransaksiAda" => "count_result",
            "nomorReferensiAda" => "count_result",
            "rekeningAda" => "count_result",
            "kasirAda" => "count_result",
            "siswaAda" => "count_result",
            "unitAda" => "count_result",
            "jenisPembayaranAda" => "count_result",
            "hitungBanyaknyaAlokasi" => "count_result",
            "perolehJumlahTagihanSiswa" => "sum_result",
            "perolehIdUnitDanIdJenisPembayaranDariKodeProduk" => array("id_unit", "id_jenis_pembayaran"),
            "perolehIdSiswaDariNIS" => "id",
            "perolehIdTagihanTerlama" => "id",
            "perolehIdDistribusiSisaTerlama" => "id"
        );

        return $resultKeys[$methodName];
    }

    private function getPresetDataset() {
        return array(
            "bank" => array(
                array("id" => 1, "nama" => "Bank 1", "label" => "B1"),
                array("id" => 2, "nama" => "Bank 2", "label" => "B2"),
                array("id" => 3, "nama" => "Bank 3", "label" => "B3")
            ),
            "rekening" => array(
                array("id" => 1, "id_bank" => 1, "nomor" => "309001", "nama" => "Bank 1 9001", "label" => "B1 9001"),
                array("id" => 2, "id_bank" => 1, "nomor" => "309002", "nama" => "Bank 1 9002", "label" => "B1 9002"),
                array("id" => 3, "id_bank" => 2, "nomor" => "307001", "nama" => "Bank 2 7001", "label" => "B2 7001")
            ),
            "transaksi" => array(
                array("id" => 1, "jenis" => "TRANSAKSI", "kategori" => "PEMBAYARAN", "metode" => "ONLINE", "batal" => 0, "waktu_transaksi" => "2014-05-04 00:00:00", "waktu_laporan" => "2014-05-04 00:00:00", "waktu_entri" => "2014-05-04 00:00:00", "id_rekening" => 1, "id_kasir" => 1, "nomor_transaksi" => "T001", "nomor_referensi" => "R001", "nilai" => 3.00000),
                array("id" => 2, "jenis" => "TRANSAKSI", "kategori" => "PEMBAYARAN", "metode" => "ONLINE", "batal" => 0, "waktu_transaksi" => "2014-05-04 00:00:00", "waktu_laporan" => "2014-05-04 00:00:00", "waktu_entri" => "2014-05-04 00:00:00", "id_rekening" => 2, "id_kasir" => 1, "nomor_transaksi" => "T002", "nomor_referensi" => "R002", "nilai" => 3.00000),
                array("id" => 3, "jenis" => "TRANSAKSI", "kategori" => "PEMBAYARAN", "metode" => "ONLINE", "batal" => 0, "waktu_transaksi" => "2014-05-04 00:00:00", "waktu_laporan" => "2014-05-04 00:00:00", "waktu_entri" => "2014-05-04 00:00:00", "id_rekening" => 3, "id_kasir" => 1, "nomor_transaksi" => "T003", "nomor_referensi" => "R003", "nilai" => 3.00000)
            ),
            "alokasi" => array(
                array("id" => 1, "id_transaksi" => 1, "id_siswa" => 1, "id_unit" => 1, "id_jenis_pembayaran" => 1, "nilai" => 1.00000, "sisa" => 1.00000, "terdistribusi" => 0.00000),
                array("id" => 2, "id_transaksi" => 1, "id_siswa" => 1, "id_unit" => 1, "id_jenis_pembayaran" => 2, "nilai" => 2.00000, "sisa" => 2.00000, "terdistribusi" => 0.00000),
                array("id" => 3, "id_transaksi" => 2, "id_siswa" => 1, "id_unit" => 1, "id_jenis_pembayaran" => 1, "nilai" => 1.00000, "sisa" => 1.00000, "terdistribusi" => 0.00000),
                array("id" => 4, "id_transaksi" => 2, "id_siswa" => 1, "id_unit" => 1, "id_jenis_pembayaran" => 2, "nilai" => 1.00000, "sisa" => 1.00000, "terdistribusi" => 0.00000),
                array("id" => 5, "id_transaksi" => 2, "id_siswa" => 1, "id_unit" => 1, "id_jenis_pembayaran" => 3, "nilai" => 1.00000, "sisa" => 1.00000, "terdistribusi" => 0.00000),
                array("id" => 7, "id_transaksi" => 7, "id_siswa" => 1, "id_unit" => 4, "id_jenis_pembayaran" => 3, "nilai" => 10.00000, "sisa" => 0.00000, "terdistribusi" => 10.00000),
                array("id" => 8, "id_transaksi" => 8, "id_siswa" => 1, "id_unit" => 4, "id_jenis_pembayaran" => 3, "nilai" => 10.00000, "sisa" => 0.00000, "terdistribusi" => 10.00000)
            ),
            "kasir" => array(
                array("id" => 1, "nama" => "Kasir 1"),
                array("id" => 2, "nama" => "Kasir 2"),
                array("id" => 3, "nama" => "Kasir 3")
            ),
            "siswa" => array(
                array("id" => 1, "nama" => "Siswa 1", "nis" => "1000000001"),
                array("id" => 2, "nama" => "Siswa 2", "nis" => "1000000002"),
                array("id" => 3, "nama" => "Siswa 3", "nis" => "1000000003")
            ),
            "unit" => array(
                array("id" => 1, "nama" => "TK", "label" => "TK"),
                array("id" => 2, "nama" => "SD", "label" => "SD"),
                array("id" => 3, "nama" => "SMP", "label" => "SMP"),
                array("id" => 4, "nama" => "SMA", "label" => "SMA")
            ),
            "jenis_pembayaran" => array(
                array("id" => 1, "nama" => "UM", "label" => "TM"),
                array("id" => 2, "nama" => "UK", "label" => "UK"),
                array("id" => 3, "nama" => "SPP", "label" => "SPP"),
                array("id" => 4, "nama" => "US", "label" => "US")
            ),
            "tagihan" => array(
                array(
                    "id" => 1,
                    "waktu_tagihan" => "2014-03-01 00:00:00",
                    "id_tahun_ajaran" => 1,
                    "id_siswa" => 2,
                    "id_bulan" => 9,
                    "id_jenis_pembayaran" => 3,
                    "nilai" => 100000.00000,
                    "terbayar" => 0.00000,
                    "sisa" => 100000.00000,
                    "id_unit" => 4,
                    "id_program" => 1,
                    "id_jurusan" => 1,
                    "id_tingkat" => 15,
                    "id_kelas" => 45
                ),
                array(
                    "id" => 2,
                    "waktu_tagihan" => "2014-03-01 00:00:00",
                    "id_tahun_ajaran" => 1,
                    "id_siswa" => 1,
                    "id_bulan" => 9,
                    "id_jenis_pembayaran" => 3,
                    "nilai" => 100000.00000,
                    "terbayar" => 100000.00000,
                    "sisa" => 0.00000,
                    "id_unit" => 4,
                    "id_program" => 1,
                    "id_jurusan" => 1,
                    "id_tingkat" => 15,
                    "id_kelas" => 45
                ),
                array(
                    "id" => 3,
                    "waktu_tagihan" => "2014-04-01 00:00:00",
                    "id_tahun_ajaran" => 1,
                    "id_siswa" => 1,
                    "id_bulan" => 10,
                    "id_jenis_pembayaran" => 3,
                    "nilai" => 100000.00000,
                    "terbayar" => 50000.00000,
                    "sisa" => 50000.00000,
                    "id_unit" => 4,
                    "id_program" => 1,
                    "id_jurusan" => 1,
                    "id_tingkat" => 15,
                    "id_kelas" => 45
                ),
                array(
                    "id" => 4,
                    "waktu_tagihan" => "2014-04-01 00:00:00",
                    "id_tahun_ajaran" => 1,
                    "id_siswa" => 1,
                    "id_bulan" => 10,
                    "id_jenis_pembayaran" => 2,
                    "nilai" => 100000.00000,
                    "terbayar" => 50000.00000,
                    "sisa" => 50000.00000,
                    "id_unit" => 4,
                    "id_program" => 1,
                    "id_jurusan" => 1,
                    "id_tingkat" => 15,
                    "id_kelas" => 45
                ),
                array(
                    "id" => 5,
                    "waktu_tagihan" => "2014-05-01 00:00:00",
                    "id_tahun_ajaran" => 1,
                    "id_siswa" => 1,
                    "id_bulan" => 11,
                    "id_jenis_pembayaran" => 3,
                    "nilai" => 100000.00000,
                    "terbayar" => 0.00000,
                    "sisa" => 100000.00000,
                    "id_unit" => 4,
                    "id_program" => 1,
                    "id_jurusan" => 1,
                    "id_tingkat" => 15,
                    "id_kelas" => 45
                )
            ),
            "produk" => array(
                array("id" => 1, "kode_produk" => "33", "id_unit" => 4, "id_jenis_pembayaran" => 3),
                array("id" => 2, "kode_produk" => "34", "id_unit" => 4, "id_jenis_pembayaran" => 4)
            ),
            "distribusi" => array(
                array("id" => 1, "id_alokasi" => 7, "id_tagihan" => 7, "nilai" => 3.00000),
                array("id" => 2, "id_alokasi" => 7, "id_tagihan" => 8, "nilai" => 3.00000),
                array("id" => 3, "id_alokasi" => 7, "id_tagihan" => 9, "nilai" => 3.00000),
                array("id" => 4, "id_alokasi" => 7, "id_tagihan" => NULL, "nilai" => 1.00000),
                array("id" => 5, "id_alokasi" => 8, "id_tagihan" => NULL, "nilai" => 10.00000)
            )
        );
    }

}
