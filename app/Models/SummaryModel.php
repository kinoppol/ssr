<?php
namespace App\Models;

use CodeIgniter\Model;

class SummaryModel extends Model
{
    public function getSummaryYear($year){
        $db = \Config\Database::connect();
        $builder = $db->table('zone');
        $data=$builder->get()->getResult();
        return $data;
    }

    public function getSummaryStudent($data=array()){
        $db = \Config\Database::connect();
        $builder = $db->table('summary_of_student');
        $builder->select('sum(count_val) as c');
        if(isset($data['org_code'])&&!is_array($data['org_code']))$builder->where('school_id',$data['org_code']);
        if(isset($data['org_code'])&&is_array($data['org_code']))$builder->whereIn('school_id',$data['org_code']);
        $data=$builder->get()->getResult();
        return $data['0']->c;
    }
}