<?php
namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    public function getDivZoneMOU(){
        $db = \Config\Database::connect();
        $builder = $db->table('div_zone_mou');
        $data=$builder->get()->getResult();
        return $data;
    }
}