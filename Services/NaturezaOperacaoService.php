<?php
namespace Services;

use \PDO;
use Lib\DataBase\ConnectionDatabase;
use Lib\Database\ResponseData;

class NaturezaOperacaoService
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @var ResponseData
     */
    private $responseData;

    public function __construct()
    {
        $this->db = ConnectionDatabase::getInstance();
        $this->responseData = new ResponseData();
    }

    /**
     * Get All
     *
     * @return array
     */
    public function findAll()
    {
        try {

            $sql = "SELECT * FROM public.paises ";

            $rs = $this->db->query($sql);
            $result = $rs->fetchAll(PDO::FETCH_ASSOC);

            return $this->responseData->success($result, (int) $rs->rowCount());
        } catch (\Exception $e) {
            return $this->responseData->error($e->getMessage());
        }
    }
}